<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ Page::getPageTitle() }}</title>
    {!! Page::head() !!}
    <!-- Scripts -->
</head>
<body id="kt_body" class="app-blank">
<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
            @yield('content')
        </div>
        <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2" style="background-image: url('{{asset('assets/media/misc/auth-bg.png')}}')">
            <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">
                <a href="{{ Page::homeUrl() }}" class="mb-0 mb-lg-12">
                    <img alt="Logo" src="{{ asset('assets/media/logos/default-dark.png') }}" class="h-120px h-lg-150px" />
                </a>
                <img class="d-none d-lg-block mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20" src="{{asset('assets/media/misc/auth-screens.png')}}" alt="" />
                <!--end::Text-->
            </div>
            <!--end::Content-->
        </div>
    </div>
</div>
{!! Page::footer() !!}
</body>
</html>
