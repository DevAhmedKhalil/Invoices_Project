@extends('layouts.master')

@section('title', 'صلاحيات المستخدمين - مورا سوفت للادارة القانونية')

@section('css')
    <!-- Internal Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <!-- Internal DataTable CSS -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المستخدمين</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ صلاحيات المستخدمين</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @if (session()->has('Add'))
        <script>
            window.onload = function () {
                notif({ msg: "تم اضافة الصلاحية بنجاح", type: "success" });
            }
        </script>
    @endif

    @if (session()->has('edit'))
        <script>
            window.onload = function () {
                notif({ msg: "تم تحديث بيانات الصلاحية بنجاح", type: "success" });
            }
        </script>
    @endif

    @if (session()->has('delete'))
        <script>
            window.onload = function () {
                notif({ msg: "تم حذف الصلاحية بنجاح", type: "error" });
            }
        </script>
    @endif

    <!-- row -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    @can('اضافة صلاحية')
                        <a class="btn btn-primary btn-sm" href="{{ route('roles.create') }}">اضافة</a>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="table-responsive hoverable-table">
                        <table class="table table-hover" id="example1" data-page-length='50' style="text-align: center;">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم الصلاحية</th>
                                <th>العمليات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($roles as $key => $role)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        @can('عرض صلاحية')
                                            <a class="btn btn-sm btn-success" href="{{ route('roles.show', $role->id) }}">عرض</a>
                                        @endcan
                                        @can('تعديل صلاحية')
                                            <a class="btn btn-sm btn-primary" href="{{ route('roles.edit', $role->id) }}">تعديل</a>
                                        @endcan
                                        @if ($role->name !== 'owner')
                                            @can('حذف صلاحية')
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id], 'style' => 'display:inline']) !!}
                                                {!! Form::submit('حذف', ['class' => 'btn btn-sm btn-danger']) !!}
                                                {!! Form::close() !!}
                                            @endcan
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
@endsection

@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Internal Datatable Initialization -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

    <!-- Internal Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>

    <!-- Internal Modal js -->
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>
@endsection
