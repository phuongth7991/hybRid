@extends('core.layouts.app')
@section('content')
    <div class="card card-flush h-lg-100">
        <script>
            var data = {!! @json_encode(request()->all()) !!}
        </script>
        @if(Session::has('message'))
            @php
                $type = Session::get('type');
            @endphp
            <div class="alert alert-{{$type}} d-flex align-items-center p-5">
                <i class="ki-duotone ki-shield-tick fs-2hx text-{{$type}} me-4"><span class="path1"></span><span class="path2"></span></i>
                <div class="d-flex flex-column">
                    @if(Session::get('type') == 'success')
                        <h4 class="mb-1 text-dark">{{__('Thành công')}}</h4>
                    @else
                        <h4 class="mb-1 text-dark">{{__('Thất bại')}}</h4>
                    @endif
                    <span>{{Session::get('message')}}</span>
                </div>
            </div>
        @endif
        <div class="card-body">
        {!! form($form) !!}
        </div>
    </div>
@endsection
