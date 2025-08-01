<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />

    @if (checkFeature('seo') && $whatsappStore->site_title && $whatsappStore->home_title)
        <title>{{ $whatsappStore->home_title }} | {{ $whatsappStore->site_title }}</title>
    @else
        <title>{{ $whatsappStore->store_name }} | {{ getAppName() }}</title>
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="{{ $whatsappStore->logo_url }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (checkFeature('seo'))
        @if ($whatsappStore->meta_description)
            <meta name="description" content="{{ $whatsappStore->meta_description }}">
        @endif
        @if ($whatsappStore->meta_keyword)
            <meta name="keywords" content="{{ $whatsappStore->meta_keyword }}">
        @endif
    @else
        <meta name="description" content="{{ $whatsappStore->description }}">
        <meta name="keywords" content="">
    @endif
    <!-- PWA  -->
    <meta name="theme-color" content="#6777ef" />
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="{{ asset('pwa/1.json') }}">

    <link href="{{ asset('front/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('assets/css/whatsappp_store/grocery_store.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/slider/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slider/css/slick-theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/third-party.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins.css') }}">
    <link rel="stylesheet" href="{{ mix('assets/css/whatsappp_store/custom.css') }}" />
</head>

<body>
    <div class="main-content mx-auto w-100 overflow-hidden d-flex flex-column justify-content-between {{ getLocalLanguage() == 'ar' ? 'rtl' : '' }}" @if(getLanguage($whatsappStore->default_language) == 'Arabic') dir="rtl" @endif>
        <div class="bg-vector bg-vector-1 text-start">
            <img src="{{ asset('assets/img/whatsapp_stores/grocery_store/bg-vector-1.png') }}" alt="vector"
                loading="lazy" />
        </div>
        <div class="bg-vector bg-vector-2 text-end">
            <img src="{{ asset('assets/img/whatsapp_stores/grocery_store/bg-vector-2.png') }}" alt="vector"
                class="ms-auto" loading="lazy" />
        </div>
        <div class="bg-vector bg-vector-3 text-start">
            <img src="{{ asset('assets/img/whatsapp_stores/grocery_store/bg-vector-3.png') }}" alt="vector"
                loading="lazy" />
        </div>
        <div class="bg-vector bg-vector-4 text-end">
            <img src="{{ asset('assets/img/whatsapp_stores/grocery_store/bg-vector-4.png') }}" alt="vector"
                class="ms-auto" loading="lazy" />
        </div>
        <div class="bg-vector bg-vector-5 text-start">
            <img src="{{ asset('assets/img/whatsapp_stores/grocery_store/bg-vector-5.png') }}" alt="vector"
                loading="lazy" />
        </div>
        <div class="bg-vector bg-vector-6 text-end">
            <img src="{{ asset('assets/img/whatsapp_stores/grocery_store/bg-vector-6.png') }}" alt="vector"
                class="ms-auto" loading="lazy" />
        </div>
        <div>
            <header class="px-4 py-3">
                <nav class="navbar navbar-expand-lg position-relative">
                    <div class="container-fluid p-0">
                        <div class="d-flex align-items-center gap-3">
                            <a class="navbar-brand p-0 m-0"
                                href="{{ route('whatsapp.store.show', $whatsappStore->url_alias) }}">
                                <img src="{{ $whatsappStore->logo_url }}" alt="logo"
                                    class="w-100 h-100 object-fit-cover" />
                            </a>
                            <span class="fw-5 fs-18"><a
                                    href="{{ route('whatsapp.store.show', $whatsappStore->url_alias) }}"
                                    style="color: #212529 ">{{ $whatsappStore->store_name }}</a></span>
                        </div>

                        <div class="d-flex align-items-center gap-lg-4 gap-sm-3 gap-2">
                            <div class="language-dropdown position-relative">
                                <button class="dropdown-btn position-relative" id="dropdownMenuButton"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    @if (array_key_exists(getLocalLanguage() ?? 'en', \App\Models\User::FLAG))
                                        <img class="flag" alt="flag"
                                            src="{{ asset(\App\Models\User::FLAG[getLocalLanguage() ?? 'en']) }}"
                                            loading="lazy" />
                                    @endif
                                    {{ strtoupper(getLocalLanguage() ?? 'EN') }}
                                </button>
                                <svg class="dropdown-arrow" xmlns="http://www.w3.org/2000/svg" width="14"
                                    height="8" viewBox="0 0 18 10" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M0.615983 0.366227C0.381644 0.600637 0.25 0.918522 0.25 1.24998C0.25 1.58143 0.381644 1.89932 0.615983 2.13373L8.11598 9.63373C8.35039 9.86807 8.66828 9.99971 8.99973 9.99971C9.33119 9.99971 9.64907 9.86807 9.88348 9.63373L17.3835 2.13373C17.6112 1.89797 17.7372 1.58222 17.7343 1.25448C17.7315 0.92673 17.6 0.613214 17.3683 0.381454C17.1365 0.149694 16.823 0.0182329 16.4952 0.0153849C16.1675 0.0125369 15.8517 0.13853 15.616 0.366227L8.99973 6.98248L2.38348 0.366227C2.14907 0.131889 1.83119 0.000244141 1.49973 0.000244141C1.16828 0.000244141 0.850393 0.131889 0.615983 0.366227Z"
                                        fill="black" />
                                </svg>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @foreach (getAllLanguageWithFullData() as $language)
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)" id="languageName"
                                                data-name="{{ $language->iso_code }}">

                                                @if (array_key_exists($language->iso_code, \App\Models\User::FLAG))
                                                    <img class="flag" alt="flag"
                                                        src="{{ asset(\App\Models\User::FLAG[$language->iso_code]) }} "
                                                        loading="lazy" />
                                                @else
                                                    @if (count($language->media) != 0)
                                                        <img src="{{ $language->image_url }}" class="me-1"
                                                            loading="lazy" />
                                                    @else
                                                        <i class="fa fa-flag fa-xl me-3 text-danger"
                                                            aria-hidden="true"></i>
                                                    @endif
                                                @endif
                                                {{ strtoupper($language->iso_code) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <button class="add-to-cart-btn d-flex align-items-center justify-content-center position-relative"
                                id="addToCartViewBtn">

                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                    viewBox="0 0 30 30" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M20.0048 9.03985C20.0048 9.27694 19.9106 9.50433 19.7429 9.67198C19.5753 9.83964 19.3479 9.93382 19.1108 9.93382C18.8737 9.93382 18.6463 9.83964 18.4787 9.67198C18.311 9.50433 18.2168 9.27694 18.2168 9.03985V7.2519C18.2168 6.38254 17.8715 5.54879 17.2567 4.93406C16.642 4.31934 15.8083 3.97399 14.9389 3.97399C14.0696 3.97399 13.2358 4.31934 12.6211 4.93406C12.0063 5.54879 11.661 6.38254 11.661 7.2519V9.03985C11.661 9.27694 11.5668 9.50433 11.3992 9.67198C11.2315 9.83964 11.0041 9.93382 10.767 9.93382C10.5299 9.93382 10.3025 9.83964 10.1349 9.67198C9.96723 9.50433 9.87305 9.27694 9.87305 9.03985V7.2519C9.87305 5.90835 10.4068 4.61982 11.3568 3.66979C12.3068 2.71976 13.5954 2.18604 14.9389 2.18604C16.2825 2.18604 17.571 2.71976 18.521 3.66979C19.471 4.61982 20.0048 5.90835 20.0048 7.2519V9.03985Z"
                                        fill="#292929" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M23.6898 10.6489L24.6434 24.9525C24.6674 25.3188 24.616 25.6862 24.4924 26.0318C24.3688 26.3775 24.1756 26.6942 23.9249 26.9623C23.6741 27.2304 23.371 27.4442 23.0343 27.5905C22.6977 27.7369 22.3346 27.8127 21.9675 27.8132H7.90939C7.54218 27.813 7.17892 27.7375 6.84209 27.5913C6.50526 27.445 6.20204 27.2312 5.95119 26.963C5.70034 26.6948 5.5072 26.378 5.38374 26.0322C5.26028 25.6864 5.20912 25.3189 5.23342 24.9525L6.187 10.6489C6.23235 9.97006 6.534 9.33384 7.03086 8.86907C7.52771 8.40431 8.18262 8.14575 8.86296 8.14575H21.0139C21.6942 8.14575 22.3491 8.40431 22.846 8.86907C23.3428 9.33384 23.6445 9.97006 23.6898 10.6489ZM17.9017 13.4238C17.6351 13.984 17.2153 14.4571 16.6909 14.7884C16.1664 15.1197 15.5588 15.2955 14.9384 15.2955C14.3181 15.2955 13.7105 15.1197 13.186 14.7884C12.6615 14.4571 12.2417 13.984 11.9752 13.4238C11.9248 13.3177 11.854 13.2227 11.7668 13.144C11.6797 13.0653 11.5779 13.0045 11.4673 12.9652C11.3566 12.9258 11.2393 12.9086 11.1221 12.9146C11.0048 12.9206 10.8899 12.9496 10.7838 13C10.6778 13.0504 10.5827 13.1212 10.504 13.2084C10.4253 13.2955 10.3646 13.3973 10.3252 13.508C10.2859 13.6186 10.2687 13.7359 10.2747 13.8532C10.2807 13.9704 10.3097 14.0854 10.3601 14.1914C10.7706 15.0583 11.4188 15.7908 12.2293 16.3037C13.0398 16.8166 13.9793 17.0889 14.9384 17.0889C15.8976 17.0889 16.837 16.8166 17.6475 16.3037C18.458 15.7908 19.1062 15.0583 19.5168 14.1914C19.5672 14.0854 19.5962 13.9704 19.6022 13.8532C19.6082 13.7359 19.591 13.6186 19.5516 13.508C19.5123 13.3973 19.4515 13.2955 19.3728 13.2084C19.2942 13.1212 19.1991 13.0504 19.093 13C18.987 12.9496 18.872 12.9206 18.7548 12.9146C18.6375 12.9086 18.5202 12.9258 18.4096 12.9652C18.2989 13.0045 18.1972 13.0653 18.11 13.144C18.0229 13.2226 17.9521 13.3177 17.9017 13.4238Z"
                                        fill="#292929" />
                                </svg>
                                <div
                                    class="position-absolute product-count-badge count-product  badge rounded-pill bg-danger">

                                </div>
                            </button>
                        </div>
                    </div>
                </nav>
            </header>

            <div class="banner-section position-relative px-4">
                <div class="banner-img">
                    <img src="{{ $whatsappStore->cover_url }}" loading="lazy" class="w-100 h-100 object-fit-cover"
                        alt="banner" />
                </div>
            </div>
            <div class="category-section px-4 pt-30 position-relative">
                <div class="section-heading mb-4">
                    <h2>{{ __('messages.whatsapp_stores_templates.choos_your_category') }}</h2>
                </div>
                <div class="category-slider">
                    @foreach ($whatsappStore->categories as $category)
                        <a href="{{ route('whatsapp.store.products', ['alias' => $whatsappStore->url_alias, 'category' => $category->id]) }}"
                            style="color: #212529 !important;" class="text-black">
                            <div>
                                <div class="category-item">
                                    <div class="category-img">
                                        <img src="{{ $category->image_url }}" alt="category"
                                            class="w-100 h-100 object-fit-cover" loading="lazy" />
                                    </div>
                                    <h3 class="fs-18 fw-5 mb-0 text-black category-item-grocery-desc">
                                        {{ $category->name }}</h3>
                                </div>
                            </div>
                        </a>
                    @endforeach

                </div>
                @if ($whatsappStore->categories->count() == 0)
                <div class="text-center mb-5 mt-3">
                    <h3 class="fs-20 fw-6 mb-0">
                        {{ __('messages.whatsapp_stores_templates.category_not_found') }}</h3>
                </div>
            @endif
            </div>
            <div class="product-section px-4 pt-30 position-relative">
                <div class="section-heading">
                    <h2> {{ __('messages.whatsapp_stores_templates.choose_your_product') }}</h2>
                </div>
                <div class="row mb-40 product-cards">
                    @foreach ($whatsappStore->products()->latest()->take(8)->get() as $product)
                        <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
                            <div class="product-card h-100 d-flex flex-column justify-content-between">
                                <a href="{{ route('whatsapp.store.product.details', [$whatsappStore->url_alias, $product->id]) }}"
                                    class="d-flex text-black flex-column h-100">
                                    <div class="product-img bg-yellow">
                                        <img src="{{ $product->images_url[0] ?? '' }}" alt="product"
                                            class="w-100 h-100 object-fit-cover product-image" product-image
                                            loading="lazy" />
                                    </div>

                                <div class="flex-grow-1">
                                    <div
                                        class="product-details text-center d-flex flex-column justify-content-between h-100">
                                            <div>
                                                <h5 class="fs-22 fw-6 mb-0 product-name">{{ $product->name }}</h5>
                                                <p class="fs-16 fw-5 mb-1 text-gray-200 product-category">
                                                    {{ $product->category->name }}</p>
                                                <p class="fs-18 fw-6 mb-2">
                                                    <span class="currency_icon">
                                                        {{ $product->currency->currency_icon }}</span>
                                                    <span class="selling_price">{{ $product->selling_price }}</span>
                                                    @if ($product->net_price)
                                                        <del class="fs-14 fw-5 text-gray-200">{{ $product->currency->currency_icon }}
                                                            {{ $product->net_price }}</del>
                                                    @endif
                                                </p>
                                            </div>
                                            <input type="hidden" value="{{ $product->available_stock }}"
                                          class="available-stock">
                                    </div>
                                </div>
                            </a>
                                <button data-id="{{ $product->id }}"
                                    class="@if($product->available_stock == 0) disabled @endif mb-2 btn btn-primary d-flex justify-content-center align-items-center mx-auto gap-2 add-to-cart-w-140px addToCartBtn">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 30 30" fill="none">
                                            <path
                                                d="M4.06864 11.7857L6.75321 23.5389C6.8827 24.1133 7.20391 24.6265 7.66397 24.994C8.12403 25.3615 8.69552 25.5614 9.28435 25.5609H15.5766C15.804 25.5609 16.022 25.4706 16.1827 25.3098C16.3435 25.1491 16.4338 24.931 16.4338 24.7037C16.4338 24.4764 16.3435 24.2584 16.1827 24.0976C16.022 23.9369 15.804 23.8466 15.5766 23.8466H9.28435C8.87292 23.8466 8.51807 23.5603 8.42378 23.154L5.7375 11.3974C5.70906 11.2683 5.70992 11.1345 5.74001 11.0058C5.7701 10.8771 5.82866 10.7567 5.91138 10.6536C5.99409 10.5505 6.09887 10.4672 6.218 10.41C6.33713 10.3527 6.46759 10.3228 6.59978 10.3226H8.78378V12.3309C8.78378 12.5582 8.87409 12.7762 9.03483 12.9369C9.19558 13.0977 9.4136 13.188 9.64092 13.188C9.86825 13.188 10.0863 13.0977 10.247 12.9369C10.4078 12.7762 10.4981 12.5582 10.4981 12.3309V10.3217H20.1101V12.3309C20.1101 12.5582 20.2004 12.7762 20.3611 12.9369C20.5219 13.0977 20.7399 13.188 20.9672 13.188C21.1945 13.188 21.4126 13.0977 21.5733 12.9369C21.734 12.7762 21.8244 12.5582 21.8244 12.3309V10.3217H24.0084C24.1412 10.3219 24.2723 10.3521 24.3919 10.4099C24.5115 10.4678 24.6166 10.5518 24.6992 10.6558C24.7819 10.7598 24.8401 10.8811 24.8695 11.0107C24.8989 11.1403 24.8987 11.2748 24.8689 11.4043L22.9146 19.956C22.8896 20.0657 22.8864 20.1794 22.9053 20.2903C22.9241 20.4013 22.9647 20.5075 23.0246 20.6028C23.0845 20.6981 23.1625 20.7807 23.2543 20.8458C23.3462 20.911 23.4499 20.9574 23.5596 20.9824C23.6694 21.0075 23.783 21.0107 23.894 20.9918C24.0049 20.9729 24.1111 20.9324 24.2064 20.8725C24.3017 20.8126 24.3843 20.7345 24.4495 20.6427C24.5146 20.5509 24.561 20.4472 24.5861 20.3374L26.5378 11.7917C26.6282 11.4129 26.6312 11.0184 26.5465 10.6382C26.4619 10.258 26.2918 9.90213 26.0492 9.59743C25.8066 9.28838 25.4969 9.03862 25.1434 8.86708C24.79 8.69555 24.4021 8.60676 24.0092 8.60743H21.7532C21.3075 5.44457 18.5886 3 15.3041 3C12.0195 3 9.3015 5.44371 8.85492 8.60743H6.59978C5.80007 8.60743 5.05692 8.96829 4.55978 9.59743C4.31745 9.90112 4.14732 10.2559 4.06223 10.635C3.97715 11.0141 3.97934 11.4076 4.06864 11.7857ZM15.3041 4.71429C16.4197 4.71598 17.5 5.10517 18.3604 5.8153C19.2208 6.52543 19.8077 7.5124 20.0209 8.60743H10.5872C10.8004 7.5124 11.3874 6.52543 12.2477 5.8153C13.1081 5.10517 14.1885 4.71598 15.3041 4.71429Z"
                                                fill="currentColor" />
                                            <path
                                                d="M20.9296 20.7959C20.7023 20.7959 20.4843 20.8862 20.3235 21.047C20.1628 21.2077 20.0725 21.4257 20.0725 21.653V23.0408H18.6848C18.4575 23.0408 18.2394 23.1311 18.0787 23.2918C17.9179 23.4526 17.8276 23.6706 17.8276 23.8979C17.8276 24.1252 17.9179 24.3432 18.0787 24.504C18.2394 24.6647 18.4575 24.755 18.6848 24.755H20.0734V26.1428C20.0734 26.3701 20.1637 26.5881 20.3244 26.7488C20.4851 26.9096 20.7032 26.9999 20.9305 26.9999C21.1578 26.9999 21.3758 26.9096 21.5366 26.7488C21.6973 26.5881 21.7876 26.3701 21.7876 26.1428V24.7542H23.1754C23.4027 24.7542 23.6207 24.6639 23.7814 24.5031C23.9422 24.3424 24.0325 24.1244 24.0325 23.897C24.0325 23.6697 23.9422 23.4517 23.7814 23.291C23.6207 23.1302 23.4027 23.0399 23.1754 23.0399H21.7868V21.653C21.7868 21.4257 21.6965 21.2077 21.5357 21.047C21.375 20.8862 21.157 20.7959 20.9296 20.7959Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                    {{ __('messages.whatsapp_stores_templates.add_to_cart') }}
                                </button>
                            </div>

                        </div>
                    @endforeach
                    @if ($whatsappStore->products->count() == 0)
                        <div class="text-center mb-5 mt-3">
                            <h3 class="fs-20 fw-6 mb-0 text-break">
                                {{ __('messages.whatsapp_stores_templates.product_not_found') }}</h3>
                        </div>
                    @endif
                </div>
                @if ($whatsappStore->products->count() > 0)
                    <div class="text-center">
                        <a href="{{ route('whatsapp.store.products', $whatsappStore->url_alias) }}"
                            class="btn view-more-btn fs-18 d-flex align-items-center justify-content-center mx-auto gap-4">
                            <span class="text">{{ __('messages.whatsapp_stores_templates.view_more') }}</span>
                            <span class="arrow-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="12"
                                    viewBox="0 0 16 12" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M8.4325 0.383616C8.51346 0.290708 8.61194 0.214673 8.72231 0.15986C8.83268 0.105046 8.95277 0.0725301 9.07572 0.064171C9.19866 0.055812 9.32205 0.0717745 9.43883 0.111145C9.5556 0.150515 9.66346 0.212521 9.75625 0.293616L15.4913 5.29362C15.5921 5.38161 15.6728 5.49017 15.7282 5.61199C15.7836 5.7338 15.8122 5.86606 15.8122 5.99987C15.8122 6.13367 15.7836 6.26593 15.7282 6.38775C15.6728 6.50956 15.5921 6.61812 15.4913 6.70612L9.75625 11.7061C9.66396 11.79 9.55586 11.8546 9.4383 11.8961C9.32073 11.9377 9.19605 11.9554 9.07157 11.9481C8.94708 11.9409 8.82528 11.9089 8.71331 11.8541C8.60134 11.7992 8.50143 11.7226 8.41945 11.6286C8.33747 11.5346 8.27505 11.4253 8.23586 11.3069C8.19666 11.1885 8.18148 11.0635 8.19119 10.9392C8.2009 10.8149 8.23532 10.6938 8.29242 10.5829C8.34953 10.472 8.42817 10.3737 8.52375 10.2936L12.3738 6.93737H1.125C0.87636 6.93737 0.637903 6.83859 0.462087 6.66278C0.286272 6.48696 0.1875 6.24851 0.1875 5.99987C0.1875 5.75123 0.286272 5.51277 0.462087 5.33695C0.637903 5.16114 0.87636 5.06237 1.125 5.06237H12.3725L8.5225 1.70612C8.33524 1.54265 8.22056 1.3115 8.20368 1.0635C8.1868 0.815498 8.26911 0.570946 8.4325 0.383616Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
        @if (isset($enable_pwa) && $enable_pwa == 1 && !isiOSDevice())
            <div class="mt-0">
                <div class="pwa-support d-flex align-items-center justify-content-center">
                    <div>
                        <h1 class="text-start pwa-heading">{{ __('messages.pwa.add_to_home_screen') }}</h1>
                        <p class="text-start pwa-text text-dark fs-16 fw-5">{{ __('messages.pwa.pwa_description') }} </p>
                        <div class="text-end d-flex">
                            <button id="installPwaBtn"
                                class="pwa-install-button w-50 mb-1 btn">{{ __('messages.pwa.install') }}
                            </button>
                            <button
                                class= "pwa-cancel-button w-50  pwa-close btn btn-secondary mb-1 {{ getLocalLanguage() == 'ar' ? 'me-2' : 'ms-2' }}">{{ __('messages.common.cancel') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @include('whatsapp_stores.templates.order_modal')
        @include('whatsapp_stores.templates.cart_modal')
        <footer class="position-relative">
            <div class="text-center fw-5 fs-16 fw-medium text-white">
                <div class="mb-2">
                    <i class="fas fa-map-marker-alt"></i> {{ $whatsappStore->address }}
                </div>
                <div>
                    © Copyright {{ now()->year }} {{ env('APP_NAME') }}. All Rights Reserved.
                </div>
            </div>
        </footer>
    </div>
</body>
<script>
    let vcardAlias = "{{ $whatsappStore->url_alias }}";
    let languageChange = "{{ url('language') }}";
    let lang = "{{ getLocalLanguage() ?? 'en' }}";
    let isRtl = "{{ getLocalLanguage() == 'ar' ? 'true' : 'false' }}" === "true";
</script>
<script src="{{ asset('messages.js?$mixID') }}"></script>
<script src="{{ asset('assets/js/intl-tel-input/build/intlTelInput.js') }}"></script>
<script src="{{ asset('assets/js/vcard11/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/js/bootstrap.bundle.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/front-third-party-vcard11.js') }}"></script>
<script src="{{ mix('assets/js/custom/helpers.js') }}"></script>
<script src="{{ asset('assets/js/slider/js/slick.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/js/whatsapp_store_template.js') }}"></script>
<script>
    document.querySelectorAll(".dropdown-item").forEach((item) => {
        item.addEventListener("click", function() {
            const selectedLang = item.getAttribute("data-lang");
            const selectedFlag = item.querySelector("img").src;
            const selectedText = item.textContent.trim();
            document.getElementById(
                "dropdownMenuButton"
            ).innerHTML = `<img src="${selectedFlag}" class="flag" alt="flag"> ${selectedText}`;
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".category-slider").slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            dots: false,
            rtl: isRtl,
            arrows: true,
            prevArrow: '<button class="slide-arrow prev-arrow"><svg xmlns="http://www.w3.org/2000/svg" width="8" height="14" viewBox="0 0 8 14" fill="none"><path d="M0 6.99998C0 6.74907 0.0960374 6.49819 0.287709 6.3069L6.32224 0.287199C6.70612 -0.0957328 7.3285 -0.0957328 7.71221 0.287199C8.09593 0.669975 8.09593 1.29071 7.71221 1.67367L2.37252 6.99998L7.71203 12.3263C8.09574 12.7092 8.09574 13.3299 7.71203 13.7127C7.32831 14.0958 6.70593 14.0958 6.32206 13.7127L0.287522 7.69306C0.09582 7.50167 0 7.25079 0 6.99998Z" fill="currentColor" /></svg></button>',
            nextArrow: '<button class="slide-arrow next-arrow"><svg xmlns="http://www.w3.org/2000/svg" width="8" height="14" viewBox="0 0 8 14" fill="none"><path d="M8 7.00002C8 7.25093 7.90396 7.50181 7.71229 7.6931L1.67776 13.7128C1.29388 14.0957 0.671503 14.0957 0.287787 13.7128C-0.095929 13.33 -0.095929 12.7093 0.287787 12.3263L5.62748 7.00002L0.287973 1.67369C-0.0957425 1.29076 -0.0957425 0.670084 0.287973 0.287339C0.67169 -0.0957785 1.29407 -0.0957785 1.67794 0.287339L7.71248 6.30694C7.90418 6.49833 8 6.74921 8 7.00002Z" fill="currentColor"/></svg></button>',
            responsive: [{
                    breakpoint: 1129,
                    settings: {
                        slidesToShow: 4,
                    },
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 3,
                    },
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                    },
                },
                {
                    breakpoint: 575,
                    settings: {
                        arrows: false,
                        dots: true,
                        slidesToShow: 2,
                    },
                },
                {
                    breakpoint: 436,
                    settings: {
                        slidesToShow: 1,
                        arrows: false,
                        dots: true,
                    },
                },
            ],
        });
    });
</script>
<script>
    let deferredPrompt = null;
    window.addEventListener("beforeinstallprompt", (event) => {
        /* event.preventDefault(); */
        deferredPrompt = event;
        document.getElementById("installPwaBtn").style.display = "block";
    });
    document.getElementById("installPwaBtn").addEventListener("click", async () => {
        if (deferredPrompt) {
            deferredPrompt.prompt();
            await deferredPrompt.userChoice;
            deferredPrompt = null;
        }
    });
</script>
<script>
    let defaultCountryCodeValue = "{{ getSuperAdminSettingValue('default_country_code') }}"
</script>

</html>
