@extends('layouts.master')
@section('css')
    <!---Internal  Prism css-->
    <link href="{{ URL::asset('assets/plugins/prism/prism.css') }}" rel="stylesheet">
    <!---Internal Input tags css-->
    <link href="{{ URL::asset('assets/plugins/inputtags/inputtags.css') }}" rel="stylesheet">
    <!--- Custom-scroll -->
    <link href="{{ URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.css') }}" rel="stylesheet">
@endsection
@section('title')
    تفاصيل فاتورة
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"><a href="/invoice">قائمة الفواتير</a></h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تفاصيل الفاتورة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session()->has('delete'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('delete') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card mg-b-20" id="tabs-style2">
                <div class="card-body">
                    <div class="text-wrap">
                        <div class="example">
                            <div class="panel panel-primary tabs-style-2">
                                <div class="tab-menu-heading">
                                    <div class="tabs-menu1">
                                        <ul class="nav panel-tabs main-nav-line">
                                            <li><a href="#tab4" class="nav-link active" data-toggle="tab">معلومات
                                                    الفاتورة</a></li>
                                            <li><a href="#tab5" class="nav-link" data-toggle="tab">حالات الدفع</a></li>
                                            <li><a href="#tab6" class="nav-link" data-toggle="tab">المرفقات</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body main-content-body-right border">
                                    <div class="tab-content">

                                        <!-- Invoice Info Tab -->
                                        <div class="tab-pane active" id="tab4">
                                            <div class="table-responsive mt-15">
                                                <table class="table table-striped table-bordered table-hover mb-4">
                                                    <tbody>
                                                    <tr class="bg-light">
                                                        <th scope="row" class="w-25 font-weight-bold text-dark">رقم
                                                            الفاتورة
                                                        </th>
                                                        <td class="w-75">{{ $invoices->invoice_number }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="font-weight-bold text-secondary">تاريخ
                                                            الإصدار
                                                        </th>
                                                        <td>{{ $invoices->invoice_date }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="font-weight-bold text-secondary">تاريخ
                                                            الاستحقاق
                                                        </th>
                                                        <td>{{ $invoices->due_date }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="font-weight-bold text-secondary">القسم
                                                        </th>
                                                        <td>{{ $invoices->section->section_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="font-weight-bold text-secondary">المنتج
                                                        </th>
                                                        <td>{{ $invoices->product->product_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="font-weight-bold text-secondary">مبلغ
                                                            التحصيل
                                                        </th>
                                                        <td>{{ number_format($invoices->amount_collection, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="font-weight-bold text-secondary">مبلغ
                                                            العمولة
                                                        </th>
                                                        <td>{{ number_format($invoices->amount_commission, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="font-weight-bold text-secondary">الخصم
                                                        </th>
                                                        <td>{{ number_format($invoices->discount, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="font-weight-bold text-secondary">نسبة
                                                            الضريبة
                                                        </th>
                                                        <td>{{ $invoices->rate_vat }}%</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="font-weight-bold text-secondary">قيمة
                                                            الضريبة
                                                        </th>
                                                        <td>{{ number_format($invoices->value_vat, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="font-weight-bold text-secondary">الإجمالي
                                                            شامل الضريبة
                                                        </th>
                                                        <td class="font-weight-bold text-success">{{ number_format($invoices->total, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="font-weight-bold text-secondary">الحالة
                                                            الحالية
                                                        </th>
                                                        <td>
                                                            <span class="badge badge-pill {{
                                                                match($invoices->status_value) {
                                                                    0 => 'badge-danger',
                                                                    1 => 'badge-success',
                                                                    2 => 'badge-warning',
                                                                    3 => 'badge-secondary',
                                                                    default => 'badge-dark',
                                                                }
                                                            }} p-2">
                                                                {{ $invoices->status }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="font-weight-bold text-secondary">
                                                            ملاحظات
                                                        </th>
                                                        <td class="text-muted">{{ $invoices->note ?? '-- لا توجد ملاحظات --' }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- Payment Status Tab -->
                                        <div class="tab-pane" id="tab5">
                                            <div class="table-responsive mt-15">
                                                <table class="table table-striped table-hover text-center mb-0">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>رقم الفاتورة</th>
                                                        <th>نوع المنتج</th>
                                                        <th>القسم</th>
                                                        <th>حالة الدفع</th>
                                                        <th>تاريخ الدفع</th>
                                                        <th>ملاحظات</th>
                                                        <th>تاريخ الإضافة</th>
                                                        <th>المستخدم</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($details as $detail)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $detail->invoice_number }}</td>
                                                            <td>{{ $invoices->product->product_name }}</td>
                                                            <td>{{ $invoices->section->section_name }}</td>
                                                            @php
                                                                $badge = match($detail->status_value) {
                                                                    0 => 'badge-danger',
                                                                    1 => 'badge-success',
                                                                    2 => 'badge-warning',
                                                                    3 => 'badge-secondary',
                                                                    default => 'badge-dark',
                                                                };
                                                            @endphp
                                                            <td>
                                                                <span class="badge badge-pill {{ $badge }}">{{ $detail->status }}</span>
                                                            </td>
                                                            <td>{{ $detail->payment_date }}</td>
                                                            <td>{{ $detail->note }}</td>
                                                            <td>{{ $detail->created_at }}</td>
                                                            <td>{{ $detail->user }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- Attachments Tab -->
                                        <div class="tab-pane" id="tab6">
                                            <div class="card card-statistics">
                                                <div class="card-body">
                                                    <p class="text-danger">* صيغة المرفق: pdf, jpeg, jpg, png</p>
                                                    <h5 class="card-title">إضافة مرفقات</h5>
                                                    <form method="post"
                                                          action="{{ route('invoices.attachments.store', $invoices->id) }}"
                                                          enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="customFile"
                                                                   name="file_name" required>
                                                            <input type="hidden" name="invoice_number"
                                                                   value="{{ $invoices->invoice_number }}">
                                                            <label class="custom-file-label" for="customFile">حدد
                                                                المرفق</label>
                                                        </div>
                                                        <br><br>
                                                        <button type="submit" class="btn btn-primary btn-sm">تأكيد
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="table-responsive mt-15">
                                                    <table class="table table-hover text-center mb-0">
                                                        <thead>
                                                        <tr>
                                                            <th>م</th>
                                                            <th>اسم الملف</th>
                                                            <th>قام بالاضافة</th>
                                                            <th>تاريخ الاضافة</th>
                                                            <th>العمليات</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach ($attachments as $attachment)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $attachment->file_name }}</td>
                                                                <td>{{ $attachment->created_by }}</td>
                                                                <td>{{ $attachment->created_at }}</td>
                                                                <td>
                                                                    <a href="{{ url('view-file/' . $invoices->invoice_number . '/' . $attachment->file_name) }}"
                                                                       class="btn btn-success btn-sm"
                                                                       target="_blank"
                                                                    >
                                                                        <i class="fas fa-eye"></i> عرض</a>
                                                                    <a href="{{ url('download/' . $invoices->invoice_number . '/' . $attachment->file_name) }}"
                                                                       class="btn btn-info btn-sm">
                                                                        <i class="fas fa-download"></i> تحميل</a>
                                                                    <button class="btn btn-danger btn-sm"
                                                                            data-toggle="modal"
                                                                            data-target="#delete_file"
                                                                            data-id_file="{{ $attachment->id }}"
                                                                            data-file_name="{{ $attachment->file_name }}"
                                                                            data-invoice_number="{{ $attachment->invoice_number }}">
                                                                        <i class="las la-trash"></i>حذف
                                                                    </button>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Attachment Modal -->
    <div class="modal fade" id="delete_file" tabindex="-1" role="dialog" aria-labelledby="deleteFileLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('delete-file') }}" method="post">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteFileLabel">حذف المرفق</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-center text-danger">هل أنت متأكد من حذف هذا المرفق؟</p>
                        <input type="hidden" name="id_file" id="id_file" value="">
                        <input type="hidden" name="file_name" id="file_name" value="">
                        <input type="hidden" name="invoice_number" id="invoice_number" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-danger">تأكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!-- Internal Jquery.mCustomScrollbar js-->
    <script src="{{ URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <!-- Internal Input tags js-->
    <script src="{{ URL::asset('assets/plugins/inputtags/inputtags.js') }}"></script>
    <!--- Tabs JS-->
    <script src="{{ URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js') }}"></script>
    <script src="{{ URL::asset('assets/js/tabs.js') }}"></script>
    <!--Internal  Clipboard js-->
    <script src="{{ URL::asset('assets/plugins/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/clipboard/clipboard.js') }}"></script>
    <!-- Internal Prism js-->
    <script src="{{ URL::asset('assets/plugins/prism/prism.js') }}"></script>

    <script>
        $('#delete_file').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id_file = button.data('id_file');
            var file_name = button.data('file_name');
            var invoice_number = button.data('invoice_number');
            var modal = $(this);
            modal.find('#id_file').val(id_file);
            modal.find('#file_name').val(file_name);
            modal.find('#invoice_number').val(invoice_number);
        });
    </script>

    <script>
        // Display the selected file name => Vanilla JS
        document.addEventListener('DOMContentLoaded', function () {
            const fileInput = document.querySelector('.custom-file-input');
            if (fileInput) {
                fileInput.addEventListener('change', function (e) {
                    const fileName = e.target.files[0]?.name;
                    const label = e.target.closest('.custom-file').querySelector('.custom-file-label');
                    if (label) {
                        label.classList.add('selected');
                        label.textContent = fileName || "حدد المرفق";
                    }
                });
            }
        });
    </script>
    {{--    <script>--}}
    {{--        // Display the selected file name => JQuery--}}
    {{--        $('.custom-file-input').on('change', function () {--}}
    {{--            var fileName = $(this).val().split('\\').pop();--}}
    {{--            $(this).siblings('.custom-file-label').addClass('selected').html(fileName);--}}
    {{--        });--}}
    {{--    </script>--}}
@endsection
