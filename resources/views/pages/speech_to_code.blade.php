<div class="group-container justify-content-around gap-2 mb-2" data-group="speech_to_code" style="position: relative; display: none;">
    <div class="speech-recognition-container text-center" style="position: relative; z-index: 5;">
        <div class="text-center">
            <input type="hidden" class="speech-recognition-output-language">
            <button type="button" class="speech-recognition-output-language-btn btn btn-outline-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-speech-language"><img src="{{ asset('images/python_logo.svg') }}" class="speech-language-icon me-2" width="24" height="24"> <b class="speech-language-text">Python</b></button>
            <button type="button" class="speech-recognition-refresh-btn btn btn-outline-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-speech-language">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.918Z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        <div style="position: absolute; top: 225px; left: 185px;">
            <h6 class="speech-recognition-title-text speech-recognition-status-text">TAP TO ACTIVATE</h6>
        </div>
        <div class="speech-recognition-btn speech-recognition-inactive text-muted mt-3">
            <i class="speech-recognition-icon-mic bi bi-mic-fill" style="font-size: 72px;"></i>
            <i class="speech-recognition-icon-processing spinner-border" style="width: 72px; height: 72px; margin-right: -55px; display: none;"></i>
        </div>
        <div class="text-muted mt-5">
            <textarea class="speech-recognition-output-speech form-control" rows="5" placeholder="Your speech will appear here..."></textarea>
        </div>
    </div>
    <div class="speech-activated" style="
        position: absolute;
        transform: scale(6);
        top: 144px;
        left: 225px;
        z-index: 2;
        display: none;
    "></div>
</div>