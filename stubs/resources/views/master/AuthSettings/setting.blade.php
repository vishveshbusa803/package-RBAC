@extends('layouts.master')
@section('content')

@push('styles')
<style>
    /* Your existing styles */
    body {
        background-color: #f5f7fb !important;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        color: #333;
    }

    .container-main {
        max-width: 1200px;
    }

    .card {
        border-radius: 12px;
        border: 1px solid #e9ecef;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
    }

    .card:hover {
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
    }

    .card-header {
        background-color: white;
        border-bottom: 1px solid #e9ecef;
        padding: 1.25rem 1.5rem;
        font-weight: 600;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header i {
        color: #6f42c1;
    }

    .card-body {
        padding: 1.5rem;
    }

    .section-title {
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 15px;
        color: #444;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title i {
        font-size: 0.9rem;
        color: #6f42c1;
    }

    .section-subtitle {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 1.25rem;
        max-width: 800px;
    }

    .divider {
        border-top: 1px solid #e9ecef;
        margin: 1.75rem 0;
    }

    .form-check {
        margin-bottom: 0.75rem;
        padding-left: 2.5rem;
    }

    .form-check-input {
        width: 1.2rem;
        height: 1.2rem;
        margin-left: -2.5rem;
        margin-top: 0.15rem;
        cursor: pointer;
        border: 2px solid #adb5bd;
    }

    .form-check-input:checked {
        background-color: #6f42c1;
        border-color: #6f42c1;
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.25);
    }

    .form-check-label {
        cursor: pointer;
        font-weight: 500;
        font-size: 0.95rem;
        margin-left: 10px;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        color: #444;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #e9ecef;
        padding: 0.6rem 0.9rem;
        font-size: 0.95rem;
    }

    .form-control:focus {
        border-color: #6f42c1;
        box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.15);
    }

    .small-note {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 0.5rem;
        line-height: 1.5;
        display: flex;
        align-items: flex-start;
        gap: 6px;
    }

    .small-note i {
        color: #ffc107;
        font-size: 1rem;
        margin-top: 0.1rem;
    }

    .badge-info {
        background-color: var(--primary-light);
        color: #6f42c1;
        font-weight: 500;
        padding: 0.3rem 0.6rem;
        font-size: 0.75rem;
        border-radius: 4px;
        margin-left: 8px;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        font-size: 0.9rem;
    }

    .btn {
        border-radius: 8px;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background-color: #6f42c1;
        border-color: #6f42c1;
    }

    .btn-primary:hover {
        background-color: #5e35b1;
        border-color: #5e35b1;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(111, 66, 193, 0.25);
    }

    .btn-outline-secondary {
        border-color: #e9ecef;
        color: #666;
    }

    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        color: #333;
    }

    .method-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background-color: var(--primary-light);
        color: #6f42c1;
        margin-bottom: 10px;
    }

    .method-card {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 1.25rem;
        transition: all 0.2s ease;
        height: 100%;
        cursor: pointer;
    }

    .method-card:hover {
        border-color: #6f42c1;
        background-color: rgba(111, 66, 193, 0.02);
    }

    .method-card.active {
        border-color: #6f42c1;
        background-color: rgba(111, 66, 193, 0.05);
    }

    .method-name {
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }

    .method-desc {
        font-size: 0.85rem;
        color: #6c757d;
        line-height: 1.4;
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
        margin-bottom: 0;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.toggle-slider {
        background-color: #6f42c1;
    }

    input:checked+.toggle-slider:before {
        transform: translateX(24px);
    }

    .settings-group {
        background-color: #f9fafc;
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e9ecef;
    }

    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-weight: 500;
    }

    .status-active {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }

    .status-inactive {
        background-color: rgba(108, 117, 125, 0.1);
        color: #6c757d;
    }

    @media (max-width: 768px) {
        .container-main {
            margin: 1rem auto;
            padding: 0 1rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        .section-title {
            font-size: 0.95rem;
        }

        #toast-container {
            min-width: 90%;
            left: 5% !important;
            right: 5% !important;
        }
    }

    .config-section {
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .nav {
        --bs-nav-link-color: #0d6efd;
    }

    .nav-tabs {
        --bs-nav-tabs-link-active-bg: #fff !important;
        --bs-nav-tabs-border-width: 1px;
        --bs-nav-tabs-border-color: #dee2e6;
    }
</style>
@endpush

<div class="container-main">
    <ul class="nav nav-tabs mb-4" id="authTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="auth-tab" data-bs-toggle="tab" data-bs-target="#authSettings" type="button" role="tab">
                <i class="fas fa-shield-alt me-1"></i> Authentication Settings
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#passwordCriteria" type="button" role="tab">
                <i class="fas fa-solid fa-key me-1"></i> Password Criteria
            </button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="authSettings" role="tabpanel">
            <input type="hidden" id="captchaAuthSettingId" />
            <input type="hidden" id="emailOtpAuthSettingId" />
            <input type="hidden" id="mobileOtpAuthSettingId" />
            <input type="hidden" id="twoFaAuthSettingId" />

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-shield-alt"></i> Authentication Methods
                </div>

                <div class="card-body">
                    <p class="section-subtitle">
                        Enable or disable authentication methods for your users. Multiple methods can be active simultaneously.
                    </p>

                    <div class="row g-3 mb-1">
                        <div class="col-md-6 col-lg-3">
                            <div class="method-card" id="captchaCard">
                                <div class="method-icon">
                                    <i class="fas fa-solid fa-robot" style="font-size: 1.2rem"></i>
                                </div>
                                <div class="method-name">Captcha</div>
                                <div class="method-desc">Protects against automated attacks and bots</div>
                                <div class="mt-3">
                                    <div class="form-check form-switch form-switch-lg">
                                        <input type="checkbox" class="form-check-input" id="captchaToggle">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="method-card" id="emailOtpCard">
                                <div class="method-icon">
                                    <i class="fas fa-solid fa-envelope" style="font-size: 1.2rem"></i>
                                </div>
                                <div class="method-name">Email OTP</div>
                                <div class="method-desc">
                                    One-time password sent to user's registered email address
                                </div>

                                <div class="mt-3 d-flex justify-content-between align-items-center">
                                    <div class="form-check form-switch form-switch-lg">
                                        <input type="checkbox" class="form-check-input" id="emailOtpToggle">
                                    </div>
                                    <button type="button"
                                        class="btn btn-primary btn-sm"
                                        id="filterBtn"
                                        data-bs-toggle="offcanvas"
                                        data-bs-target="#btnEmailSidebar">
                                        configure
                                    </button>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="method-card" id="mobileOtpCard">
                                <div class="method-icon">
                                    <i class="fas fa-mobile-alt" style="font-size: 1.2rem"></i>
                                </div>
                                <div class="method-name">Mobile OTP</div>
                                <div class="method-desc">
                                    One-time password sent via SMS to user's mobile number
                                </div>

                                <div class="mt-3 d-flex justify-content-between align-items-center">
                                    <div class="form-check form-switch form-switch-lg">
                                        <input type="checkbox" class="form-check-input" id="mobileOtpToggle">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="method-card" id="twoFaCard">
                                <div class="method-icon">
                                    <i class="fas fa-solid fa-key" style="font-size: 1.2rem"></i>
                                </div>
                                <div class="method-name">Two-Factor Authentication</div>
                                <div class="method-desc">Authenticator apps like Google Authenticator or Authy</div>

                                <div class="mt-3 d-flex justify-content-between align-items-center">
                                    <div class="form-check form-switch form-switch-lg">
                                        <input type="checkbox" class="form-check-input" id="twoFaToggle">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="small-note">
                        <i class="fas fa-info-circle"></i>
                        <span>
                            At least one authentication method must be active. Changes will take effect immediately.
                        </span>
                    </div>
                </div>

                <div class="card-header">
                    <i class="fas fa-clock"></i>
                    OTP Rules & Limits
                </div>
                <div class="card-body">
                    <p class="section-subtitle">Configure attempts and reset timers for OTP verification.</p>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Mobile OTP Attempts</label>
                            <div class="input-group">
                                <input type="number" class="form-control" min="1" max="10" id="mobileOtpAttempts">
                                <span class="input-group-text">times</span>
                            </div>
                            <div class="small-note mt-1">Maximum incorrect attempts before lockout</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Mobile OTP Reset</label>
                            <div class="input-group">
                                <input type="number" class="form-control" min="1" max="60" id="mobileOtpReset">
                                <span class="input-group-text">minutes</span>
                            </div>
                            <div class="small-note mt-1">Timeout for Resend OTP</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Mobile OTP Expiration Time</label>
                            <div class="input-group">
                                <input type="number" class="form-control" min="1" max="60" id="otpMobileExpirationTime">
                                <span class="input-group-text">minutes</span>
                            </div>
                            <div class="small-note mt-1">
                                Time after which the OTP becomes invalid
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Email OTP Attempts</label>
                            <div class="input-group">
                                <input type="number" class="form-control" min="1" max="10" id="emailOtpAttempts">
                                <span class="input-group-text">times</span>
                            </div>
                            <div class="small-note mt-1">Maximum incorrect attempts before lockout</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Email OTP Reset</label>
                            <div class="input-group">
                                <input type="number" class="form-control" min="1" max="60" id="emailOtpReset">
                                <span class="input-group-text">minutes</span>
                            </div>
                            <div class="small-note mt-1">Timeout for Resend OTP</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Email OTP Expiration Time</label>
                            <div class="input-group">
                                <input type="number" class="form-control" min="1" max="60" id="otpEmailExpirationTime">
                                <span class="input-group-text">minutes</span>
                            </div>
                            <div class="small-note mt-1">
                                Time after which the OTP becomes invalid
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">2FA Attempts</label>
                            <div class="input-group">
                                <input type="number" class="form-control" min="1" max="10" id="twoFaAttempts">
                                <span class="input-group-text">times</span>
                            </div>
                            <div class="small-note mt-1">Maximum incorrect attempts before lockout</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 p-3">
                    <button class="btn btn-outline-secondary px-4" id="btnCancelAuthSetting">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button class="btn btn-primary px-4" id="btnUpdateAuthSetting">
                        <i class="fas fa-save me-1"></i> Update Settings
                    </button>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="passwordCriteria" role="tabpanel">
            <div class="card mb-4 h-100">
                <div class="card-header">
                    <i class="fas fa-solid fa-key"></i> Password Validation Rules
                </div>

                <div class="card-body">
                    <p class="section-subtitle">Define password complexity requirements for user accounts.</p>

                    <div class="settings-group">
                        <!-- Minimum Length Rule -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check g-1">
                                <input class="form-check-input" type="checkbox" id="minLength" data-ruleid="0">
                                <label class="form-check-label" for="minLength">Minimum 8 characters</label>
                            </div>
                            <span class="badge-info">Required</span>
                        </div>

                        <!-- Uppercase Rule -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check g-1">
                                <input class="form-check-input" type="checkbox" id="uppercase" data-ruleid="0">
                                <label class="form-check-label" for="uppercase">At least 1 uppercase letter</label>
                            </div>
                        </div>

                        <!-- Lowercase Rule -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check g-1">
                                <input class="form-check-input" type="checkbox" id="lowercase" data-ruleid="0">
                                <label class="form-check-label" for="lowercase">At least 1 lowercase letter</label>
                            </div>
                        </div>

                        <!-- Numeric Rule -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check g-1">
                                <input class="form-check-input" type="checkbox" id="number" data-ruleid="0">
                                <label class="form-check-label" for="number">At least 1 number</label>
                            </div>
                        </div>

                        <!-- Special Character Rule -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="specialChar" data-ruleid="0">
                                <label class="form-check-label" for="specialChar">
                                    At least 1 special character
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="small-note mt-3">
                        <i class="fas fa-lightbulb"></i>
                        <span>
                            All selected rules will be enforced during password creation and password reset.
                        </span>
                    </div>

                    <div class="d-flex justify-content-end gap-2 p-3">
                        <button class="btn btn-outline-secondary px-4" id="btnCancelPasswordRule">
                            <i class="fas fa-times me-1"></i> Cancel
                        </button>
                        <button type="button" class="btn btn-primary px-4" id="updatePasswordRules">
                            <i class="fas fa-save me-1"></i> Update Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>


</div>


<div class="offcanvas offcanvas-end" tabindex="-1" id="btnEmailSidebar">
    <div class="offcanvas-header">
        <h5>Email Configuration</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body" id="btnEmailForm">

        <input type="hidden" id="editIndex">

        <div class="mb-2">
            <label class="form-label">Email </label>
            <input type="text" class="form-control" id="txtemail" required>
            <div class="text-danger small field-error d-none" id="lblemailError"></div>

        </div>

        <div class="mb-2">
            <label class="form-label">Email App Password </label>
            <input type="password" class="form-control" id="txtPassword" required>
            <div class="text-danger small field-error d-none" id="lblPasswordError"></div>

        </div>

        <div class="mb-2">
            <label class="form-label">SMTP Port</label>
            <input type="number" class="form-control" id="txtSmtpPort" required>
            <div class="text-danger small field-error d-none" id="lblSmtpError"></div>
        </div>

        <div class="mb-4">
            <label class="form-label">SMTP Server</label>
            <input type="text" class="form-control" id="txtSmtpServer" required>
            <div class="text-danger small field-error d-none" id="lblSmtpServerError"></div>
        </div>



        <button type="submit" class="btn btn-primary" id="btnSaveEmail">Save</button>
        <button type="button" class="btn btn-secondary ms-2" id="btnCancelEmail">Reset</button>

    </div>
</div>

@endsection

@section('script')
<!-- apexcharts -->
<script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/pages/dashboard.init.js') }}"></script>


<script>
    // Add CSRF token to all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {


        // Prevent button click from toggling checkbox

        $('.method-card button').on('click', function(e) {

            // loadEmailConfig();

            e.stopPropagation(); // 🔑 this is the fix

        });



        loadAuthSettings();
        loadPasswordRules();

        function loadAuthSettings() {
            $.ajax({
                url: '{{ route("getAuthenticationSettings") }}', // Fixed: Use named route
                type: 'GET',
                success: function(data) {
                    bindAuthSettings(data);
                },
                error: function(xhr, status, error) {
                    console.error('Error loading auth settings:', error);
                    notificationToastCustom('error', 'Failed to load authentication settings');
                }
            });
        }

        function bindAuthSettings(settings) {
            settings.forEach(function(item) {
                console.log('Binding auth setting:', item);
                switch (item.authCode) {
                    case 'CAPTCHA':
                        $('#captchaToggle').prop('checked', item.isEnabled).trigger('change');
                        $('#captchaAuthSettingId').val(item.authSettingId);
                        break;

                    case 'EMAIL_VERIFY':
                        $('#emailOtpAuthSettingId').val(item.authSettingId);
                        $('#emailOtpToggle').prop('checked', item.isEnabled).trigger('change');

                        if (item.otpattempt) $('#emailOtpAttempts').val(item.otpattempt);
                        if (item.otpresetTime) $('#emailOtpReset').val(item.otpresetTime);
                        if (item.otpexpiryTime != null) $('#otpEmailExpirationTime').val(item.otpexpiryTime);
                        break;

                    case 'MOBILE_VERIFY':
                        $('#mobileOtpAuthSettingId').val(item.authSettingId);
                        $('#mobileOtpToggle').prop('checked', item.isEnabled).trigger('change');

                        if (item.otpattempt) $('#mobileOtpAttempts').val(item.otpattempt);
                        if (item.otpresetTime) $('#mobileOtpReset').val(item.otpresetTime);
                        if (item.otpexpiryTime != null) $('#otpMobileExpirationTime').val(item.otpexpiryTime);
                        break;

                    case 'TWO_FACTOR':
                        $('#twoFaAuthSettingId').val(item.authSettingId);
                        $('#twoFaToggle').prop('checked', item.isEnabled).trigger('change');

                        if (item.otpattempt) $('#twoFaAttempts').val(item.otpattempt);
                        break;
                }
            });
        }

        /* ===============================
           Method card click interaction
        ================================ */
        $('.method-card').each(function() {
            const $card = $(this);
            const $toggle = $card.find('input[type="checkbox"]');

            $card.on('click', function(e) {
                if (!$(e.target).is('input') &&
                    !$(e.target).hasClass('toggle-switch') &&
                    !$(e.target).hasClass('toggle-slider')) {
                    $toggle.prop('checked', !$toggle.prop('checked')).trigger('change');
                }
            });
        });

        /* ===============================
           Toggle method card active state
        ================================ */
        $('.toggle-switch input').on('change', function() {
            const $card = $(this).closest('.method-card');
            $card.toggleClass('active', $(this).is(':checked'));
        });

        /* ===============================
           Update Authentication Settings
        ================================ */
        $(document).on("click", "#btnUpdateAuthSetting", function() {

            const authSettings = [];

            // Captcha
            authSettings.push({
                AuthSettingId: $('#captchaAuthSettingId').val(),
                IsEnabled: $("#captchaToggle").is(":checked"),
                AuthCode: "CAPTCHA",
                Otpattempt: null,
                OtpresetTime: null,
                OtpexpiryTime: null
            });

            // Email OTP
            authSettings.push({
                AuthSettingId: $('#emailOtpAuthSettingId').val(),
                AuthCode: "EMAIL_VERIFY",
                IsEnabled: $("#emailOtpToggle").is(":checked"),
                Otpattempt: $("#emailOtpAttempts").val() ? parseInt($("#emailOtpAttempts").val()) : null,
                OtpresetTime: $("#emailOtpReset").val() ? parseInt($("#emailOtpReset").val()) : null,
                OtpexpiryTime: $("#otpEmailExpirationTime").val() ? parseInt($("#otpEmailExpirationTime").val()) : null
            });

            // Mobile OTP
            authSettings.push({
                AuthSettingId: $('#mobileOtpAuthSettingId').val(),
                AuthCode: "MOBILE_VERIFY",
                IsEnabled: $("#mobileOtpToggle").is(":checked"),
                Otpattempt: $("#mobileOtpAttempts").val() ? parseInt($("#mobileOtpAttempts").val()) : null,
                OtpresetTime: $("#mobileOtpReset").val() ? parseInt($("#mobileOtpReset").val()) : null,
                OtpexpiryTime: $("#otpMobileExpirationTime").val() ? parseInt($("#otpMobileExpirationTime").val()) : null
            });

            // Two Factor Auth
            authSettings.push({
                AuthSettingId: $('#twoFaAuthSettingId').val(),
                AuthCode: "TWO_FACTOR",
                IsEnabled: $("#twoFaToggle").is(":checked"),
                Otpattempt: $("#twoFaAttempts").val() ? parseInt($("#twoFaAttempts").val()) : null,
                OtpresetTime: null,
                OtpexpiryTime: null
            });

            console.log('Sending auth settings:', authSettings);

            $.ajax({
                url: '{{ route("updateAuthenticationSettings") }}', // Fixed: Use named route
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(authSettings),
                success: function(response) {
                    if (response.result == true) {
                        notificationToastCustom('success', response.message, "Success");
                    } else {
                        notificationToastCustom('error', response.message, "Error");
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Update error:', error);
                    if (xhr.status === 419) {
                        notificationToastCustom('error', 'Session expired. Please refresh the page.', 'CSRF Error');
                    } else {
                        notificationToastCustom('error', "Failed to update authentication settings");
                    }
                }
            });
        });

        $('#btnCancelAuthSetting').on('click', function(e) {
            loadAuthSettings();
            notificationToastCustom('info', "Changes cancelled", "Information");
        });

        $('#btnCancelPasswordRule').on('click', function(e) {
            loadPasswordRules();
            notificationToastCustom('info', "Changes cancelled", "Information");
        });

        // Update password rules
        $('#updatePasswordRules').on('click', function(e) {
            let rules = [{
                    RuleId: $('#minLength').data('ruleid') || 0,
                    RuleCode: 'MIN_LENGTH',
                    IsEnabled: $('#minLength').is(':checked'),
                    RuleValue: 8
                },
                {
                    RuleId: $('#uppercase').data('ruleid') || 0,
                    RuleCode: 'UPPERCASE',
                    IsEnabled: $('#uppercase').is(':checked'),
                    RuleValue: 1
                },
                {
                    RuleId: $('#lowercase').data('ruleid') || 0,
                    RuleCode: 'LOWERCASE',
                    IsEnabled: $('#lowercase').is(':checked'),
                    RuleValue: 1
                },
                {
                    RuleId: $('#number').data('ruleid') || 0,
                    RuleCode: 'NUMBER',
                    IsEnabled: $('#number').is(':checked'),
                    RuleValue: 1
                },
                {
                    RuleId: $('#specialChar').data('ruleid') || 0,
                    RuleCode: 'SPECIAL_CHAR',
                    IsEnabled: $('#specialChar').is(':checked'),
                    RuleValue: 1
                }
            ];

            console.log('Sending password rules:', rules);

            $.ajax({
                url: '{{ route("updatePasswordRules") }}',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(rules),
                success: function(response) {
                    if (response.result == true) {
                        notificationToastCustom('success', response.message, "Success");
                        // Reload rules to get updated IDs if any
                        loadPasswordRules();
                    } else {
                        notificationToastCustom('error', response.message, "Error");
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Update error:', error);
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        notificationToastCustom('error', xhr.responseJSON.message, "Error");
                    } else if (xhr.status === 419) {
                        notificationToastCustom('error', 'Session expired. Please refresh the page.', 'CSRF Error');
                    } else {
                        notificationToastCustom('error', "Failed to update password rules");
                    }
                }
            });
        });
    });

    function loadPasswordRules() {
        $.ajax({
            url: '{{ route("getPasswordRules") }}',
            type: 'GET',
            success: function(rules) {
                console.log('Loaded password rules:', rules);
                if (rules && rules.length > 0) {
                    rules.forEach(r => {
                        let checkbox = null;

                        switch (r.ruleCode) {
                            case 'MIN_LENGTH':
                                checkbox = $('#minLength');
                                break;
                            case 'UPPERCASE':
                                checkbox = $('#uppercase');
                                break;
                            case 'LOWERCASE':
                                checkbox = $('#lowercase');
                                break;
                            case 'NUMBER':
                                checkbox = $('#number');
                                break;
                            case 'SPECIAL_CHAR':
                                checkbox = $('#specialChar');
                                break;
                        }

                        if (checkbox) {
                            checkbox.prop('checked', r.isEnabled);
                            checkbox.attr('data-ruleid', r.ruleId);
                            checkbox.data('ruleid', r.ruleId);
                        }
                    });
                } else {
                    console.warn('No password rules returned from server');
                    // Initialize with default IDs as 0
                    $('#minLength, #uppercase, #lowercase, #number, #specialChar').each(function() {
                        $(this).data('ruleid', 0);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading password rules:', error);
                notificationToastCustom('error', 'Failed to load password rules');
            }
        });
    }
</script>
@endsection
