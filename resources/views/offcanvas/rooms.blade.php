<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas-rooms">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">
            <span class="text-gradient-primary" id="view-room-code"></span>
            <br>
            <span id="view-room-name" style="font-size: 14px;"></span>
        </h5>
        <div class="btn-group ms-auto">
            <button type="button" class="btn btn-primary"><i class="bi bi-code-square" style="font-size: 32px;"></i></button>
            <button type="button" class="btn btn-outline-primary" data-bs-dismiss="offcanvas" aria-label="Close"><i class="bi bi-x" style="font-size: 32px;"></i></button>
        </div>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <div class="chat-container flex-grow-1">
            <div class="chat-messages">
                <!-- Messages will be dynamically loaded here -->
            </div>
        </div>
        <div class="chat-input mt-3 sticky-bottom">
            <form id="chat-form">
                <div class="input-group">
                    <input type="text" class="form-control" id="chat-message" placeholder="Type your message..." required>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>