@extends('core.layouts.app')
@section('content')
    <script>
        var cdnUrl = "{{SystemHelper::getCdnUrl()}}";
    </script>
    @if(!empty($filter))
        <div class="card card-flush h-lg-100 mb-5" id="filter-group">
            <div class="card-body">
                <form action="" method="GET" id="filter-form">
                    <div class="row g-3 align-items-center">
                        {!! $filter !!}
                        <div class="col-auto">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button class="btn btn-primary" id="search" type="submit"><i class="fas fa-search"></i>
                                    Lọc
                                </button>
                                @if($enabledExport)
                                    <button role="button" id="export" class="btn btn-success" type="button"><i
                                            class="fas fa-file-excel"></i>Export
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
    @if(Session::has('message'))
        @php
            $type = Session::get('type');
        @endphp
        <div class="alert alert-{{$type}} d-flex align-items-center p-5">
            <i class="ki-duotone ki-shield-tick fs-2hx text-{{$type}} me-4"><span class="path1"></span><span
                    class="path2"></span></i>
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
    <div class="card card-flush h-lg-100">
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('/vendor/datatables/buttons.server-side.js')}}"></script>
    <script>
        setTimeout(() => {
            $('.alert-{{Session::get('type')}}').addClass("d-none");
        }, 5000);

        function renderBtnAction(item, routePath, routeName, actEdit, actDel) {
            var link = '/' + routePath + '/' + item.id + '/edit';
            html = '';
            if (actEdit) {
                html += '<a class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Sửa" href="' + link + '">' +
                    '<i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>' +
                    '</a>&nbsp;&nbsp;';
            }

            if (actDel) {
                html += '<a class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="Xóa" onclick="removeItem(' + item.id + ', \'' + routePath + '\')" href="javascript:void(0);">' +
                    '<i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>' +
                    '</a>';
            }

            return html;
        }

        function removeItem(id, routerName) {
            Swal.fire({
                title: '{{__('Bạn chắc chắn xóa bản ghi này ?')}}',
                icon: 'warning',
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/${routerName}/${id}`).then(function (res) {
                        window.LaravelDataTables.kt_datatable_horizontal_scroll.ajax.reload();
                    });
                }
            })
            return false;
        }

        $('#export').click(function () {
            var form = $('#filter-form');
            var query = form.serialize() + '&export=1';
            window.open(window.location.pathname + '?' + query, '_blank');
        });

        $('#search').click(function () {
            var query = $('#filter-form').serialize();
            window.history.pushState({}, '', window.location.pathname + '?' + query);
            window.LaravelDataTables.kt_datatable_horizontal_scroll.ajax.url(window.location.pathname + '?' + query).load();
            return false;
        });

        $('#filter-form').on('submit', function () {
            var query = $('#filter-form').serialize();
            window.history.pushState({}, '', window.location.pathname + '?' + query);
            window.LaravelDataTables.kt_datatable_horizontal_scroll.ajax.url(window.location.pathname + '?' + query).load();
            return false;
        })

        window.addEventListener('popstate', function (event) {
            // Get the current URL
            var url = window.location.href;

            // Reload the DataTable with the new URL
            window.LaravelDataTables.kt_datatable_horizontal_scroll.ajax.url(url).load();
        });
    </script>
    {{ $dataTable->scripts() }}
    <script>
        $('#kt_datatable_horizontal_scroll').find('thead tr').addClass('fw-bold text-muted');
    </script>
@endpush
