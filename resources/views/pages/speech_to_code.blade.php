<div class="group-container justify-content-around gap-2 mb-2" data-group="speech_to_code" style="position: relative; display: none;">
    <div class="speech-recognition-container text-center" style="position: relative; z-index: 5;">
        <div class="text-center">
            <input type="hidden" class="speech-recognition-output-language">
            <button type="button" class="speech-recognition-output-language-btn btn btn-outline-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-speech-language"><img src="{{ asset('images/python_logo.svg') }}" class="speech-language-icon me-2" width="24" height="24"> <b class="speech-language-text">Python</b></button>
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