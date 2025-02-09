<!DOCTYPE html>

<html>
<head>
    @include('components.header')
</head>
<style>


</style>
<body>
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
                    <button class="unavailable-btn btn btn-outline-primary btn-lg" type="button"><span style="font-size: 16px;">Create Account</span></button>
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
                <a href="#" class="unavailable-btn small d-block" style="margin-top: -20px; margin-bottom: 30px;">Forgot password?</a>
                <div class="d-grid gap-2 mb-3">
                    <button class="login-btn btn btn-primary btn-lg" type="button">Log In</button>
                </div>

                <div class="text-center mt-4">
                    <div class="unavailable-btn form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="remember">
                        <label class="form-check-label small" for="remember">Remember me</label>
                    </div>
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
    $('.login-btn').on('click', function() {
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
});
</script>
</html>