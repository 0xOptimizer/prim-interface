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
                <div class="dropdown">
                    <button class="btn dropdown-toggle p-0 border-0" type="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="background: none;">
                        <img src="{{ $data['user']['user_image'] ?? asset('images/user/default.png') }}" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px;">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                        <li>
                            <a class="dropdown-item" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-person me-2" viewBox="0 0 16 16" style="width: 16px; height: 16px;">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 1a5.978 5.978 0 0 0-4.546 2.086C3.02 11.57 3 12.28 3 12.5c0 .276.224.5.5.5h9a.5.5 0 0 0 .5-.5c0-.22-.02-.93-.454-1.414A5.978 5.978 0 0 0 8 9z"/>
                                </svg>
                                Account
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a href="{{ route('logout') }}" class="dropdown-item text-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-box-arrow-right me-2" viewBox="0 0 16 16" style="width: 16px; height: 16px;">
                                    <path fill-rule="evenodd" d="M6.146 11.354a.5.5 0 0 1 0-.708L9.293 8 6.146 4.854a.5.5 0 1 1 .708-.708l3.5 3.5a.5.5 0 0 1 0 .708l-3.5 3.5a.5.5 0 0 1-.708 0z"/>
                                    <path fill-rule="evenodd" d="M4.5 8a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z"/>
                                    <path fill-rule="evenodd" d="M13.5 15a.5.5 0 0 1-.5-.5V1.5a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5z"/>
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
    </div>
</body>
<script src="{{ asset('js/dashboard.js?v=2') }}"></script>
</html>