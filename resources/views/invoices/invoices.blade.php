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
    <!--Internal   Notify -->
    <link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
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
                                            <div class="dropdown">
                                                <button class="btn btn-primary btn-sm p-1 d-flex align-items-center justify-content-center"
                                                        type="button"
                                                        id="dropdownMenuButton{{ $invoice->id }}"
                                                        data-toggle="dropdown"
                                                        aria-haspopup="true"
                                                        aria-expanded="false"
                                                        style="width: 30px; height: 30px;">
                                                    <i class="las la-cog"></i>
                                                </button>
                                                <div class="dropdown-menu"
                                                     aria-labelledby="dropdownMenuButton{{ $invoice->id }}">

                                                    {{-- Edit --}}
                                                    <a class="dropdown-item text-info"
                                                       href="{{ route('invoices.edit', $invoice->id) }}">
                                                        <i class="las la-pen"></i> تعديل
                                                    </a>

                                                    {{-- Soft Delete --}}
                                                    <button class="dropdown-item text-danger"
                                                            data-toggle="modal"
                                                            data-target="#deleteModal"
                                                            data-id="{{ $invoice->id }}"
                                                            data-invoice_number="{{ $invoice->invoice_number }}">
                                                        <i class="las la-trash"></i> حذف
                                                    </button>

                                                    {{-- Force Delete --}}
                                                    <button class="dropdown-item text-danger"
                                                            data-toggle="modal"
                                                            data-target="#forceDeleteModal"
                                                            data-id="{{ $invoice->id }}"
                                                            data-invoice_number="{{ $invoice->invoice_number }}">
                                                        <i class="las la-times-circle"></i> حذف نهائي
                                                    </button>

                                                    {{-- Change Status --}}
                                                    <button class="dropdown-item text-warning"
                                                            data-toggle="modal"
                                                            data-target="#statusModal"
                                                            data-id="{{ $invoice->id }}"
                                                            data-status="{{ $invoice->status }}"
                                                            data-invoice_number="{{ $invoice->invoice_number }}"
                                                            data-last_updated="{{ $invoice->updated_at }}">
                                                        <i class="las la-sync"></i> تغيير حالة الفاتورة
                                                    </button>

                                                </div>
                                            </div>
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

            <!-- Modal تغيير حالة الفاتورة -->
            <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="POST" action="{{ route('invoice.updateStatus', $invoice->id) }}">
                        @csrf
                        <input type="hidden" name="invoice_id" id="modal_invoice_id">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">تغيير حالة الفاتورة</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p><strong>رقم الفاتورة:</strong> <span id="modal_invoice_number"></span></p>
                                <div class="form-group">
                                    <label for="status">الحالة الجديدة</label>
                                    <select name="status" class="form-control" required>
                                        <option value="paid">مدفوعة</option>
                                        <option value="unpaid">غير مدفوعة</option>
                                        <option value="partial">مدفوعة جزئياً</option>
                                        <option value="overdue">متأخرة</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="updated_at">تاريخ التعديل</label>
                                    <input type="date" name="payment_date" class="form-control"
                                           value="{{ now()->toDateString() }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">حفظ التغييرات</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Force Delete Modal -->
            <div class="modal" id="forceDeleteModal">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content modal-content-demo">
                        <div class="modal-header bg-danger text-white">
                            <h6 class="modal-title text-white">حذف نهائي للفاتورة</h6>
                            <button aria-label="Close" class="close text-white" data-dismiss="modal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form id="forceDeleteForm" method="post">
                            @csrf
                            @method("DELETE")
                            <div class="modal-body">
                                <p class="text-danger font-weight-bold">
                                    ⚠️ هل أنت متأكد أنك تريد حذف هذه الفاتورة بشكل نهائي؟ لا يمكن التراجع عن هذا
                                    الإجراء.
                                </p>
                                <input type="hidden" name="id" id="force_id" value="">
                                <input class="form-control" name="invoice_number" id="force_invoice_number" type="text"
                                       readonly>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                <button type="submit" class="btn btn-danger">حذف نهائي</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Force Delete Modal -->

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
            <!--Internal  Notify js -->
            <script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
            <script src="{{URL::asset('assets/plugins/notify/js/notifit-custom.js')}}"></script>

            <script>
                $('#forceDeleteModal').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget);
                    var id = button.data('id');
                    var invoiceNumber = button.data('invoice_number');

                    // تحديث بيانات الفورم
                    var modal = $(this);
                    modal.find('#force_id').val(id);
                    modal.find('#force_invoice_number').val(invoiceNumber);

                    // تحديد رابط الفورم بناءً على الـ ID
                    var deleteUrl = '/invoices/force-delete/' + id;
                    $('#forceDeleteForm').attr('action', deleteUrl);
                });
            </script>

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

            <script>
                $('#statusModal').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget);
                    var invoiceId = button.data('id');
                    var status = button.data('status');
                    var invoiceNumber = button.data('invoice_number');
                    var updatedAt = button.data('last_updated');

                    var modal = $(this);
                    modal.find('#modal_invoice_id').val(invoiceId);
                    modal.find('#modal_invoice_number').text(invoiceNumber);
                    modal.find('#modal_status').val(status);
                    modal.find('#modal_updated_at').val(updatedAt ? updatedAt.split('T')[0] : '');
                });
            </script>

@endsection
