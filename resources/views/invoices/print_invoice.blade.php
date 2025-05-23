@extends('layouts.master')

@section('css')
    <style>
        /* Optional: print-specific styles */
        @media print {
            body * {
                visibility: hidden;
            }

            #printableArea, #printableArea * {
                visibility: visible;
            }

            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            /* Hide buttons during print */
            .no-print {
                display: none;
            }
        }
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Pages</h4><span
                        class="text-muted mt-1 tx-13 mr-2 mb-0">/ Invoice</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- row -->
    <div class="row row-sm" id="printableArea">
        <div class="col-md-12 col-xl-12">
            <div class="main-content-body-invoice">
                <div class="card card-invoice">
                    <div class="card-body">
                        <div class="invoice-header">
                            <h1 class="invoice-title">فاتورة رقم: {{ $invoice->invoice_number }}</h1>
                            <div class="billed-from">
                                <h6>{{ config('app.name', 'Your Company') }}</h6>
                                <p>
                                    {{ $invoice->company_address ?? '201 Something St., Something Town, YT 242, Country 6546' }}
                                    <br>
                                    Tel No: {{ $invoice->company_phone ?? '324 445-4544' }}<br>
                                    Email: {{ $invoice->company_email ?? 'info@company.com' }}
                                </p>
                            </div><!-- billed-from -->
                        </div><!-- invoice-header -->

                        <div class="row mg-t-20">
                            <div class="col-md">
                                <label class="tx-gray-600">العميل</label>
                                <div class="billed-to">
                                    <h6>{{ $invoice->customer_name ?? 'اسم العميل' }}</h6>
                                    <p>
                                        {{ $invoice->customer_address ?? 'عنوان العميل' }}<br>
                                        Tel No: {{ $invoice->customer_phone ?? 'رقم الهاتف' }}<br>
                                        Email: {{ $invoice->customer_email ?? 'البريد الإلكتروني' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md">
                                <label class="tx-gray-600">معلومات الفاتورة</label>
                                <p class="invoice-info-row"><span>رقم الفاتورة</span>
                                    <span>{{ $invoice->invoice_number }}</span></p>
                                <p class="invoice-info-row"><span>تاريخ الإصدار</span>
                                    <span>{{ $invoice->invoice_date->format('d-m-Y') }}</span></p>
                                <p class="invoice-info-row"><span>تاريخ الاستحقاق</span>
                                    <span>{{ $invoice->due_date->format('d-m-Y') }}</span></p>
                                <p class="invoice-info-row"><span>حالة الفاتورة</span>
                                    <span>{{ $invoice->getStatusArabicAttribute() }}</span></p>
                            </div>
                        </div>

                        <div class="table-responsive mg-t-40">
                            <table class="table table-invoice border text-md-nowrap mb-0">
                                <thead>
                                <tr>
                                    <th class="wd-20p">النوع</th>
                                    <th class="wd-40p">الوصف</th>
                                    <th class="tx-center">حالة الدفع</th>
                                    <th class="tx-right">تاريخ الدفع</th>
                                    <th class="tx-right">المستخدم</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach ($invoice->details as $detail)
                                    <tr>
                                        <td>{{ $detail->product }}</td>
                                        <td class="tx-12">{{ $detail->note ?? '-' }}</td>
                                        <td class="tx-center">{{ $detail->status }}</td>
                                        <td class="tx-right">{{ $detail->payment_date ? \Carbon\Carbon::parse($detail->payment_date)->format('Y-m-d') : '-' }}</td>
                                        <td class="tx-right">{{ $detail->user }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="valign-middle" colspan="2" rowspan="4">
                                        <div class="invoice-notes">
                                            <label class="main-content-label tx-13">ملاحظات</label>
                                            <p>{{ $invoice->notes ?? 'لا توجد ملاحظات' }}</p>
                                        </div><!-- invoice-notes -->
                                    </td>
                                    <td class="tx-right">الإجمالي الفرعي</td>
                                    <td class="tx-right"
                                        colspan="2">{{ number_format($invoice->subtotal, 2) }} {{ $invoice->currency ?? '$' }}</td>
                                </tr>
                                <tr>
                                    <td class="tx-right">الضريبة ({{ $invoice->tax_rate ?? 0 }}%)</td>
                                    <td class="tx-right"
                                        colspan="2">{{ number_format($invoice->tax_amount, 2) }} {{ $invoice->currency ?? '$' }}</td>
                                </tr>
                                <tr>
                                    <td class="tx-right">الخصم</td>
                                    <td class="tx-right"
                                        colspan="2">{{ number_format($invoice->discount, 2) }} {{ $invoice->currency ?? '$' }}</td>
                                </tr>
                                <tr>
                                    <td class="tx-right tx-uppercase tx-bold tx-inverse">الإجمالي المستحق</td>
                                    <td class="tx-right" colspan="2">
                                        <h4 class="tx-primary tx-bold">{{ number_format($invoice->total_due, 2) }} {{ $invoice->currency ?? '$' }}</h4>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <hr class="mg-b-40">

                        <a class="btn btn-purple float-left mt-3 mr-2 no-print" href="#">
                            <i class="mdi mdi-currency-usd ml-1"></i> دفع الآن
                        </a>
                        <a href="#" class="btn btn-danger float-left mt-3 mr-2 no-print" id="printInvoiceBtn">
                            <i class="mdi mdi-printer ml-1"></i> طباعة الفاتورة
                        </a>
                        <a href="#" class="btn btn-success float-left mt-3 no-print">
                            <i class="mdi mdi-telegram ml-1"></i> إرسال الفاتورة
                        </a>
                    </div>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>
    <!-- row closed -->
@endsection

@section('js')
    <script>
        document.getElementById('printInvoiceBtn').addEventListener('click', function (e) {
            e.preventDefault();
            window.print();
        });
    </script>
@endsection
