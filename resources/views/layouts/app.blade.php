    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>@yield('title') | {{ getAppName() }}</title>
        <!-- Favicon -->
        <link rel="icon" href="{{ getFaviconUrl() }}" type="image/png">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
        <!-- General CSS Files -->

        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/third-party.css') }}">
        {{--        <link rel="stylesheet" type="text/css" href="{{ asset('assets/scss/custom.css') }}"> --}}
        @livewireStyles



        <link rel="stylesheet" type="text/css"
            href="{{ asset('vendor/rappasoft/livewire-tables/css/laravel-livewire-tables.min.css') }}">
        <link rel="stylesheet" type="text/css"
            href="{{ asset('vendor/rappasoft/livewire-tables/css/laravel-livewire-tables-thirdparty.min.css') }}">
        @if (!getLogInUser()->theme_mode)
            <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css?id=$mixID') }}">
            <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins.css') }}">
        @else
            <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins.dark.css') }}">
            <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.dark.css') }}">
            <link rel="stylesheet" type="text/css" href="{{ mix('assets/css/custom-pages-dark.css') }}">
        @endif
        <link rel="stylesheet" type="text/css" href="{{ mix('assets/css/page.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ mix('assets/css/theme.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ mix('assets/css/lazy-loading.css') }}">

        @livewireScripts
        <script src="{{ asset('vendor/rappasoft/livewire-tables/js/laravel-livewire-tables.min.js') }}"></script>
        <script src="{{ asset('vendor/rappasoft/livewire-tables/js/laravel-livewire-tables-thirdparty.min.js') }}"></script>

        <script src="https://js.stripe.com/v3/"></script>
        <script src="https://checkout.razorpay.com/v1/checkout.js" data-turbolinks-eval="false" data-turbo-eval="false">
        </script>

        <script src="{{ asset('assets/js/third-party.js') }}"></script>

        <script data-turbo-eval="false">
            let mobileValidation = "{{ getSuperAdminSettingValue('mobile_validation') }}"
            let phoneNumberRequired = "{{ getSuperAdminSettingValue('phone_number_required') }}"
            let stripe = ''
            @if (getSelectedPaymentGateway('stripe_key'))
                stripe = Stripe('{{ getSelectedPaymentGateway('stripe_key') }}')
            @endif
            let appUrl = "{{ config('app.url') }}"
            let noData = "{{ __('messages.no_data') }}"
            let utilsScript = "{{ asset('assets/js/inttel/js/utils.min.js') }}"
            let defaultProfileUrl = "{{ asset('web/media/avatars/user.png') }}"
            let defaultTemplate = "{{ asset('assets/images/default_cover_image.jpg') }}"
            let defaultServiceIconUrl = "{{ asset('assets/images/default_service.png') }}"
            let defaultProductIconUrl = "{{ asset('images/wp-product.png') }}"
            let defaltNfcLogo = "{{ asset('assets/img/nfc/nfc_default_logo.png') }}"
            let defaultCoverUrl = "{{ asset('assets/images/default_cover_image.jpg') }}"
            let defaultGalleryUrl = "{{ asset('assets/images/default_service.png') }}"
            let defaultAppLogoUrl = "{{ asset(getAppLogo()) }}"
            let defaultFaviconUrl = "{{ getFaviconUrl() }}"
            let getLoggedInUserdata = "{{ getLogInUser() }}"
            window.getLoggedInUserLang = "{{ getCurrentLanguageName() }}"
            let lang = "{{ Illuminate\Support\Facades\Auth::user()->language ?? 'en' }}"
            let getCurrencyCode = "{{ getMaximumCurrencyCode($getIcon = true) }}"
            let sweetAlertIcon = "{{ asset('images/remove.png') }}"
            let sweetCompletedAlertIcon = "{{ asset('images/Alert.png') }}"
            let defaultCountryCodeValue = "{{ getSuperAdminSettingValue('default_country_code') }}"
            let getUniqueVcardUrlAlias = "{{ getUniqueVcardUrlAlias() }}"
            let currencyAfterAmount = "{{ getSuperAdminSettingValue('currency_after_amount') }}"
            let userDateFormate = "{{ getSuperAdminSettingValue('datetime_method') ?? 1 }}";
            let defaultVideoCoverImg = "{{ asset('assets/images/video-icon.png') }}";
            let getLoggedInUsersteps = "{{ getLogInUser()->steps }}"
            let hasActiveSubscription = "{{ hasActiveSubscription() }}"
            let defaultPlaceholderImgUrl = "{{ asset('web/media/logos/placeholder.png') }}"
            let defaultNfcCard = "{{ asset('assets/img/nfc/card_default.png') }}"
            $(document).ready(function() {
                $('[data-bs-toggle="tooltip"]').tooltip()
            })
        </script>
        <script src="{{ asset('vendor/rappasoft/livewire-tables/js/laravel-livewire-tables.min.js') }}"></script>
        <script src="{{ asset('vendor/rappasoft/livewire-tables/js/laravel-livewire-tables-thirdparty.min.js') }}"></script>

        @stack('scripts')
        @routes
        <script src="{{ asset('messages.js?$mixID') }}"></script>
        <script src="{{ mix('assets/js/pages.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/js/shepherd.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/css/shepherd.css" />
        <style>
            :root {
                --bs-primary: {{ getSuperAdminSettingValue('primary_color') ?? '#6571FF' }};
                --bs-secondary: {{ getSuperAdminSettingValue('secondary_color') ?? '#ADB5BD' }};
                --bs-info: {{ getSuperAdminSettingValue('primary_color') ?? '#0099FB' }};
                --bs-primary-rgb: {{ hexToRgb(getSuperAdminSettingValue('primary_color') ?? '#6571FF') }};
                --bs-bg-blur: rgba(var(--bs-primary-rgb), 0.2);
            }


            .btn-primary,
            .btn-outline-primary:hover {
                background-color: var(--bs-primary) !important;
                color: #fff !important;
                border-color: var(--bs-primary) !important;
            }

            .bg-primary {
                background-color: var(--bs-primary) !important;
            }

            .bg-info {
                background-color: var(--bs-primary) !important;
                opacity: 0.7 !important;
            }

            .bg-secondary {
                background-color: var(--bs-secondary) !important;
            }

            .btn-outline-primary {
                color: var(--bs-primary) !important;
                border-color: var(--bs-primary) !important;
            }

            .btn-secondary {
                background-color: var(--bs-secondary) !important;
                border-color: var(--bs-secondary) !important;
                color: #fff !important;
            }

            /* Text */
            .text-primary {
                color: var(--bs-primary) !important;
            }

            .text-secondary {
                color: var(--bs-secondary) !important;
            }

            .text-info {
                color: var(--bs-primary) !important;
            }

            /* Badge */
            .badge-primary {
                background-color: var(--bs-primary) !important;
            }

            .badge-secondary {
                background-color: var(--bs-secondary) !important;
            }

            /* Alerts */
            .alert-primary {
                background-color: var(--bs-primary) !important;
                color: #fff !important;
            }

            .alert-secondary {
                background-color: var(--bs-secondary) !important;
                color: #fff !important;
            }

            .alert-info {
                background-color: var(--bs-primary) !important;
                color: #fff !important;
            }

            .form-check-input:checked {
                background-color: var(--bs-primary) !important;
                border-color: var(--bs-primary) !important;
            }

            .form-check-input[type="checkbox"]:indeterminate {
                background-color: var(--bs-primary) !important;
                border-color: var(--bs-primary) !important;
            }

            .form-switch .form-check-input:checked {
                background-color: var(--bs-primary) !important;
                border-color: var(--bs-primary) !important;
            }

            .btn-info {
                background-color: var(--bs-primary) !important;
                border-color: var(--bs-primary) !important;
            }

            .page-item.active .page-link {
                background-color: var(--bs-primary) !important;
                border-color: var(--bs-primary) !important;
            }

            .header .navbar-nav .nav-item .active.nav-link:after {
                border-bottom-color: var(--bs-primary) !important;
            }

            .header .navbar-nav .nav-item:hover .nav-link:after {
                border-bottom-color: var(--bs-primary) !important;
            }

            .select2-container--bootstrap-5 .select2-dropdown .select2-results__options .select2-results__option.select2-results__option--selected,
            .select2-container--bootstrap-5 .select2-dropdown .select2-results__options .select2-results__option[aria-selected=true]:not(.select2-results__option--highlighted) {
                background-color: var(--bs-primary) !important;
            }

            .nav-tabs .nav-item .nav-link.active:after,
            .nav-tabs .nav-item:hover .nav-link:after {
                border-bottom-color: var(--bs-primary) !important;
            }

            .nav-tabs .nav-item.show .nav-link,
            .nav-tabs .nav-link.active {
                color: var(--bs-primary) !important;
            }

            .nav-pills .nav-link.active,
            .nav-pills .show>.nav-link {
                background-color: var(--bs-primary) !important;
            }

            .btn-group-toggle input[type=radio]:checked+label,
            .btn-group-toggle input[type=radio]:focus+label {
                background-color: var(--bs-primary) !important;
                border: 1px solid var(--bs-primary) !important;
            }

            .aside-menu-container__aside-menu .nav-item .nav-link:hover,
            .aside-menu-container__aside-menu .nav-item.active>.nav-link {
                border-left-color: var(--bs-primary) !important;
                background-color: rgba(var(--bs-primary-rgb), 0.2) !important;
            }

            .blur-bg {
                background-color: rgba(var(--bs-primary-rgb), 0.2) !important;
            }

            .setting-tab .nav-item .nav-link.active {
                border-left-color: var(--bs-primary) !important;
                background-color: rgba(var(--bs-primary-rgb), 0.2) !important;
            }

            .setting-tab .nav-item .nav-link:hover {
                border-left-color: var(--bs-primary) !important;
                background-color: rgba(var(--bs-primary-rgb), 0.2) !important;
            }

            .nav-tabs-1 .nav-item-1 .nav-link-1.active,
            .nav-tabs-1 .nav-item-1 .nav-link-1:hover {
                background-color: rgba(var(--bs-primary-rgb), 0.2) !important;
                border-left-color: var(--bs-primary) !important;
            }

            .img-radio.img-border {
                border: 3px solid var(--bs-primary) !important;

            }

            .template-border {
                border: 3px solid var(--bs-primary) !important;
            }

            .progress-bar {
                background-color: var(--bs-primary) !important;
            }
        </style>


    </head>

    <body>

        @if (getLogInUser()->language != 'ar')
            <div class="d-flex flex-column flex-root vh-100">
                <div class="d-flex flex-row flex-column-fluid">
                    @include('layouts.sidebar')
                    <div class="wrapper d-flex flex-column flex-row-fluid">
                        <div class='container-fluid d-flex align-items-stretch justify-content-between px-0'>
                            @include('layouts.header')
                        </div>
                        <div class='content d-flex flex-column flex-column-fluid pt-7 overflow-scroll'>
                            @yield('header_toolbar')
                            <div class='d-flex flex-wrap flex-column-fluid'>
                                @yield('content')
                            </div>
                        </div>
                        <div class='container-fluid'>
                            @include('layouts.footer')
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (getLogInUser()->language == 'ar')
            <div class="rtl" dir="rtl">
                <div class="d-flex flex-column flex-root vh-100">
                    <div class="d-flex flex-row flex-column-fluid">
                        @include('layouts.sidebar')
                        <div class="wrapper d-flex flex-column flex-row-fluid">
                            <div class='container-fluid d-flex align-items-stretch justify-content-between px-0'>
                                @include('layouts.header')
                            </div>
                            <div class='content d-flex flex-column flex-column-fluid pt-7 overflow-scroll'>
                                @yield('header_toolbar')
                                <div class='d-flex flex-wrap flex-column-fluid'>
                                    @yield('content')
                                </div>
                            </div>
                            <div class='container-fluid'>
                                @include('layouts.footer')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @include('profile.changePassword')
        @include('profile.changelanguage')
        @include('layouts.shepherd-js')
        @if (moduleExists('TwofactorAuthentication'))
            @include('twofactorauthentication::twofactor_authentication.two_factor_authentication')
        @endif
    </body>


    </html>
