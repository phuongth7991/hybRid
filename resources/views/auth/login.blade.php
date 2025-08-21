@extends('core.layouts.authentication')

@section('content')
<!--begin::Form-->
<div class="d-flex flex-center flex-column flex-lg-row-fluid">
    <!--begin::Wrapper-->
    <div class="w-lg-500px p-10">
        <!--begin::Form-->
        <form class="form w-100" id="kt_sign_in_form" method="POST" action="{{ route('login') }}">
            @csrf
            <!--begin::Heading-->
            <div class="text-center mb-11">
                <h1 class="text-dark fw-bolder mb-3">{{__('Đăng nhập')}}</h1>
                @if(config('auth.social_login_enabled'))
                    <div class="text-gray-500 fw-semibold fs-6">{{__('Đăng nhập bằng tài khoản mạng xã hội')}}</div>
                @endif
            </div>
            <!--begin::Heading-->
            @if(config('auth.social_login_enabled'))
                <!--begin::Login options-->
                <div class="row g-3 mb-9">
                    <!--begin::Col-->
                    <div class="col-md-6">
                        <!--begin::Google link=-->
                        <a href="#" class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
                            <img alt="Logo" src="{{asset('assets/media/svg/brand-logos/google-icon.svg')}}" class="h-15px me-3" />{{__('Đăng nhập bằng Google')}}</a>
                        <!--end::Google link=-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-6">
                        <!--begin::Google link=-->
                        <a href="#" class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
                            <img alt="Logo" src="{{asset('assets/media/svg/brand-logos/apple-black.svg')}}" class="theme-light-show h-15px me-3" />
                            <img alt="Logo" src="{{asset('assets/media/svg/brand-logos/apple-black-dark.svg')}}" class="theme-dark-show h-15px me-3" />{{__('Đăng nhập bằng Apple')}}</a>
                        <!--end::Google link=-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Login options-->
                <div class="separator separator-content my-14">
                    <span class="w-125px text-gray-500 fw-semibold fs-7">{{__('Hoặc đăng nhập bằng Email')}}</span>
                </div>
            @endif
            <div class="fv-row mb-8">
                <input type="text" placeholder="{{__('Địa chỉ email')}}" name="email" value="{{ old('email') }}"  required autocomplete="email" autofocus class="form-control bg-transparent @error('email') is-invalid @enderror" />
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="fv-row mb-3">
                <input id="password" type="password" class="form-control bg-transparent @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('email')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="d-grid mb-10">
                <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                    <!--begin::Indicator label-->
                    <span class="indicator-label">{{__('Đăng nhập ngay')}}</span>
                </button>
            </div>
            @if(config('auth.register_enabled'))
                <div class="text-gray-500 text-center fw-semibold fs-6">{{ __('Bạn chưa có tài khoản?') }}
                    <a href="{{ route('register') }}" class="link-primary">{{ __('Đăng ký')  }}</a>
                </div>
            @endif
        </form>
        <!--end::Form-->
    </div>
    <!--end::Wrapper-->
</div>
@if(config('app.multiple_language_enabled'))
    <div class="w-lg-500px d-flex flex-stack px-10 mx-auto">
        <!--begin::Languages-->
        <div class="me-10">
            <!--begin::Toggle-->
            <button class="btn btn-flex btn-link btn-color-gray-700 btn-active-color-primary rotate fs-base" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                <img data-kt-element="current-lang-flag" class="w-20px h-20px rounded me-3" src="assets/media/flags/united-states.svg" alt="" />
                <span data-kt-element="current-lang-name" class="me-1">English</span>
                <i class="ki-duotone ki-down fs-5 text-muted rotate-180 m-0"></i>
            </button>
            <!--end::Toggle-->
            <!--begin::Menu-->
            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-4 fs-7" data-kt-menu="true" id="kt_auth_lang_menu">
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <a href="#" class="menu-link d-flex px-5" data-kt-lang="English">
										<span class="symbol symbol-20px me-4">
											<img data-kt-element="lang-flag" class="rounded-1" src="assets/media/flags/united-states.svg" alt="" />
										</span>
                        <span data-kt-element="lang-name">English</span>
                    </a>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <a href="#" class="menu-link d-flex px-5" data-kt-lang="Spanish">
										<span class="symbol symbol-20px me-4">
											<img data-kt-element="lang-flag" class="rounded-1" src="assets/media/flags/spain.svg" alt="" />
										</span>
                        <span data-kt-element="lang-name">Spanish</span>
                    </a>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <a href="#" class="menu-link d-flex px-5" data-kt-lang="German">
										<span class="symbol symbol-20px me-4">
											<img data-kt-element="lang-flag" class="rounded-1" src="assets/media/flags/germany.svg" alt="" />
										</span>
                        <span data-kt-element="lang-name">German</span>
                    </a>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <a href="#" class="menu-link d-flex px-5" data-kt-lang="Japanese">
										<span class="symbol symbol-20px me-4">
											<img data-kt-element="lang-flag" class="rounded-1" src="assets/media/flags/japan.svg" alt="" />
										</span>
                        <span data-kt-element="lang-name">Japanese</span>
                    </a>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <a href="#" class="menu-link d-flex px-5" data-kt-lang="French">
										<span class="symbol symbol-20px me-4">
											<img data-kt-element="lang-flag" class="rounded-1" src="assets/media/flags/france.svg" alt="" />
										</span>
                        <span data-kt-element="lang-name">French</span>
                    </a>
                </div>
                <!--end::Menu item-->
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Languages-->
        <!--begin::Links-->
        <div class="d-flex fw-semibold text-primary fs-base gap-5">
            <a href="../../demo1/dist/pages/team.html" target="_blank">Terms</a>
            <a href="../../demo1/dist/pages/pricing/column.html" target="_blank">Plans</a>
            <a href="../../demo1/dist/pages/contact.html" target="_blank">Contact Us</a>
        </div>
        <!--end::Links-->
    </div>
@endif
@endsection
