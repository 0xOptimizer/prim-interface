<!DOCTYPE html>

<html>
<head>
    @include('components.header')
</head>
<body>
    <div class="phone-container">
        <div class="top-bar mb-5">
            <div class="d-flex justify-content-between align-items-center">
                <button class="group-navigate-btn group-navigate-btn-back btn btn-primary btn-sm" type="button" data-group="settings" data-group-action="back" style="width: 50px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 0 0-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 0 0-2.282.819l-.922 1.597a1.875 1.875 0 0 0 .432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 0 0 0 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 0 0-.432 2.385l.922 1.597a1.875 1.875 0 0 0 2.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 0 0 2.28-.819l.923-1.597a1.875 1.875 0 0 0-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 0 0 0-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 0 0-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 0 0-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 0 0-1.85-1.567h-1.843ZM12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z" clip-rule="evenodd" />
                    </svg>
                </button>
                <h5 style="margin-top: 9px; margin-right: auto; margin-left: 12px;"><span>Hi, <span class="text-gradient-primary">{{ strlen($data['user']['first_name']) > 20 ? substr($data['user']['first_name'], 0, 17) . '...' : $data['user']['first_name'] }}</span></span></h5>
                <div class="dropdown position-relative">
                    <button class="btn dropdown-toggle p-0 border-0" type="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="background: none;">
                        <img src="{{ $data['user']['user_image'] ?? asset('images/user/default.png') }}" alt="Profile" class="image-backdrop rounded-circle" style="width: 40px; height: 40px;">
                        <img src="{{ $data['user']['user_image'] ?? asset('images/user/default.png') }}" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px;">
                    </button>
                    @if ($data['user']['user_type'] === 'admin')
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="position: absolute; top: -5px; left: -5px; width: 15px; height: 15px; background-color: #000000ed; color: #f8928f; border-radius: 50%;" class="size-6">
                            <path fill-rule="evenodd" d="M14.447 3.026a.75.75 0 0 1 .527.921l-4.5 16.5a.75.75 0 0 1-1.448-.394l4.5-16.5a.75.75 0 0 1 .921-.527ZM16.72 6.22a.75.75 0 0 1 1.06 0l5.25 5.25a.75.75 0 0 1 0 1.06l-5.25 5.25a.75.75 0 1 1-1.06-1.06L21.44 12l-4.72-4.72a.75.75 0 0 1 0-1.06Zm-9.44 0a.75.75 0 0 1 0 1.06L2.56 12l4.72 4.72a.75.75 0 0 1-1.06 1.06L.97 12.53a.75.75 0 0 1 0-1.06l5.25-5.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                        </svg>
                    @elseif ($data['user']['user_type'] === 'teacher')
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="position: absolute; top: -5px; left: -5px; width: 15px; height: 15px; background-color: black; color: white; border-radius: 50%;" class="size-6">
                            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                        </svg>
                    @elseif ($data['user']['user_type'] === 'student')
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="position: absolute; top: -5px; left: -5px; width: 15px; height: 15px; background-color: black; color: white; border-radius: 50%;" class="size-6">
                            <path d="M11.7 2.805a.75.75 0 0 1 .6 0A60.65 60.65 0 0 1 22.83 8.72a.75.75 0 0 1-.231 1.337 49.948 49.948 0 0 0-9.902 3.912l-.003.002c-.114.06-.227.119-.34.18a.75.75 0 0 1-.707 0A50.88 50.88 0 0 0 7.5 12.173v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 0 1 4.653-2.52.75.75 0 0 0-.65-1.352 56.123 56.123 0 0 0-4.78 2.589 1.858 1.858 0 0 0-.859 1.228 49.803 49.803 0 0 0-4.634-1.527.75.75 0 0 1-.231-1.337A60.653 60.653 0 0 1 11.7 2.805Z" />
                            <path d="M13.06 15.473a48.45 48.45 0 0 1 7.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 0 1-.46.711 47.87 47.87 0 0 0-8.105 4.342.75.75 0 0 1-.832 0 47.87 47.87 0 0 0-8.104-4.342.75.75 0 0 1-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 0 0-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.66a6.727 6.727 0 0 0 .551-1.607 1.5 1.5 0 0 0 .14-2.67v-.645a48.549 48.549 0 0 1 3.44 1.667 2.25 2.25 0 0 0 2.12 0Z" />
                            <path d="M4.462 19.462c.42-.419.753-.89 1-1.395.453.214.902.435 1.347.662a6.742 6.742 0 0 1-1.286 1.794.75.75 0 0 1-1.06-1.06Z" />
                        </svg>
                    @endif
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                        <li>
                            <a class="dropdown-item" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-person me-2" viewBox="0 0 16 16" style="width: 16px; height: 16px;">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 1a5.978 5.978 0 0 0-4.546 2.086C3.02 11.57 3 12.28 3 12.5c0 .276.224.5.5.5h9a.5.5 0 0 0 .5-.5c0-.22-.02-.93-.454-1.414A5.978 5.978 0 0 0 8 9z"/>
                                </svg>
                                Account
                            </a>
                        </li>
                        @if ($data['user']['user_type'] === 'admin')
                        <li>
                            <a class="dropdown-item" href="analytics">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 me-2" style="width: 16px; height: 16px;">
                                    <path fill-rule="evenodd" d="M3 6a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3V6ZM3 15.75a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2.25Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3v-2.25Z" clip-rule="evenodd" />
                                </svg>
                                Admin Panel
                            </a>
                        </li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a href="{{ route('logout') }}" class="dropdown-item text-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 me-2" style="width: 16px; height: 16px;">
                                    <path fill-rule="evenodd" d="M7.5 3.75A1.5 1.5 0 0 0 6 5.25v13.5a1.5 1.5 0 0 0 1.5 1.5h6a1.5 1.5 0 0 0 1.5-1.5V15a.75.75 0 0 1 1.5 0v3.75a3 3 0 0 1-3 3h-6a3 3 0 0 1-3-3V5.25a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3V9A.75.75 0 0 1 15 9V5.25a1.5 1.5 0 0 0-1.5-1.5h-6Zm10.72 4.72a.75.75 0 0 1 1.06 0l3 3a.75.75 0 0 1 0 1.06l-3 3a.75.75 0 1 1-1.06-1.06l1.72-1.72H9a.75.75 0 0 1 0-1.5h10.94l-1.72-1.72a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                </svg>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Include the pages here -->
        @include('pages.main')
        @include('pages.settings')
        @include('pages.speech_to_code')
        @include('pages.rooms')
        @include('pages.rooms_join')
        @include('pages.rooms_create')
    </div>
@include('offcanvas.speech.language')
@include('offcanvas.speech.output')
@include('offcanvas.rooms')
</body>
<script>
    window.AppData = {
        user_uuid: "{{ $data['user']['uuid'] }}",
    };
</script>
<script src="{{ asset('js/dashboard.js?v=5') }}"></script>
</html>