$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function() {
    // $('body').on('click', '.group-navigate-btn', function() {
    //     const $this = $(this);
    //     const others = $('.group-navigate-btn').not('.group-navigate-btn-sticky');

    //     const expansion = $(this).data('expansion');
    //     const $current = $('.group-container:visible');
    //     const $next = $(`.group-container[data-group="${expansion}"]`);
        
    //     $current.addClass('animate__animated animate__fadeOutLeft').css('animation-duration', '0.55s');

    //     $current.one('animationend', function() {
    //         $current.hide().removeClass('d-flex animate__animated animate__fadeOutLeft');
    //         if (expansion == 'rooms') {
    //             $next.show().addClass('animate__animated animate__slideInDown').css('animation-duration', '0.55s');
    //         } else {
    //             $next.show().addClass('d-flex animate__animated animate__slideInDown').css('animation-duration', '0.55s');
    //         }
    //         $next.one('animationend', function() {
    //             $next.removeClass('animate__animated animate__slideInDown');
    //         });
    //     });

    //     others.addClass('animate__animated animate__fadeOutLeft').css('animation-duration', '0.55s').one('animationend', function() {
    //         $(this).hide().removeClass('animate__animated animate__fadeOutLeft');
            
    //         if (others.filter(':visible').length === 0 && $this.data('expansion') === 'activities') {
    //             $this.animate({ width: '100%' }, { duration: 1200, easing: 'cubicBezier(.25,.1,.25,1)' });
    //         }
    //     });

    //     $('.go-to-main-btn').show();
    //     $('.go-to-settings-btn').hide();

    //     // setTimeout(function() {
    //     //     $($this).css('transition', '0.55s ease');
    //     //     $($this).css('background-color', '#212529bf');
    //     //     $($this).css('opacity', '0.5');

    //     //     $($this).find('svg').hide();
    //     //     $($this).find('p').removeClass('mt-3');
    //     // }, 550);
    // });

    let syntaxMapping = {};

    $.getJSON("/files/syntax_mapping.json", function (data) {
        syntaxMapping = data;
    });

    let mediaRecorder;
    let audioChunks = [];
    let silenceTimeout;
    
    $('.speech-recognition-btn').on('click', async function () {
        const $this = $(this);
        const $status = $('.speech-recognition-status-text');
        const $output = $('.speech-recognition-output code');

        if ($this.hasClass('speech-recognition-inactive')) {
            audioChunks = []; // Clear previous recordings
            $this.removeClass('speech-recognition-inactive text-muted')
                .addClass('speech-recognition-active text-primary');
            $status.text('LISTENING');
            $output.html('<span class="text-muted">Listening...</span>');

            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);
                mediaRecorder.start();

                mediaRecorder.ondataavailable = function (event) {
                    if (event.data.size > 0) {
                        audioChunks.push(event.data);
                    }
                    resetSilenceTimeout();
                };

                mediaRecorder.onstop = function () {
                    processAudio();
                };

            } catch (error) {
                console.error("Microphone access error:", error);
                $output.html('<span class="text-danger">Microphone access denied.</span>');
            }
        } else {
            stopRecording();
        }
    });

    function resetSilenceTimeout() {
        clearTimeout(silenceTimeout);
        silenceTimeout = setTimeout(stopRecording, 5000);
    }

    function stopRecording() {
        if (mediaRecorder && mediaRecorder.state !== "inactive") {
            mediaRecorder.stop();
        }

        $('.speech-recognition-btn').removeClass('speech-recognition-active text-primary')
            .addClass('speech-recognition-inactive text-muted');
        $('.speech-recognition-status-text').text('NOT LISTENING');
    }

    function processAudio() {
        const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
        const reader = new FileReader();
    
        reader.onloadend = function () {
            const base64Audio = reader.result.split(',')[1];
    
            $.ajax({
                url: "https://9ppl2g4bp9.execute-api.ap-southeast-1.amazonaws.com/dev/prim/transcribe/start",
                type: "POST",
                headers: { "Content-Type": "application/json; charset=utf-8" },
                data: JSON.stringify({ audio: base64Audio }),
                success: function (response) {
                    console.log("AWS Response:", response);
                    
                    if (typeof response === "string") {
                        response = JSON.parse(response);
                    }
                    
                    setTimeout(function() { 
                        console.log("Parsed Response object:", response);
                        console.log("Job name:", response.job_name);
                
                        if (response.job_name) {
                            checkTranscription(response.job_name);
                        } else {
                            console.error("No job name found.");
                        }
                    }, 1000);
                },             
                error: function (xhr, status, error) {
                    console.error("AWS Error:", error);
                }
            });
        };
    
        reader.readAsDataURL(audioBlob);
    }
    
    function checkTranscription(jobName) {
        $.ajax({
            url: "https://9ppl2g4bp9.execute-api.ap-southeast-1.amazonaws.com/dev/prim/transcribe/check",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ 
                job_name: jobName,
                method: 'check'
            }),
            success: function (response, status, xhr) {
                if (typeof response === "string") {
                    response = JSON.parse(response);
                }

                if (xhr.status === 200 && response.transcript_url) {
                    console.log("Transcription Completed:", response.transcript_url);
                    fetchTranscript(response.transcript_url);
                } else if (xhr.status === 202) {
                    console.log("Transcription in progress...");
                    setTimeout(() => checkTranscription(jobName), 2000);
                } else {
                    console.error("Unexpected response:", response);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching transcription:", error);
            }
        });
    }    

    function fetchTranscript(transcriptUrl) {
        $.ajax({
            url: "https://9ppl2g4bp9.execute-api.ap-southeast-1.amazonaws.com/dev/prim/transcribe/check",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ 
                transcript_url: transcriptUrl,
                method: 'retrieve'
            }),
            success: function (response) {
                if (typeof response === "string") {
                    response = JSON.parse(response);
                }

                const transcriptText = response.results.transcripts[0].transcript;
                
                $('.speech-recognition-output-speech').val(transcriptText);
                convertPseudocode();
            },
            error: function (xhr, status, error) {
                console.error("Error fetching transcript text:", error);
            }
        });
    }
    
    function convertPseudocode() {
        const input = $('.speech-recognition-output-speech').val();
        const language = $('.speech-recognition-output-language').val();

        console.log("=== Converting Pseudocode to " + language.toUpperCase() + " ===");
        console.log("Original Input:", input);
    
        let cleanedSpeech = input.toLowerCase().replace(/[^a-z0-9" ]+/g, "").trim();
        console.log("Cleaned Speech:", cleanedSpeech);
    
        let words = cleanedSpeech.split(" ");
        let outputCode = "";
        let usedIndices = new Set();
        let indentLevel = 0;
        const indentSize = 4;
    
        Object.keys(syntaxMapping).forEach(key => {
            syntaxMapping[key].patterns.forEach(pattern => {
                let processedPattern = pattern.replace(/\(\?:([a-z ]+)\)\?/gi, "$1").trim();
                let patternWords = processedPattern.toLowerCase().split(" ");
                let params = [];
                let extractedWords = [];
                let inputIndex = 0;
                let match = true;
                let localUsedIndices = new Set();
    
                for (let i = 0; i < patternWords.length; i++) {
                    let word = patternWords[i];
                    
                    if (word.match(/\{\d+\}/)) {
                        while (inputIndex < words.length && 
                              (usedIndices.has(inputIndex) || localUsedIndices.has(inputIndex))) {
                            inputIndex++;
                        }
                        if (inputIndex < words.length) {
                            params.push(words[inputIndex]);
                            extractedWords.push(words[inputIndex]);
                            localUsedIndices.add(inputIndex);
                            inputIndex++;
                        } else {
                            match = false;
                            break;
                        }
                    } else {
                        while (inputIndex < words.length && 
                              (usedIndices.has(inputIndex) || 
                               localUsedIndices.has(inputIndex) || 
                               words[inputIndex] !== word)) {
                            inputIndex++;
                        }
                        if (inputIndex >= words.length) {
                            match = false;
                            break;
                        }
                        extractedWords.push(words[inputIndex]);
                        localUsedIndices.add(inputIndex);
                        inputIndex++;
                    }
                }
    
                if (match && syntaxMapping[key].languages[language]) {
                    localUsedIndices.forEach(index => usedIndices.add(index));
                    let code = syntaxMapping[key].languages[language];
                    params.forEach((param, index) => {
                        code = code.replace(`{${index + 1}}`, param);
                    });
    
                    // Add indentation
                    const indent = ' '.repeat(indentLevel * indentSize);
                    let codeLine = indent + code;
    
                    // Handle closing braces and new lines
                    if (syntaxMapping[key].requires_closing_brace) {
                        switch(language) {
                            case 'python':
                                codeLine += ':';
                                outputCode += codeLine + '\n';
                                indentLevel++;
                                break;
                            case 'javascript':
                            case 'java':
                                outputCode += codeLine + '\n';
                                outputCode += indent + '}\n';
                                break;
                        }
                    } else {
                        outputCode += codeLine + '\n';
                    }
    
                    // Handle indentation for created new lines
                    if (syntaxMapping[key].creates_new_line && !syntaxMapping[key].requires_closing_brace) {
                        outputCode += '\n';
                    }
                }
            });
        });
    
        console.log("\nFinal Generated Code:\n" + outputCode);
        $('.speech-recognition-output-code pre').html(outputCode);
        return true;
    }

    // const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    // recognition.continuous = true;
    // recognition.interimResults = true;
    // recognition.lang = 'en-US';

    // let finalTranscript = '';
    // let silenceTimeout;

    // $('.speech-recognition-btn').on('click', function () {
    //     const $this = $(this);
    //     const $status = $('.speech-recognition-status-text');
    //     const $output = $('.speech-recognition-output code');

    //     if ($this.hasClass('speech-recognition-inactive')) {
    //         finalTranscript = ''; 
    //         $this.removeClass('speech-recognition-inactive text-muted')
    //             .addClass('speech-recognition-active text-primary');
    //         $status.text('LISTENING');
    //         $output.html('<span class="text-muted">Listening...</span>');

    //         recognition.start();
    //     } else {
    //         stopRecognition();
    //     }
    // });

    // recognition.onresult = function (event) {
    //     clearTimeout(silenceTimeout);

    //     let newText = '';
    //     let lastProcessed = finalTranscript.trim(); 

    //     for (let i = event.resultIndex; i < event.results.length; i++) {
    //         if (event.results[i].isFinal) {
    //             let transcript = event.results[i][0].transcript.trim();
    //             if (!lastProcessed.endsWith(transcript)) { 
    //                 newText += transcript + ' ';
    //             }
    //         }
    //     }

    //     if (newText.trim() !== '') {
    //         finalTranscript += newText;
    //     }

    //     const pseudocodeKeywords = /\b(if|else|elseif|while|for|do|until|repeat|switch|case|default|break|function|procedure|return|print|input|output|declare|set|assign|begin|end)\b/gi;
    //     const highlightedText = finalTranscript.replace(pseudocodeKeywords, '<span class="text-warning">$&</span>');

    //     $('.speech-recognition-output code').html(highlightedText);

    //     console.log("Preprocessed Speech Data:", newText);

    //     silenceTimeout = setTimeout(stopRecognition, 5000);
    // };


    // function stopRecognition() {
    //     recognition.stop();
    //     $('.speech-recognition-btn').removeClass('speech-recognition-active text-primary')
    //         .addClass('speech-recognition-inactive text-muted');
    //     $('.speech-recognition-status-text').text('NOT LISTENING');

    //     console.log("Final Compiled Preprocessed Data:", finalTranscript);

    //     // $.ajax({
    //     //     url: "YOUR_AWS_API_GATEWAY_ENDPOINT",
    //     //     type: "POST",
    //     //     contentType: "application/json",
    //     //     data: JSON.stringify({ text: finalTranscript }),
    //     //     success: function (response) {
    //     //         console.log("AWS Transcribe Response:", response);
    //     //     },
    //     //     error: function (xhr, status, error) {
    //     //         console.error("AWS Transcribe Error:", error);
    //     //     }
    //     // });
    // }

    // recognition.onerror = function () {
    //     $('.speech-recognition-output code').html('<span class="text-danger">Speech recognition error.</span>');
    // };

    // $('.back-to-main-btn').on('click', function() {
    //     $(this).prop('disabled', true);
    //     $(this).html('<i class="spinner-border spinner-border-sm"></i>');
    // });
    $('.rooms-join-btn').on('click', function() {
        $('.rooms-menu-container').removeClass('d-flex').hide();
        $('.rooms-join-container').show();
    });

    $('.speech-recognition-output-language').on('change', function() {
        convertPseudocode();
    });
});