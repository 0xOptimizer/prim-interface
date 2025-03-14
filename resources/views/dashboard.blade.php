<!DOCTYPE html>

<html>
<head>
    @include('components.header')
</head>
<body>
    <div class="phone-container">
        <div class="group-container animate__animated animate__fadeInDown animate__durationExcluded" data-group="main">
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center">
                    <button class="group-navigate-btn btn btn-primary btn-sm" type="button" data-group="settings" style="width: 50px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 0 0-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 0 0-2.282.819l-.922 1.597a1.875 1.875 0 0 0 .432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 0 0 0 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 0 0-.432 2.385l.922 1.597a1.875 1.875 0 0 0 2.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 0 0 2.28-.819l.923-1.597a1.875 1.875 0 0 0-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 0 0 0-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 0 0-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 0 0-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 0 0-1.85-1.567h-1.843ZM12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <a href="{{ route('dashboard') }}" class="back-to-main-btn btn btn-primary btn-sm" style="width: 50px; display: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
                            <path d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
                        </svg>
                    </a>
                    <h5 style="margin-top: 9px; margin-right: auto; margin-left: 12px;"><span>Hi, <span class="text-gradient-primary">{{ strlen($data['user']['first_name']) > 20 ? substr($data['user']['first_name'], 0, 17) . '...' : $data['user']['first_name'] }}</span></span></h5>
                    <img src="{{ $data['user']['user_image'] ?? asset('images/user/default.png') }}" alt="Profile" class="unavailable-btn rounded-circle" style="width: 40px; height: 40px;">
                </div>
            </div>
            <div class="menu-buttons-group mt-5">
                <button class="expand-btn btn btn-primary w-100 pt-4 mb-2" data-expansion="speech_to_code">
                    <svg style="width: 48px; height: 48px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path d="M8.25 4.5a3.75 3.75 0 1 1 7.5 0v8.25a3.75 3.75 0 1 1-7.5 0V4.5Z" />
                        <path d="M6 10.5a.75.75 0 0 1 .75.75v1.5a5.25 5.25 0 1 0 10.5 0v-1.5a.75.75 0 0 1 1.5 0v1.5a6.751 6.751 0 0 1-6 6.709v2.291h3a.75.75 0 0 1 0 1.5h-7.5a.75.75 0 0 1 0-1.5h3v-2.291a6.751 6.751 0 0 1-6-6.709v-1.5A.75.75 0 0 1 6 10.5Z" />
                    </svg>
                    <p class="mt-3">
                        Speech to Code
                    </p>
                </button>
                <div class="d-flex justify-content-around gap-2 mb-2">
                    <button class="expand-btn btn btn-success w-100 pt-2" data-expansion="rooms">
                        <svg style="width: 24px; height: 24px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" />
                            <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                        </svg>
                        <p class="mt-2">
                            Rooms
                        </p>
                    </button>
                    <button class="expand-btn btn btn-danger w-100 pt-2" data-expansion="activities">
                        <svg style="width: 24px; height: 24px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M9 4.5a.75.75 0 0 1 .721.544l.813 2.846a3.75 3.75 0 0 0 2.576 2.576l2.846.813a.75.75 0 0 1 0 1.442l-2.846.813a3.75 3.75 0 0 0-2.576 2.576l-.813 2.846a.75.75 0 0 1-1.442 0l-.813-2.846a3.75 3.75 0 0 0-2.576-2.576l-2.846-.813a.75.75 0 0 1 0-1.442l2.846-.813A3.75 3.75 0 0 0 7.466 7.89l.813-2.846A.75.75 0 0 1 9 4.5ZM18 1.5a.75.75 0 0 1 .728.568l.258 1.036c.236.94.97 1.674 1.91 1.91l1.036.258a.75.75 0 0 1 0 1.456l-1.036.258c-.94.236-1.674.97-1.91 1.91l-.258 1.036a.75.75 0 0 1-1.456 0l-.258-1.036a2.625 2.625 0 0 0-1.91-1.91l-1.036-.258a.75.75 0 0 1 0-1.456l1.036-.258a2.625 2.625 0 0 0 1.91-1.91l.258-1.036A.75.75 0 0 1 18 1.5ZM16.5 15a.75.75 0 0 1 .712.513l.394 1.183c.15.447.5.799.948.948l1.183.395a.75.75 0 0 1 0 1.422l-1.183.395c-.447.15-.799.5-.948.948l-.395 1.183a.75.75 0 0 1-1.422 0l-.395-1.183a1.5 1.5 0 0 0-.948-.948l-1.183-.395a.75.75 0 0 1 0-1.422l1.183-.395c.447-.15.799-.5.948-.948l.395-1.183A.75.75 0 0 1 16.5 15Z" clip-rule="evenodd" />
                        </svg>
                        <p class="mt-2">
                            Activities
                        </p>
                    </button>
                </div>
                <div class="expansion-group d-flex justify-content-around gap-2 mb-2" data-expansion="menu">
                    <div class="w-100">
                        <div class="card card-success-bg">
                            <div class="card-body">
                                <b class="card-title">SHS-1</b>
                                <p class="card-text text-muted" style="font-size: 10px;"><span class="text-success"><i class="spinner-grow" style="width: 8px; height: 8px;"></i> <b>ACTIVE</b></span> • 2 hours ago</p>
                            </div>
                            <div class="card-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="card-value">
                                <b class="text-success">5</b>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <b class="card-title">SHS-2</b>
                                <p class="card-text text-muted" style="font-size: 10px;">5 hours ago</p>
                            </div>
                        </div>
                    </div>
                    <div class="w-100">
                        <ul class="list-unstyled">
                            <li class="unavailable-btn d-flex align-items-center mb-2" style="font-size: 14px;">
                                <i class="bi bi-file-earmark-text-fill text-primary me-2" style="margin-top: -1px;"></i>
                                <span>Calculator App</span>
                                <small class="text-muted ms-auto">2 hours ago</small>
                            </li>
                            <li class="unavailable-btn d-flex align-items-center" style="font-size: 14px;">
                                <i class="bi bi-chat-left-dots-fill text-purple me-2" style="margin-top: -1px;"></i>
                                <span>Student Discussion</span>
                                <small class="text-muted ms-auto">5 hours ago</small>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="expansion-group justify-content-around gap-2 mb-2" data-expansion="speech_to_code" style="display: none;">
                    <div class="speech-recognition-container text-center">
                        <div>
                            <h6 class="speech-recognition-title-text">Hey, start speaking!</h6>
                        </div>
                        <div class="speech-recognition-btn speech-recognition-inactive text-muted mt-5">
                            <i class="bi bi-mic-fill" style="font-size: 72px;"></i>
                        </div>
                        <div class="speech-recognition-status-text text-muted mt-5">
                             NOT LISTENING
                        </div>
                        <hr>
                        <div class="speech-recognition-output mt-5">
                             <code>
                                    <span class="text-muted">Your code will appear here...</span>
                             </code>
                        </div>
                    </div>
                </div>
                <div class="expansion-group mb-2" data-expansion="rooms" style="display: none;">
                    <div class="rooms-menu-container d-flex justify-content-around gap-2">
                        <div class="w-100">
                            <div class="rooms-create-btn rooms-card-btn card card-success-bg">
                                <div class="card-body">
                                    <b class="card-title">Create a Room</b>
                                </div>
                                <div class="card-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                        <path d="M5.25 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM2.25 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM18.75 7.5a.75.75 0 0 0-1.5 0v2.25H15a.75.75 0 0 0 0 1.5h2.25v2.25a.75.75 0 0 0 1.5 0v-2.25H21a.75.75 0 0 0 0-1.5h-2.25V7.5Z" />
                                    </svg>
                                </div>
                                <div class="card-value">
                                </div>
                            </div>
                        </div>
                        <div class="w-100">
                            <div class="rooms-join-btn rooms-card-btn card card-purple-bg">
                                <div class="card-body">
                                    <b class="card-title">Join a Room</b>
                                </div>
                                <div class="card-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                        <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="card-value">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rooms-join-container" style="display: none;">
                        <div class="d-flex flex-column align-items-center gap-2 mt-3">
                            <form>
                                <div class="form-group d-flex justify-content-center">
                                    <input type="text" class="room-code-input form-control" value="#" disabled style="width: 15%;">
                                    <input type="text" class="room-code-input form-control" placeholder="0" min="0" max="9" style="width: 15%;">
                                    <input type="text" class="room-code-input form-control" placeholder="0" min="0" max="9" style="width: 15%;">
                                    <input type="text" class="room-code-input form-control" placeholder="0" min="0" max="9" style="width: 15%;">
                                    <input type="text" class="room-code-input form-control" placeholder="0" min="0" max="9" style="width: 15%;">
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100 mt-2">Join</button>
                            </form>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="text-center mt-3 text-muted">
                        <i class="bi bi-info-circle"></i> Previous rooms will appear here.
                    </div>
                </div>
            </div>
        </div>
        <div class="group-container" data-group="settings" style="display: none;">
            <div class="mb-5">
                <button class="group-navigate-btn btn btn-outline-primary btn-sm" type="button" data-group="main" style="width: 50px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </button>
                <h4 class="text-center mt-2"><span class="text-gradient-primary">Settings & Configurations</span></h4>
            </div>
            <div>
                <!-- "activation phrase" input -->
                <div class="form-group">
                    <label for="activationPhrase">Activation Phrase</label>
                    <input type="text" class="form-control p-3" id="activationPhrase" style="font-size: 20px; height: 48px;">
                </div>

                <!-- switches -->
                <hr class="my-4">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Dark Mode</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Notifications</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckNotifications">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Auto Updates</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckAutoUpdates">
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary btn-lg w-100">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function() {
    $('body').on('click', '.expand-btn', function() {
        const $this = $(this);
        const others = $('.expand-btn');

        const expansion = $(this).data('expansion');
        const $current = $('.expansion-group:visible');
        const $next = $(`.expansion-group[data-expansion="${expansion}"]`);
        
        $current.addClass('animate__animated animate__fadeOutLeft').css('animation-duration', '0.55s');

        $current.one('animationend', function() {
            $current.hide().removeClass('d-flex animate__animated animate__fadeOutLeft');
            if (expansion == 'rooms') {
                $next.show().addClass('animate__animated animate__slideInDown').css('animation-duration', '0.55s');
            } else {
                $next.show().addClass('d-flex animate__animated animate__slideInDown').css('animation-duration', '0.55s');
            }
            $next.one('animationend', function() {
                $next.removeClass('animate__animated animate__slideInDown');
            });
        });

        others.addClass('animate__animated animate__fadeOutLeft').css('animation-duration', '0.55s').one('animationend', function() {
            $(this).hide().removeClass('animate__animated animate__fadeOutLeft');
            
            if (others.filter(':visible').length === 0 && $this.data('expansion') === 'activities') {
                $this.animate({ width: '100%' }, { duration: 1200, easing: 'cubicBezier(.25,.1,.25,1)' });
            }
        });

        $('.back-to-main-btn').show();
        $('.group-navigate-btn[data-group="settings"]').hide();

        setTimeout(function() {
            $($this).css('transition', '0.55s ease');
            $($this).css('background-color', '#212529bf');
            $($this).css('opacity', '0.5');

            $($this).find('svg').hide();
            $($this).find('p').removeClass('mt-3');
        }, 550);
    });

    // let mediaRecorder;
    // let audioChunks = [];
    // let silenceTimeout;
    
    // $('.speech-recognition-btn').on('click', async function () {
    //     const $this = $(this);
    //     const $status = $('.speech-recognition-status-text');
    //     const $output = $('.speech-recognition-output code');

    //     if ($this.hasClass('speech-recognition-inactive')) {
    //         audioChunks = []; // Clear previous recordings
    //         $this.removeClass('speech-recognition-inactive text-muted')
    //             .addClass('speech-recognition-active text-primary');
    //         $status.text('LISTENING');
    //         $output.html('<span class="text-muted">Listening...</span>');

    //         try {
    //             const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
    //             mediaRecorder = new MediaRecorder(stream);
    //             mediaRecorder.start();

    //             mediaRecorder.ondataavailable = function (event) {
    //                 if (event.data.size > 0) {
    //                     audioChunks.push(event.data);
    //                 }
    //                 resetSilenceTimeout();
    //             };

    //             mediaRecorder.onstop = function () {
    //                 processAudio();
    //             };

    //         } catch (error) {
    //             console.error("Microphone access error:", error);
    //             $output.html('<span class="text-danger">Microphone access denied.</span>');
    //         }
    //     } else {
    //         stopRecording();
    //     }
    // });

    // function resetSilenceTimeout() {
    //     clearTimeout(silenceTimeout);
    //     silenceTimeout = setTimeout(stopRecording, 5000);
    // }

    // function stopRecording() {
    //     if (mediaRecorder && mediaRecorder.state !== "inactive") {
    //         mediaRecorder.stop();
    //     }

    //     $('.speech-recognition-btn').removeClass('speech-recognition-active text-primary')
    //         .addClass('speech-recognition-inactive text-muted');
    //     $('.speech-recognition-status-text').text('NOT LISTENING');
    // }

    // function processAudio() {
    //     const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
    //     const reader = new FileReader();

    //     reader.onloadend = function () {
    //         const base64Audio = reader.result.split(',')[1];
    //         $('.speech-recognition-output code').text(base64Audio);
    //         console.log("Raw Audio Data (Base64):", base64Audio);

    //         // $.ajax({
    //         //     url: "AWS_ENDPOINT",
    //         //     type: "POST",
    //         //     contentType: "application/json",
    //         //     data: JSON.stringify({ audio: base64Audio }),
    //         //     success: function (response) {
    //         //         console.log("AWS Response:", response);
    //         //     },
    //         //     error: function (xhr, status, error) {
    //         //         console.error("AWS Error:", error);
    //         //     }
    //         // });
    //     };

    //     reader.readAsDataURL(audioBlob);
    // }

    const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    recognition.continuous = true;
    recognition.interimResults = true;
    recognition.lang = 'en-US';

    let finalTranscript = '';
    let silenceTimeout;

    $('.speech-recognition-btn').on('click', function () {
        const $this = $(this);
        const $status = $('.speech-recognition-status-text');
        const $output = $('.speech-recognition-output code');

        if ($this.hasClass('speech-recognition-inactive')) {
            finalTranscript = ''; 
            $this.removeClass('speech-recognition-inactive text-muted')
                .addClass('speech-recognition-active text-primary');
            $status.text('LISTENING');
            $output.html('<span class="text-muted">Listening...</span>');

            recognition.start();
        } else {
            stopRecognition();
        }
    });

    recognition.onresult = function (event) {
        clearTimeout(silenceTimeout);

        let newText = '';
        for (let i = 0; i < event.results.length; i++) {
            if (event.results[i].isFinal) {
                newText += event.results[i][0].transcript + ' ';
            }
        }

        if (newText.trim() !== '') {
            finalTranscript += newText;
        }

        // Highlight detected pseudocode keywords
        const pseudocodeKeywords = /\b(if|else|elseif|while|for|do|until|repeat|switch|case|default|break|function|procedure|return|print|input|output|declare|set|assign|begin|end)\b/gi;
        const highlightedText = newText.replace(pseudocodeKeywords, '<span class="text-warning">$&</span>');

        $('.speech-recognition-output code').append(' ' + highlightedText);
        
        console.log("Preprocessed Speech Data:", newText);

        silenceTimeout = setTimeout(stopRecognition, 5000);
    };


    function stopRecognition() {
        recognition.stop();
        $('.speech-recognition-btn').removeClass('speech-recognition-active text-primary')
            .addClass('speech-recognition-inactive text-muted');
        $('.speech-recognition-status-text').text('NOT LISTENING');

        console.log("Final Compiled Preprocessed Data:", finalTranscript);

        // $.ajax({
        //     url: "YOUR_AWS_API_GATEWAY_ENDPOINT",
        //     type: "POST",
        //     contentType: "application/json",
        //     data: JSON.stringify({ text: finalTranscript }),
        //     success: function (response) {
        //         console.log("AWS Transcribe Response:", response);
        //     },
        //     error: function (xhr, status, error) {
        //         console.error("AWS Transcribe Error:", error);
        //     }
        // });
    }

    recognition.onerror = function () {
        $('.speech-recognition-output code').html('<span class="text-danger">Speech recognition error.</span>');
    };

    $('.back-to-main-btn').on('click', function() {
        $(this).prop('disabled', true);
        $(this).html('<i class="spinner-border spinner-border-sm"></i>');
    });
    $('.rooms-join-btn').on('click', function() {
        $('.rooms-menu-container').removeClass('d-flex').hide();
        $('.rooms-join-container').show();
    });
});
</script>
</html>