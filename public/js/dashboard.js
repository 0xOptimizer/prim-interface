$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var current_room_code;
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
    let isSpeechProcessing = false;
    
    $('.speech-recognition-btn').on('click', async function () {
        if (isSpeechProcessing) {
            return;
        }

        isSpeechProcessing = true;

        const $this = $(this);
        const $status = $('.speech-recognition-status-text');
        const $output = $('.speech-recognition-output code');
    
        if ($this.hasClass('speech-recognition-inactive')) {
            audioChunks = [];
            $this.removeClass('speech-recognition-inactive text-muted')
                .addClass('speech-recognition-active text-primary');
            $('.speech-activated').show();
            $status.html('<span class="pulse-animation">LISTENING</span>');
            $output.html('<span class="text-muted">Listening...</span>');
    
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);
                mediaRecorder.start();
    
                mediaRecorder.ondataavailable = function (event) {
                    if (event.data.size > 0) {
                        audioChunks.push(event.data);
                        pulseEffect(); 
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
    
    function pulseEffect() {
        $('.speech-recognition-status-text').addClass('pulse-effect');
        setTimeout(() => {
            $('.speech-recognition-status-text').removeClass('pulse-effect');
        }, 500);
    }

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
        $('.speech-activated').hide();
        $('.speech-recognition-icon-mic').hide();
        $('.speech-recognition-icon-processing').show();
        $('.speech-recognition-status-text').text('');
    }

    function processAudio() {
        const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
        const reader = new FileReader();
        
        $('.speech-recognition-output code').html('<div class="spinner-border text-primary" role="status"></div>');
    
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
                        if (response.job_name) {
                            checkTranscription(response.job_name);
                        } else {
                            console.error("No job name found.");
                        }
                    }, 1000);
                },             
                error: function (xhr, status, error) {
                    isSpeechProcessing = false;
                    $('.speech-recognition-icon-mic').show();
                    $('.speech-recognition-icon-processing').hide();
                    $('.speech-recognition-status-text').text('TAP TO ACTIVATE');
                    $('.speech-recognition-btn').removeClass('speech-recognition-active text-primary')
                        .addClass('speech-recognition-inactive text-muted');
                    $('.speech-activated').hide(); 


                    console.error("AWS Error:", error);
                    $('.speech-recognition-output code').html('<span class="text-danger">Error processing audio.</span>');
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
                isSpeechProcessing = false;
            }
        });
    }
    
    // function convertPseudocode() {
    //     const input = $('.speech-recognition-output-speech').val();
    //     const language = $('.speech-recognition-output-language').val();
    
    //     console.log("=== Converting Pseudocode to " + language.toUpperCase() + " ===");
    //     console.log("Original Input:", input);
    
    //     let cleanedSpeech = input.toLowerCase().replace(/[^a-z0-9" ]+/g, "").trim();
    //     console.log("Cleaned Speech:", cleanedSpeech);
    
    //     let words = cleanedSpeech.split(" ");
    //     let outputCode = "";
    //     let usedIndices = new Set();
    //     let indentLevel = 0;
    //     const indentSize = 20;
    
    //     for (let i = 0; i < words.length; i++) {
    //         if (usedIndices.has(i)) continue; 
    
    //         Object.keys(syntaxMapping).forEach(key => {
    //             syntaxMapping[key].patterns.forEach(pattern => {
    //                 let processedPattern = pattern.replace(/\(\?:([a-z ]+)\)\?/gi, "$1").trim();
    //                 let patternWords = processedPattern.toLowerCase().split(" ");
    //                 let params = [];
    //                 let extractedWords = [];
    //                 let inputIndex = i;
    //                 let match = true;
    //                 let localUsedIndices = new Set();
    
    //                 for (let j = 0; j < patternWords.length; j++) {
    //                     let word = patternWords[j];
    
    //                     if (word.match(/\{\d+\}/)) {
    //                         while (inputIndex < words.length && 
    //                               (usedIndices.has(inputIndex) || localUsedIndices.has(inputIndex))) {
    //                             inputIndex++;
    //                         }
    //                         if (inputIndex < words.length) {
    //                             params.push(words[inputIndex]);
    //                             extractedWords.push(words[inputIndex]);
    //                             localUsedIndices.add(inputIndex);
    //                             inputIndex++;
    //                         } else {
    //                             match = false;
    //                             break;
    //                         }
    //                     } else {
    //                         while (inputIndex < words.length && 
    //                               (usedIndices.has(inputIndex) || 
    //                                localUsedIndices.has(inputIndex) || 
    //                                words[inputIndex] !== word)) {
    //                             inputIndex++;
    //                         }
    //                         if (inputIndex >= words.length) {
    //                             match = false;
    //                             break;
    //                         }
    //                         extractedWords.push(words[inputIndex]);
    //                         localUsedIndices.add(inputIndex);
    //                         inputIndex++;
    //                     }
    //                 }
    
    //                 if (match && syntaxMapping[key].languages[language]) {
    //                     localUsedIndices.forEach(index => usedIndices.add(index));
    //                     let code = syntaxMapping[key].languages[language];
    //                     params.forEach((param, index) => {
    //                         code = code.replace(`{${index + 1}}`, param);
    //                     });
    
    //                     let span = `<div class="prim-code-output" style="display: block; margin-left: ${indentLevel * indentSize}px;"><span>${code}</span></div>`;
    //                     outputCode += span;
    
    //                     if (syntaxMapping[key].creates_new_line && !syntaxMapping[key].requires_closing_brace) {
    //                         outputCode += '<br>';
    //                     }
    //                 }
    //             });
    //         });
    //     }
    
    //     console.log("\nFinal Generated Code:\n" + outputCode);
    //     $('.speech-recognition-output-code').html(outputCode);
    //     return true;
    // }

    function convertPseudocode() {
        const speech = $('.speech-recognition-output-speech').val();
        const language = $('.speech-recognition-output-language').val();
    
        const baseUrl = `${window.location.protocol}//${window.location.host}`;

        $('.speech-recognition-output-speech').attr('disabled', true);
        $('.speech-recognition-output-language').attr('disabled', true);
        $('.speech-recognition-output-language-btn').attr('disabled', true);
        $('.speech-recognition-output-code').html('<div class="spinner-border text-primary" role="status"></div>');

        $.ajax({
            url: `https://prim-api.o513.dev/api/v1/ai/convert/request`,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ speech: speech, language: language, room_code: current_room_code, user_uuid: window.AppData.user_uuid }),
            success: function (response) {
                if (typeof response === "string") {
                    response = JSON.parse(response);
                }
            
                if (!response.codes || response.codes.length !== 3) {
                    $('.speech-recognition-output-code').html('<div class="alert alert-danger">Invalid response received.</div>');
                    return;
                }
            
                const codes = response.codes;
                const downloadUrls = response.download_urls;
                const testCases = response.test_cases;
                const language = response.language;
            
                let tabsHtml = `<ul class="nav nav-tabs" id="codeTabs" role="tablist">`;
                let contentHtml = `<div class="tab-content" id="codeTabContent">`;
                let cardsHtml = ``;
            
                codes.forEach((code, index) => {
                    const tabId = `code${index}`;
                    const activeClass = index === 0 ? "active" : "";
            
                    tabsHtml += `
                        <li class="nav-item" role="presentation">
                            <button class="nav-link ${activeClass}" id="${tabId}-tab" data-bs-toggle="tab" data-bs-target="#${tabId}" type="button" role="tab"><img src="{{ asset('images/python_logo.svg') }}" class="speech-language-icon me-1" width="16" height="16"> S${index + 1}</button>
                        </li>
                    `;
            
                    contentHtml += `
                        <div class="tab-pane fade show ${activeClass}" id="${tabId}" role="tabpanel">
                            <pre style="margin-top: 20px; margin-bottom: 10px;"><code>${$('<div>').text(code).html()}</code></pre>
                            <a href="${downloadUrls[index]}" download class="text-decoration-none">
                                <div class="speech-language-select-option card card-interactable my-2">
                                    <div class="card-body d-flex align-items-center">
                                        <img src="/images/download.svg" width="32" height="32" class="me-4">
                                        <div>
                                            <span style="font-size: 16px;">Download Solution ${index + 1}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    `;
                });
            
                const downloadAllHtml = `
                    <a href="${response.download_all_url}" download class="text-decoration-none">
                        <div class="speech-language-select-option card card-interactable my-2">
                            <div class="card-body d-flex align-items-center">
                                <img src="images/download_all.svg" width="32" height="32" class="me-4">
                                <div>
                                    <span style="font-size: 16px;">Download All Solutions</span>
                                </div>
                            </div>
                        </div>
                    </a>
                `;
            
                tabsHtml += `</ul>`;
                contentHtml += `</div>`;
            
                $('.speech-recognition-output-code').html(
                    tabsHtml + contentHtml +
                    `<hr class="my-3">` +
                    `<h5 class="mt-2">Test Cases</h5><pre><code>${$('<div>').text(testCases).html()}</code></pre>` +
                    `<hr class="my-3">` +
                    `<div class="mt-4">${cardsHtml}${downloadAllHtml}</div>`
                );

                $('#offcanvas-speech-output').offcanvas('show');

                $('.speech-recognition-output-speech').attr('disabled', false);
                $('.speech-recognition-output-language').attr('disabled', false);
                $('.speech-recognition-output-language-btn').attr('disabled', false);

                $('.speech-recognition-icon-mic').show();
                $('.speech-recognition-icon-processing').hide();
                $('.speech-recognition-status-text').text('TAP TO ACTIVATE');
                $('.speech-activated').hide();
                $('.speech-recognition-btn').removeClass('speech-recognition-active text-primary')
                    .addClass('speech-recognition-inactive text-muted');
                $('.speech-recognition-btn').prop('disabled', false);

                isSpeechProcessing = false;
            },            
            error: function (xhr, status, error) {
                console.error("Error converting pseudocode:", error);
                $('.speech-recognition-output-code').html('<div class="alert alert-danger">Error processing request.</div>');
                
                $('.speech-recognition-output-speech').attr('disabled', false);
                $('.speech-recognition-output-language').attr('disabled', false);
                $('.speech-recognition-output-language-btn').attr('disabled', false);

                $('.speech-recognition-icon-mic').show();
                $('.speech-recognition-icon-processing').hide();
                $('.speech-recognition-status-text').text('TAP TO ACTIVATE');
                $('.speech-activated').hide();
                $('.speech-recognition-btn').removeClass('speech-recognition-active text-primary')
                    .addClass('speech-recognition-inactive text-muted');
                $('.speech-recognition-btn').prop('disabled', false);

                isSpeechProcessing = false;
            },
            always: function() {
                
            }
        });
    }

    let activationPhrase = localStorage.getItem('activationPhrase') || 'okay';

    function loadSettings() {
        $('#activationPhrase').val(activationPhrase);
    }

    function saveSettings() {
        activationPhrase = $('#activationPhrase').val().trim().toLowerCase();
        localStorage.setItem('activationPhrase', activationPhrase);
    }

    $('.save-settings-btn').on('click', function () {
        const $this = $(this);
        $this.prop('disabled', true);
        const originalText = $this.text();
        $this.html('<i class="spinner-border spinner-border-sm"></i>');

        setTimeout(() => {
            $this.prop('disabled', false);
            $this.text(originalText);
            saveSettings();
        }, 750);
    });

    loadSettings();

    const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    recognition.continuous = true;
    recognition.interimResults = true;
    recognition.lang = 'en-US';

    recognition.start();

    recognition.onresult = function (event) {
        let transcript = "";
        for (let i = event.resultIndex; i < event.results.length; i++) {
            if (event.results[i].isFinal) {
                transcript = event.results[i][0].transcript.trim().toLowerCase();
            }
        }

        console.log("transcript:", transcript);

        if (transcript.includes(activationPhrase)) {
            $('.speech-recognition-btn').trigger('click');
        }
    };

    recognition.onend = function () {
        setTimeout(() => recognition.start(), 500);
    };

    // $('.back-to-main-btn').on('click', function() {
    //     $(this).prop('disabled', true);
    //     $(this).html('<i class="spinner-border spinner-border-sm"></i>');
    // });
    $('.rooms-join-btn').on('click', function() {
        $('.rooms-menu-container').removeClass('d-flex').hide();
        $('.rooms-join-container').show();
    });

    $('.speech-recognition-output-speech, .speech-recognition-output-language').on('change', function() {
        $('.speech-recognition-btn').removeClass('speech-recognition-active text-primary')
            .addClass('speech-recognition-inactive text-muted');
        $('.speech-activated').hide();
        $('.speech-recognition-icon-mic').hide();
        $('.speech-recognition-icon-processing').show();
        $('.speech-recognition-status-text').text('');

        convertPseudocode();
    });

    $('.speech-language-select-option').on('click', function() {
        const selectedLanguage = $(this).data('option');
        $('.speech-recognition-output-language').val(selectedLanguage);
        $('#offcanvas-speech-language').offcanvas('hide');
        if (selectedLanguage === 'python') {
            $('.speech-language-icon').attr('src', '/images/python_logo.svg');
            $('.speech-language-text').text('Python');
        } else if (selectedLanguage === 'javascript') {
            $('.speech-language-icon').attr('src', '/images/javascript_logo.svg');
            $('.speech-language-text').text('JavaScript');
        }
        convertPseudocode();
    });

    $('#offcanvas-speech-output').on('hidden.bs.offcanvas', function () {
        // isSpeechProcessing = false;

        // $('.speech-recognition-icon-mic').show();
        // $('.speech-recognition-icon-processing').hide();
        // $('.speech-recognition-status-text').text('TAP TO ACTIVATE');
        // $('.speech-activated').hide();
        // $('.speech-recognition-btn').removeClass('speech-recognition-active text-primary')
        //     .addClass('speech-recognition-inactive text-muted');
        // $('.speech-recognition-btn').prop('disabled', false);
        // $('.speech-recognition-output-speech').attr('disabled', false);
        // $('.speech-recognition-output-language').attr('disabled', false);
        // $('.speech-recognition-output-language-btn').attr('disabled', false);
        
        // $('.speech-recognition-output-speech').val('');
    });

    $('.create-room-btn').on('click', function() {
        const roomName = $('#room-name').val();

        if (roomName === '') {
            return;
        }

        $(this).prop('disabled', true);
        $(this).html('<i class="spinner-border spinner-border-sm"></i>');

        $.ajax({
            url: 'https://prim-api.o513.dev/api/v1/rooms/',
            type: 'POST',
            data: { name: roomName },
            contentType: "application/json",
            success: function(response) {
                console.log("Room created successfully:", response);
                current_room_code = response.code;

                // redirect here

                $('#room-name').val('');
                $('.create-room-btn').prop('disabled', false);
                $('.create-room-btn').html('Create');
            },
            error: function(xhr, status, error) {
                console.error("Error creating room:", error);
                $('.create-room-btn').prop('disabled', false);
                $('.create-room-btn').html('Error: Try Again');
            }
        });
    });

    function getRooms() {
        const user_uuid = window.AppData.user_uuid;

        $.ajax({
            url: 'https://prim-api.o513.dev/api/v1/rooms/',
            type: 'GET',
            data: { user_uuid: user_uuid },
            contentType: "application/json",
            success: function(response) {
                console.log("Rooms fetched successfully:", response);
                const rooms = response.rooms;
                const $roomsList = $('.rooms-list');
                $roomsList.empty();

                rooms.forEach(room => {
                    const roomItem = `<div class="room-item" data-code="${room.code}">${room.name}</div>`;
                    $roomsList.append(roomItem);
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching rooms:", error);
            }
        });
    }

    getRooms();
});