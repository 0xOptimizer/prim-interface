$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var current_room_code;
var current_room_uuid;
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
        // if (isSpeechProcessing) {
        //     return;
        // }

        // isSpeechProcessing = true;

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
        const roomDescription = $('#room-description').val();
        const user_uuid = window.AppData.user_uuid;

        if (roomName === '') {
            return;
        }

        $(this).prop('disabled', true);
        $(this).html('<i class="spinner-border spinner-border-sm"></i>');

        $.ajax({
            url: 'https://prim-api.o513.dev/api/v1/rooms/create',
            type: 'POST',
            data: { name: roomName, description: roomDescription, user_uuid: user_uuid },
            success: function(response) {
                console.log("Room created successfully:", response);
                current_room_code = response.code;
                current_room_uuid = response.uuid;

                $('#view-room-code').val(current_room_code);
                $('#offcanvas-rooms').offcanvas('show');

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
            url: 'https://prim-api.o513.dev/api/v1/rooms/show/all',
            type: 'POST',
            data: { user_uuid: user_uuid },
            success: function(response) {
                console.log("Rooms fetched successfully:", response);
                const $roomsList = $('.rooms-list');
                $roomsList.empty();

                if (response.length === 0) {
                    // $roomsList.append('<div class="alert alert-info">No rooms available.</div>');
                    return;
                }

                response.forEach(room => {
                    const lastActive = new Date(room.last_active);
                    const now = new Date();
                    const hoursDiff = (now - lastActive) / 36e5;
                    const isActive = hoursDiff < 24;
                
                    const roomItem = `
                        <div class="room-card card ${isActive ? 'card-success-bg' : ''}" data-room_code="${room.code}" data-room_uuid="${room.uuid}" data-room_name="${room.name}">
                            <div class="card-body">
                                <b class="card-title">${room.name}</b>
                                <p class="card-text text-muted" style="font-size: 10px;">
                                    ${isActive ? `<span class="text-success"><i class="spinner-grow" style="width: 8px; height: 8px;"></i> <b>ACTIVE</b></span> • 2 hrs ago` : '2 hrs ago'}
                                </p>
                            </div>
                            ${isActive ? `
                            <div class="card-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="card-value">
                                <b class="text-success">${room.user_count}</b>
                            </div>` : ''}
                        </div>
                    `;
                
                    $roomsList.append(roomItem);
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching rooms:", error);
            }
        });
    }

    function getFiles() {
        const user_uuid = window.AppData.user_uuid;

        $.ajax({
            url: `https://prim-api.o513.dev/api/v1/files/user/${user_uuid}`,
            type: 'GET',
            success: function(response) {
                console.log("Files fetched successfully:", response);
                const $filesList = $('.files-list');
                $filesList.empty();

                if (response.length === 0) {
                    // $filesList.append('<div class="alert alert-info">No files available.</div>');
                    return;
                }

                response.forEach(file => {
                    const fileItem = `
                        <div class="file-card card card-warning-bg" data-file_id="${file.id}" data-file_name="${file.filename}">
                            <div class="card-body">
                                <b class="card-title">${file.filename}</b>
                                <p class="card-text text-muted" style="font-size: 10px;">
                                    <span><b>#453J</b></span> • 1.4 MB
                                </p>
                            </div>
                        </div>`
                    $filesList.append(fileItem);
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching files:", error);
            }
        });
    }

    getFiles();

    let is_room_open = false;
    $('.rooms-list').on('click', '.room-card', function() {
        const room_code = $(this).data('room_code');
        const room_name = $(this).data('room_name');
        current_room_code = room_code;
        current_room_uuid = $(this).data('room_uuid');

        is_room_open = true;
        loadChats();
        
        $('#view-room-code').text(room_code);
        $('#view-room-name').text(room_name);

        $('#offcanvas-rooms').offcanvas('show');
    });
    
    $('#offcanvas-rooms').on('hide.bs.offcanvas', function () {
        if (is_room_open) {
            is_room_open = false;
        }
    });

    function setupRoomCodeInputs() {
        $('.room-code-input:not([disabled])').on('input', function() {
            let val = $(this).val().toUpperCase().replace(/[^A-Z0-9]/g, '');
            if (val.length > 1) val = val.charAt(0);
            $(this).val(val);

            if (val && $(this).next('.room-code-input').length) {
                $(this).next('.room-code-input').focus();
            }
        }).on('keydown', function(e) {
            if (e.key === 'Backspace' && !$(this).val() && $(this).prev('.room-code-input').length) {
                $(this).prev('.room-code-input').focus();
            }
        });
    }
    setupRoomCodeInputs();

    $('.rooms-join-btn').on('click', function(e) {
        e.preventDefault();
    
        let code = '#';
        $('.room-code-input:not([disabled])').each(function() {
            code += $(this).val().trim().toUpperCase();
        });
    
        if (code.length !== 5) return;
    
        const user_uuid = window.AppData.user_uuid;
        const $btn = $(this);
    
        $btn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm"></i>');
    
        $.ajax({
            url: 'https://prim-api.o513.dev/api/v1/rooms/join',
            type: 'POST',
            data: {
                code: code,
                user_uuid: user_uuid
            },
            success: function(response) {
                current_room_code = code;
                current_room_uuid = response.uuid;

                is_room_open = true;
                loadChats();
                $('#view-room-name').text(response.name);
                $('#view-room-code').text(current_room_code);
                
                $('#offcanvas-rooms').offcanvas('show');

                $btn.prop('disabled', false).html('Join');
            },
            error: function(xhr, status, error) {
                console.error("Error joining room:", error);
                $btn.prop('disabled', false).html('Error: Try Again');
            }
        });
    });

    getRooms();

    // Calculate relative time
    function getRelativeTime(date) {
        const now = new Date();
        const diff = Math.floor((now - date) / 1000);
        if (diff < 60) return 'Just now';
        if (diff < 3600) {
            const mins = Math.floor(diff / 60);
            return `${mins} min${mins !== 1 ? 's' : ''} ago`;
        }
        if (diff < 86400) {
            const hrs = Math.floor(diff / 3600);
            return `${hrs} hr${hrs !== 1 ? 's' : ''} ago`;
        }
        return `${Math.floor(diff / 86400)} day${Math.floor(diff / 86400) > 1 ? 's' : ''} ago`;
    }

    function loadChats(skipLoading=false) {
        if (!current_room_uuid) return;
        const chatBox = $('.chat-messages');
        if (!skipLoading) {
            chatBox.html('<div class="text-center my-3">Loading messages...</div>');
        }

        $.ajax({
            url: 'https://prim-api.o513.dev/api/v1/rooms/chat/show',
            type: 'POST',
            data: { room_uuid: current_room_uuid },
            success: function (data) {
                chatBox.empty();
                if (data.length === 0) {
                    chatBox.html('<div class="text-center my-3 text-muted">No messages yet.</div>');
                    return;
                }

                let previousMsg = null;

                data.forEach((msg, index) => {
                    const isCurrentUser = msg.user.uuid === window.AppData.user_uuid;
                    const user = msg.user;
                    const fullName = `${user.first_name} ${user.last_name}`;
                    const userImg = user.user_image || 'https://prim.o513.dev/public/images/user/default.png';
                    const createdAt = new Date(msg.created_at);
                    const relativeTime = getRelativeTime(createdAt);

                    let hideMeta = false;
                    if (previousMsg) {
                        const prevTime = new Date(previousMsg.created_at);
                        const timeDiff = (createdAt - prevTime) / 1000;
                        if (previousMsg.user.uuid === msg.user.uuid && timeDiff <= 3600) {
                            hideMeta = true;
                        }
                    }

                    const message = `
                        <div class="d-flex mb-2 ${isCurrentUser ? 'justify-content-end' : ''}">
                            ${!isCurrentUser && !hideMeta ? `<img src="${userImg}" class="rounded-circle me-2" width="40" height="40">` : `<div style="width: 40px; margin-right: 10px;"></div>`}
                            <div style="max-width: 75%;">
                                ${!hideMeta ? `
                                    <div style="font-size: 12px; text-align: ${isCurrentUser ? 'right' : 'left'};">
                                        <b class="text-gradient-primary">${user.first_name}</b>
                                        <span style="opacity: 0.75; font-size: 11px;">&nbsp;·&nbsp;${relativeTime}</span>
                                    </div>` : ''
                                }
                                <div class="mt-1 mb-1 p-2" style="font-size: 18px; background-color: ${isCurrentUser ? '#0d6efd' : '#bfbebe4a'}; border-radius: ${isCurrentUser ? '8px 0px 8px 8px' : '0px 8px 8px 8px'};${isCurrentUser ? ' color: #fff;' : ''}">
                                    ${msg.chat}
                                </div>
                            </div>
                        </div>
                    `;

                    chatBox.append(message);
                    previousMsg = msg;
                });

                chatBox.scrollTop(chatBox[0].scrollHeight);
            },
            error: function () {
                chatBox.html('<div class="text-center my-3 text-danger">Failed to load messages.</div>');
            }
        });
    }

    $('#chat-form').submit(function (e) {
        e.preventDefault();
        const input = $('#chat-message');
        const message = input.val().trim();
        if (!message || !current_room_uuid) return;

        const form = $(this);
        form.find('button').prop('disabled', true);
        form.find('button').html('<i class="spinner-border spinner-border-sm"></i>');

        $.ajax({
            url: 'https://prim-api.o513.dev/api/v1/rooms/chat/create',
            type: 'POST',
            headers: {
                'Accept': 'application/json'
            },
            data: {
                room_uuid: current_room_uuid,
                user_uuid: window.AppData.user_uuid,
                chat: message,
                chat_type: 'conversation'
            },
            success: function () {
                input.val('');
                loadChats();
            },
            complete: function () {
                form.find('button').prop('disabled', false);
                form.find('button').html('<i class="fa-solid fa-paper-plane"></i>');
            }
        });
    });

    setInterval(function() {
        if (!is_room_open) return;
        if (!current_room_uuid) return;
        loadChats(true);
    }, 5000);
});