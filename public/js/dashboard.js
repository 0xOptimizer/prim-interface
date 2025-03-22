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
                contentType: "application/json",
                data: JSON.stringify({ audio: base64Audio }),
                success: function (response) {
                    console.log("AWS Response:", response);
                    if (response.job_name) {
                        checkTranscription(response.job_name);
                    }
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
            data: JSON.stringify({ job_name: jobName }),
            success: function (response) {
                if (response.transcript_url) {
                    console.log("Transcription Completed:", response.transcript_url);
                    fetchTranscript(response.transcript_url);
                } else {
                    console.log("Transcription in progress...");
                    setTimeout(() => checkTranscription(jobName), 5000);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching transcription:", error);
            }
        });
    }

    function fetchTranscript(transcriptUrl) {
        $.ajax({
            url: transcriptUrl,
            type: "GET",
            success: function (response) {
                const transcriptText = response.results.transcripts[0].transcript;
                $('.speech-recognition-output code').text(transcriptText);
            },
            error: function (xhr, status, error) {
                console.error("Error fetching transcript text:", error);
            }
        });
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
});