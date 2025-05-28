@extends('layouts.master')
@section('css')
    <link href="{{URL::asset('assets/plugins/jquery-nice-select/css/nice-select.css')}}" rel="stylesheet" />
    @section('title')
        اضافة مستخدم - مورا سوفت للادارة القانونية
    @stop
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المستخدمين</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ اضافة مستخدم</span>
            </div>
        </div>
    </div>
@endsection

@section('content')

    @if(session()->has('notif'))
        <script>
            window.onload = function () {
                notif({
                    msg: "{{ session('notif.msg') }}",
                    type: "{{ session('notif.type') }}"
                });
            }
        </script>
    @elseif(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="إغلاق">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="إغلاق">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="إغلاق">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="إغلاق">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 col-md-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <button class="close" data-dismiss="alert" type="button">&times;</button>
                    <strong>خطأ</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('users.index') }}">رجوع</a>
                        </div>
                    </div><br>

                    <form class="parsley-style-1" autocomplete="off"
                          action="{{ route('users.store') }}" method="post">
                        @csrf

                        <div class="row mg-b-20">
                            <div class="parsley-input col-md-6">
                                <label>اسم المستخدم: <span class="tx-danger">*</span></label>
                                <input class="form-control form-control-sm" name="name" required type="text">
                            </div>

                            <div class="parsley-input col-md-6">
                                <label>البريد الإلكتروني: <span class="tx-danger">*</span></label>
                                <input class="form-control form-control-sm" name="email" required type="email">
                            </div>
                        </div>

                        <div class="row mg-b-20">
                            <div class="parsley-input col-md-6">
                                <label>كلمة المرور: <span class="tx-danger">*</span></label>
                                <input class="form-control form-control-sm" name="password" required type="password">
                            </div>

                            <div class="parsley-input col-md-6">
                                <label>تأكيد كلمة المرور: <span class="tx-danger">*</span></label>
                                <input class="form-control form-control-sm" name="confirm-password" required type="password">
                            </div>
                        </div>

                        <div class="row mg-b-20">
                            <div class="col-lg-6">
                                <label>حالة المستخدم</label>
                                <select name="status" class="form-control nice-select">
                                    <option value="مفعل">مفعل</option>
                                    <option value="غير مفعل">غير مفعل</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mg-b-20">
                            <div class="col-md-12">
                                <label>صلاحية المستخدم <span class="tx-danger">*</span></label>
                                <select name="roles[]" class="form-control" multiple required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role }}">{{ $role }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-main-primary pd-x-20" type="submit">تأكيد</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/jquery-nice-select/js/jquery.nice-select.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery-nice-select/js/nice-select.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('select:not([multiple])').niceSelect();
        });
    </script>

    <script src="{{ URL::asset('assets/plugins/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/form-validation.js') }}"></script>
@endsection
