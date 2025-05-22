<!DOCTYPE html>

<html>
<head>
    @include('components.header')
</head>
<style>


</style>
<body>
    <!-- hi -->
    <div class="phone-container">
        <div class="group-container" data-group="main">
            <div class="text-center mb-5">
                <img src="{{ asset('images/prim-logo.png') }}" alt="Logo" class="logo img-fluid mx-auto d-block">
                <h2 class="text-gradient-primary animate__animated animate__fadeInDown animate__durationExcluded" style="margin-top: -10px;">PRIM</h2>
                <p class="text-muted fs-6 animate__animated animate__fadeIn animate__delay-1s animate__durationExcluded">Transform your learning experience<br>with voice-powered education</p>
            </div>
            <form>
                <div class="d-grid gap-2 mb-3">
                    <button class="group-navigate-btn btn btn-primary btn-lg" type="button" data-group="login"><span style="font-size: 16px;">Log In</span></button>
                </div>
                <div class="d-grid gap-2">
                    <button class="group-navigate-btn btn btn-outline-primary btn-lg" type="button" data-group="register"><span style="font-size: 16px;">Create Account</span></button>
                </div>
            </form>
        </div>
        <div class="group-container" data-group="login" style="display: none;">
            <div class="mb-5">
                <button class="group-navigate-btn btn btn-outline-primary btn-sm" type="button" data-group="main" style="width: 50px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </button>
                <h4 class="text-center mt-5"><span class="text-gradient-primary">Welcome Back</span></h4>
            </div>
            <form>
                <div class="form-floating mb-3">
                    <input type="email" id="login-email" class="form-control">
                    <label for="form-email">Email</label>
                </div>
                <div class="form-floating mb-4">
                    <input type="password" id="login-password" class="form-control">
                    <label>Password</label>
                </div>
                <a href="#" class="group-navigate-btn small d-block" data-group="forgot_password" style="margin-top: -20px; margin-bottom: 30px;">Forgot password?</a>
                <div class="d-grid gap-2 mb-3">
                    <button class="login-btn btn btn-primary btn-lg" type="submit">Log In</button>
                </div>

                <div class="text-center mt-4">
                    <div class="unavailable-btn form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="remember">
                        <label class="form-check-label small" for="remember">Remember me</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="group-container" data-group="register" style="display: none;">
            <div class="mb-5">
                <button class="group-navigate-btn btn btn-outline-primary btn-sm" type="button" data-group="main" style="width: 50px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </button>
                <h4 class="text-center"><span class="text-gradient-primary">Create Account</span></h4>
            </div>
            <form id="registrationForm" autocomplete="off">
                <!-- Name Fields -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="register-first_name" name="register-first_name" required>
                            <label for="register-first_name">First Name</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="register-last_name" name="register-last_name">
                            <label for="register-last_name">Last Name</label>
                        </div>
                    </div>
                </div>

                <!-- Email -->
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="register-email" name="register-email" autocomplete="new-email" required>
                    <label for="register-email">Email address</label>
                </div>

                <!-- User Type -->
                <div class="form-floating mb-3">
                    <select class="form-control" name="register-user_type" id="register-user_type">
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                    </select>
                    <label for="register-user_type">Role</label>
                </div>

                <!-- Password Fields -->
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="register-password" name="register-password" autocomplete="new-password" required>
                    <label for="register-password">Password</label>
                </div>
                <div class="form-floating mb-4">
                    <input type="password" class="form-control" id="register-confirm_password" name="register-confirm_password" required>
                    <label for="register-confirm_password">Confirm Password</label>
                    <div class="invalid-feedback" id="passwordError">Passwords do not match</div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2 mb-3">
                    <button class="register-btn btn btn-primary btn-lg" type="submit">Register</button>
                </div>
            </form>
        </div>
        <div class="group-container" data-group="forgot_password" style="display: none;">
            <div class="mb-5">
                <button class="group-navigate-btn btn btn-outline-primary btn-sm" type="button" data-group="main" style="width: 50px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </button>
                <h4 class="text-center"><span class="text-gradient-primary">Forgot your password?</span></h4>
            </div>
            <form id="forgotPasswordForm" autocomplete="off">
                <!-- Email -->
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="forgot_password-email" name="forgot_password-email" autocomplete="new-email" required>
                    <label for="forgot_password-email">Email address</label>
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2 mb-3">
                    <button class="forgot_password-btn btn btn-primary btn-lg" type="submit">Send Recovery Link</button>
                </div>
            </form>
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
    $('.login-btn').on('click', function(e) {
        e.preventDefault();

        const _this = this

        $(_this).attr('disabled', true);
        $(_this).html('<i class="spinner-border spinner-border-sm"></i>');

        const email = $('#login-email').val();
        const password = $('#login-password').val();

        $.ajax({
            url: 'https://prim-api.o513.dev/api/v1/auth/login',
            type: 'POST',
            data: JSON.stringify({
                email: email,
                password: password
            }),
            contentType: "application/json",
            dataType: "json",
            success: function (response, textStatus, xhr) {
                if (xhr.status === 200) {
                    setCookie("auth_token", response.token, 60);
                    let messageText = "";
                    let messageIcon = "success";

                    $.post('/auth/store-token', { token: response.token }, function () {
                        sweetAlertStatusMessage(messageText, messageIcon);
                    });

                    setTimeout(function () {
                        window.location.href = window.location.origin + '/dashboard';
                    }, 750);
                    
                } else {
                    let messageText = response.message || 'An error occurred';
                    let messageIcon = 'error';
                    sweetAlertStatusMessage(messageText, messageIcon);
                    $(_this).attr('disabled', false);
                    $(_this).html('Log In');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                let messageText = 'Failed to connect to servers. Please try again!';
                let messageIcon = 'warning';
                
                const response = jqXHR.responseJSON || {};
                
                if (jqXHR.status === 400) {
                    messageText = 'Invalid credentials. Please try again.';
                } else if (jqXHR.status === 500) {
                    messageText = 'Server error. Please try again later.';
                } else if (jqXHR.status === 422 || jqXHR.status === 401) {
                    messageText = response.message || 'Please check your input and try again';
                    messageIcon = 'error';
                }
                
                if (!messageText && response.message) {
                    messageText = response.message;
                }

                sweetAlertStatusMessage(messageText, messageIcon);
                
                setTimeout(function() {
                    $(_this).attr('disabled', false);
                    $(_this).html('Log In');
                }, 750);
            }
        });
    });

    $('.register-btn').on('click', function(e) {
        e.preventDefault();
        
        const _this = this

        $(_this).attr('disabled', true);
        $(_this).html('<i class="spinner-border spinner-border-sm"></i>');

        const first_name = $('#register-first_name').val();
        const last_name = $('#register-last_name').val();
        const email = $('#register-email').val();
        const password = $('#register-password').val();
        const confirm_password = $('#register-confirm_password').val();
        const user_type = $('#register-user_type').val();

        $.ajax({
            url: 'https://prim-api.o513.dev/api/v1/users',
            type: 'POST',
            data: JSON.stringify({
                first_name: first_name,
                last_name: last_name,
                email: email,
                password: password,
                password_confirmation: confirm_password,
                user_type: user_type
            }),
            contentType: "application/json",
            dataType: "json",
            success: function (response, textStatus, xhr) {
                if (xhr.status === 201) {
                    let messageText = "Registered successfully! You may now log in.";
                    let messageIcon = "success";
                    sweetAlertStatusMessage(messageText, messageIcon);

                    setTimeout(function () {
                        window.location.href = window.location.origin + '/login';
                    }, 1250);
                } else {
                    let messageText = response.message || 'An error occurred';
                    let messageIcon = 'error';
                    sweetAlertStatusMessage(messageText, messageIcon);
                    $(_this).attr('disabled', false);
                    $(_this).html('Register');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                let messageText = 'Failed to connect to servers. Please try again!';
                let messageIcon = 'warning';
                
                const response = jqXHR.responseJSON || {};
                
                if (jqXHR.status === 500) {
                    messageText = 'Server error. Please try again later.';
                } else if (jqXHR.status === 422 || jqXHR.status === 401) {
                    messageText = response.message || 'Please check your input and try again';
                    messageIcon = 'error';
                }
                
                if (!messageText && response.message) {
                    messageText = response.message;
                }

                sweetAlertStatusMessage(messageText, messageIcon);
                
                setTimeout(function() {
                    $(_this).attr('disabled', false);
                    $(_this).html('Register');
                }, 750);
            }
        });
    });

    $('#register-password, #register-confirm_password').on('keyup', function() {
        const password = $('#register-password').val();
        const confirmPassword = $('#register-confirm_password').val();
        
        if (password !== confirmPassword) {
            $('#register-confirm_password').addClass('is-invalid');
            $('#passwordError').show();
            $('.register-btn').prop('disabled', true);
        } else {
            $('#register-confirm_password').removeClass('is-invalid');
            $('#passwordError').hide();
            $('.register-btn').prop('disabled', false);
        }
    });

    $('.forgot_password-btn').on('click', function(e) {
        e.preventDefault();
        
        const _this = this;

        $(_this).attr('disabled', true);
        $(_this).html('<i class="spinner-border spinner-border-sm"></i>');

        const email = $('#forgot_password-email').val();

        $.ajax({
            url: 'https://prim-api.o513.dev/api/v1/recover/request',
            type: 'POST',
            data: JSON.stringify({
                email: email 
            }),
            contentType: "application/json",
            dataType: "json",
            success: function (response, textStatus, xhr) {
                if (xhr.status === 200) {
                    let messageText = "Recovery link sent to your email!";
                    let messageIcon = "success";
                    sweetAlertStatusMessage(messageText, messageIcon);

                    setTimeout(function () {
                        window.location.href = window.location.origin + '/login';
                    }, 1250);
                } else {
                    let messageText = response.message || 'An error occurred';
                    let messageIcon = 'error';
                    sweetAlertStatusMessage(messageText, messageIcon);
                    $(_this).attr('disabled', false);
                    $(_this).html('Send Recovery Link');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                let messageText = 'Failed to connect to servers. Please try again!';
                let messageIcon = 'warning';
                
                const response = jqXHR.responseJSON || {};
                
                if (jqXHR.status === 500) {
                    messageText = 'Server error. Please try again later.';
                } else if (jqXHR.status === 422 || jqXHR.status === 401) {
                    messageText = response.message || 'Please check your input and try again';
                    messageIcon = 'error';
                }
                
                if (!messageText && response.message) {
                    messageText = response.message;
                }

                sweetAlertStatusMessage(messageText, messageIcon);
                
                setTimeout(function() {
                    $(_this).attr('disabled', false);
                    $(_this).html('Send Recovery Link');
                }, 750);
            }
        });
    });
});
</script>
</html>