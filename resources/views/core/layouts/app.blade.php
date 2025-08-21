<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ Page::getPageTitle() }}</title>
    {!! Page::head() !!}
    @stack('styles')
    <style>
        .image-input-placeholder {
            background-image: url('{{asset('svg/avatars/blank.svg')}}');
        }

        [data-bs-theme="dark"] .image-input-placeholder {
            background-image: url('{{asset('svg/avatars/blank-dark.svg')}}');
        }
    </style>
    <!-- Scripts -->
</head>
<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
      data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
      data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
      data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
        @include('core.layouts.particles.header')
        <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
            @include('core.layouts.particles.sidebar')
            <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                <div class="d-flex flex-column flex-column-fluid">
                    @if(!Page::isHideBreadCrumb())
                        @include('core.layouts.particles.breadcrumb')
                    @endif
                    <div class="app-content flex-column-fluid" id="kt_app_content">
                        <div id="kt_app_content_container"
                             class="app-container {{ $containerClass ?? 'container-fluid' }}">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Page::footer() !!}
@stack('scripts')
</body>
</html>
