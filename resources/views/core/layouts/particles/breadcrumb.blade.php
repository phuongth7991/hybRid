<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <!--begin::Title-->
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{Page::getPageTitle()}}</h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-muted">
                    <a href="{{url('/')}}" class="text-muted text-hover-primary">{{__('Trang chủ')}}</a>
                </li>
                @php
                $breadcrumbs = Page::getBreadcrumb();
                @endphp
                @foreach($breadcrumbs as $breadcrumb)
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">{{$breadcrumb['title']}}</li>
                @endforeach
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
        <!--begin::Actions-->
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            @php
                $actions = Page::getPageAction();
            @endphp
            @foreach($actions as $action)
                <a href="{{$action->uri}}" title="{{$action->title}}" class="btn btn-sm fw-bold {{$action->btnClass}}">
                    @if(!empty($action->icon))
                        <i class="fas {{$action->icon}}"></i>
                    @endif
                    {{$action->title}}
                </a>
            @endforeach
        </div>
        <!--end::Actions-->
    </div>
    <!--end::Toolbar container-->
</div>
