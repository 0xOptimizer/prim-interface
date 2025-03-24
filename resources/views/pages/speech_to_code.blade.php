<div class="group-container justify-content-around gap-2 mb-2" data-group="speech_to_code" style="display: none;">
    <div class="speech-recognition-container text-center">
        <div>
            <h6 class="speech-recognition-title-text speech-recognition-status-text">NOT LISTENING</h6>
        </div>
        <div class="speech-recognition-btn speech-recognition-inactive text-muted mt-5">
            <i class="bi bi-mic-fill" style="font-size: 72px;"></i>
        </div>
        <div class="text-muted mt-5">
            <textarea class="speech-recognition-output-speech form-control" rows="5" placeholder="Your speech will appear here..."></textarea>
        </div>
        <hr class="mt-3">
        <div class="form-floating mb-3">
            <select class="speech-recognition-output-language form-select" id="programmingLanguageSelect" aria-label="Floating label select example">
                <option value="python">Python</option>
                <option value="javascript">JavaScript</option>
                <option value="java">Java</option>
            </select>
            <label for="programmingLanguageSelect">Language</label>
        </div>
        <div class="speech-recognition-output-code">
            <pre>
            </pre>
        </div>
    </div>
</div>