@extends('layouts.master')
@section('css')
    <!-- Internal Nice-select css  -->
    <link href="{{URL::asset('assets/plugins/jquery-nice-select/css/nice-select.css')}}" rel="stylesheet" />
    @section('title')
        تعديل مستخدم - مورا سوفت للادارة القانونية
    @stop
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل
                مستخدم</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
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

                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="row mg-b-20">
                            <div class="parsley-input col-md-6" id="fnWrapper">
                                <label>اسم المستخدم: <span class="tx-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                            </div>

                            <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                                <label>البريد الالكتروني: <span class="tx-danger">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mg-b-20">
                            <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                                <label>كلمة المرور: <span class="tx-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                                <label>تأكيد كلمة المرور: <span class="tx-danger">*</span></label>
                                <input type="password" name="confirm-password" class="form-control" required>
                            </div>
                        </div>

                        <div class="row row-sm mg-b-20">
                            <div class="col-lg-6">
                                <label class="form-label">حالة المستخدم</label>
                                <select name="status" class="form-control nice-select custom-select">
{{--                                    <option value="{{ $user->status }}">{{ $user->status }}</option>--}}
                                    <option value="مفعل">مفعل</option>
                                    <option value="غير مفعل">غير مفعل</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mg-b-20">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>نوع المستخدم</strong>
                                    <select name="roles[]" class="form-control" multiple>
{{--                                        @foreach($roles as $id => $name)--}}
{{--                                            <option value="{{ $id }}" {{ in_array($id, $userRoles) ? 'selected' : '' }}>--}}
{{--                                                {{ $name }}--}}
{{--                                            </option>--}}
{{--                                        @endforeach--}}
                                        @foreach($roles as $name)
                                            <option value="{{ $name }}" {{ in_array($name, $userRoles) ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mg-t-30">
                            <button class="btn btn-main-primary pd-x-20" type="submit">تحديث</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
@endsection

@section('js')
    <!-- Internal Nice-select js-->
    <script src="{{URL::asset('assets/plugins/jquery-nice-select/js/jquery.nice-select.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/jquery-nice-select/js/nice-select.js')}}"></script>

    <!--Internal  Parsley.min js -->
    <script src="{{URL::asset('assets/plugins/parsleyjs/parsley.min.js')}}"></script>
    <!-- Internal Form-validation js -->
    <script src="{{URL::asset('assets/js/form-validation.js')}}"></script>
@endsection
