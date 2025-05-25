@extends('layouts.master')

@section('title')
    قائمة المستخدمين - مورا سوفت
@stop

@section('css')
    <!-- DataTables CSS -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet" />
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المستخدمين</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة المستخدمين</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Add User Button -->
    <div class="d-flex justify-content-center mt-3">
        @can('اضافة مستخدم')
            <div class="mx-2">
                <a class="btn btn-outline-primary" href="{{ route('users.create') }}">
                    ➕ إضافة مستخدم
                </a>
            </div>
        @endcan
    </div>

    <!-- DataTable -->
    <div class="row row-sm mt-4">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive hoverable-table">
                        <table class="table table-hover text-center" id="example1" data-page-length='50'>
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم المستخدم</th>
                                <th>البريد الالكتروني</th>
                                <th>الحالة</th>
                                <th>الصلاحيات</th>
                                <th>العمليات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($users as $index => $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                            <span class="badge {{ $user->Status == 'مفعل' ? 'badge-success' : 'badge-danger' }}">
                                                {{ $user->Status }}
                                            </span>
                                    </td>
                                    <td>
                                        @foreach ($user->getRoleNames() as $role)
                                            <span class="badge badge-info">{{ $role }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @can('تعديل مستخدم')
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-info" title="تعديل">
                                                <i class="las la-pen"></i>
                                            </a>
                                        @endcan
                                        @can('حذف مستخدم')
                                            <button class="btn btn-sm btn-danger" data-toggle="modal"
                                                    data-target="#deleteUserModal" data-id="{{ $user->id }}"
                                                    data-name="{{ $user->name }}">
                                                <i class="las la-trash"></i>
                                            </button>
                                        @endcan
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

    <!-- Delete User Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form method="post" action="{{ route('users.destroy', 'user') }}">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="deleteUserLabel">تأكيد الحذف</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        هل أنت متأكد من حذف المستخدم؟
                        <input type="hidden" name="user_id" id="delete-user-id">
                        <input type="text" class="form-control mt-2" name="username" id="delete-user-name" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-danger">تأكيد الحذف</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
        <!-- Internal Data tables -->
        <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
        <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
        <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
        <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
        <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
        <!--Internal  Datatable js -->
        <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
        <!--Internal  Notify js -->
        <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
        <script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>
        <!-- Internal Modal js-->
        <script src="{{ URL::asset('assets/js/modal.js') }}"></script>

    <script>
        $('#deleteUserModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            $('#delete-user-id').val(button.data('id'));
            $('#delete-user-name').val(button.data('name'));
        });
    </script>
@endsection
