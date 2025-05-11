@extends('layouts.master')

@section('title')
    قائمة الفواتير
@endsection

@section('css')
    <!-- Internal Data table css -->
    <link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet"/>
    <link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet"/>
    <link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة الفواتير</span>
            </div>
        </div>
        {{--					<div class="d-flex my-xl-auto right-content"></div>--}}
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')

    @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
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

    <!-- row -->
    <div class="row">
        <!-- row opened -->
        <!--div-->
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-center">
                        <div class="">
                            <a class="btn btn-outline-primary btn-block"
                               href="{{ route('invoice.create') }}">
                                اضافة فاتورة
                            </a>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example1" class="table key-buttons text-md-nowrap" data-page-length="50">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">رقم الفاتورة</th>
                                    <th class="border-bottom-0">تاريخ الفاتورة</th>
                                    <th class="border-bottom-0">تاريخ الاستحقاق</th>
                                    <th class="border-bottom-0">المنتج</th>
                                    <th class="border-bottom-0">القسم</th>
                                    <th class="border-bottom-0">الخصم</th>
                                    <th class="border-bottom-0">نسبة الضريبة</th>
                                    <th class="border-bottom-0">قيمة الضريبة</th>
                                    <th class="border-bottom-0">الإجمالي</th>
                                    <th class="border-bottom-0">الحالة</th>
                                    <th class="border-bottom-0">ملاحظات</th>
                                    <th class="border-bottom-0">الإجراءات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td>
                                            <div class="sticky-cell">{{ $loop->iteration }}</div>
                                        </td>
                                        <td>
                                            <div class="sticky-cell">{{ $invoice->invoice_number }}</div>
                                        </td>
                                        <td>
                                            <div class="sticky-cell">{{ $invoice->invoice_date }}</div>
                                        </td>
                                        <td>
                                            <div class="sticky-cell">{{ $invoice->due_date }}</div>
                                        </td>
                                        <td>
                                            <div class="sticky-cell">{{ $invoice->product->product_name }}</div>
                                        </td>
                                        <td>
                                            <a href="{{ url('invoices-details') }}/{{ $invoice->id }}">{{ $invoice->section->section_name }}</a>
                                        </td>
                                        <td>
                                            <div class="sticky-cell">{{ $invoice->discount }}%</div>
                                        </td>
                                        <td>
                                            <div class="sticky-cell">{{ $invoice->rate_vat }}%</div>
                                        </td>
                                        <td>
                                            <div class="sticky-cell">{{ $invoice->value_vat }}</div>
                                        </td>
                                        <td>
                                            <div class="sticky-cell">{{ $invoice->total }}</div>
                                        </td>
                                        <td>
                                            <div class="sticky-cell
                                                    {{ match($invoice->status_value) {
                                                        0 => 'text-danger fw-semibold',
                                                        1 => 'text-success fw-semibold',
                                                        2 => 'text-warning fw-semibold',
                                                        3 => 'text-secondary fw-semibold',
                                                        default => 'text-dark',
                                                    } }}
                                               ">
                                                {{ $invoice->status }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class=" sticky-cell
                                            ">{{ $invoice->note }}</div>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <a class="btn btn-info btn-sm text-white"><i class="las la-pen"></i></a>
                                            <button class="btn btn-danger btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal"
                                                    data-id="{{ $invoice->id }}"
                                                    data-invoice_number="{{ $invoice->invoice_number }}">
                                                <i class="las la-trash"></i>
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
            <!--/div-->
            <!-- row closed -->

            <!-- Delete modal -->
            <div class="modal" id="deleteModal">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content modal-content-demo">
                        <div class="modal-header">
                            <h6 class="modal-title">حذف المنتج</h6>
                            <button aria-label="Close" class="close" data-dismiss="modal"
                                    type="button"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <form id="deleteForm" method="post">
                            @method("DELETE")
                            @csrf
                            <div class="modal-body">
                                <p>هل انت متاكد من عملية الحذف ؟</p><br>
                                <input type="hidden" name="id" id="id" value="">
                                <input class="form-control" name="invoice_name" id="invoice_name" type="text"
                                       readonly>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                                <button type="submit" class="btn btn-danger">تاكيد</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--End Delete modal -->
        </div>
        <!-- Container closed -->
        <!-- main-content closed -->
        @endsection
        @section('js')
            <!-- Internal Data tables -->
            <script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
            <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
            <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
            <script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
            <script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
            <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
            <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
            <script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
            <script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
            <script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
            <script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
            <script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
            <script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
            <script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
            <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
            <script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
            <!--Internal  Datatable js -->
            <script src="{{URL::asset('assets/js/table-data.js')}}"></script>


            <script>
                $('#deleteModal').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget)
                    var id = button.data('id')
                    var invoice_number = button.data('invoice_number')
                    var modal = $(this)
                    modal.find('.modal-body #id').val(id);
                    modal.find('.modal-body #invoice_name').val(invoice_number);

                    // Update the form action dynamically
                    modal.find('form#deleteForm').attr('action', '/invoice/' + id);
                })
            </script>

@endsection
