<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas-speech-language">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title text-gradient-primary text-center">Select a programming language</h5>
        <button type="button" class="btn btn-outline-primary ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"><i class="bi bi-x" style="font-size: 32px;"></i></button>
    </div>
    <div class="offcanvas-body">
        <div class="speech-language-select-option card card-interactable" data-option="python">
            <div class="card-body d-flex align-items-center">
                <img src="{{ asset('images/python_logo.svg') }}" width="64" height="64" class="me-4">
                <div>
                    <span style="font-size: 24px;">Python (.py)</span>
                    <br>
                    <span style="opacity: 0.65;">Python is a versatile, readable programming language.</span>
                </div>
            </div>
        </div>
        <div class="speech-language-select-option card card-interactable" data-option="javascript">
            <div class="card-body d-flex align-items-center">
                <img src="{{ asset('images/javascript_logo.svg') }}" width="64" height="64" class="me-4">
                <div>
                    <span style="font-size: 24px;">JavaScript (.js)</span>
                    <br>
                    <span style="opacity: 0.65;">JavaScript is mainly used for web development.</span>
                </div>
            </div>
        </div>
        <!-- <div class="card mt-2">
            <div class="select-species-option card-body d-flex align-items-center">
                <i class="bi bi-info-circle me-3" style="font-size: 48px;"></i>
                <div class="w-100">
                    <input type="text" class="form-control" placeholder="Other (Enter species)">
                </div>
            </div>
        </div> -->
    </div>
</div>