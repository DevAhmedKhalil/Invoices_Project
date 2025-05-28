@extends('layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/treeview/treeview-rtl.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection

@section('title')
    اضافة الصلاحيات - مورا سوفت للادارة القانونية
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الصلاحيات</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ اضافة نوع مستخدم</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card mg-b-20">
                    <div class="card-body">
                        <div class="form-group">
                            <p>اسم الصلاحية :</p>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                            @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <ul id="treeview1">
                                    <li><a href="#">الصلاحيات</a>
                                        <ul>
                                            @foreach($permissions as $value)
                                                <li>
                                                    <label style="font-size: 16px;">
                                                        <input type="checkbox" name="permission[]" value="{{ $value->id }}">
                                                        {{ $value->name }}
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-12 text-center mt-3">
                                <button type="submit" class="btn btn-main-primary">تأكيد</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/treeview/treeview.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>

    <script>
        window.onload = function () {
            @if(session('notif'))
            notif({
                msg: "{{ session('notif')['msg'] }}",
                type: "{{ session('notif')['type'] }}",
                position: "center",
                timeout: 3000
            });
            @endif

            @if ($errors->any())
            @foreach ($errors->all() as $error)
            notif({
                msg: "{{ $error }}",
                type: "error",
                position: "center",
                timeout: 4000
            });
            @endforeach
            @endif
        };
    </script>
@endsection
