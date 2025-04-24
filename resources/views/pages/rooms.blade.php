<div class="group-container mb-2" data-group="rooms" style="display: none;">
    @if ($data['user']['user_type'] === 'teacher')
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
        </div>
    @elseif ($data['user']['user_type'] === 'student')
        <div class="rooms-menu-container d-flex justify-content-around gap-2">
            <div class="w-100">
                <div class="group-navigate-btn rooms-card-btn card card-purple-bg" data-group="rooms_join">
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
    @endif
</div>