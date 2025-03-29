<div class="group-container" data-group="settings" style="display: none;">
    <div class="mb-5">
        <h4 class="text-center mt-2"><span class="text-gradient-primary">My Settings</span></h4>
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
            <button type="submit" class="save-settings-btn btn btn-primary btn-lg w-100">Save Changes</button>
        </div>
    </div>
</div>