@extends('layouts.master')

@section('css')
    <style>
        @media print {
            .no-print {
                display: none !important;
                visibility: hidden !important;
                opacity: 0 !important;
                height: 0 !important;
                overflow: hidden !important;
                pointer-events: none !important;
            }

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
        }

        .attachment-img {
            max-width: 100%;
            height: 100px;
            object-fit: contain;
            border: 1px solid #ddd;
            padding: 4px;
            border-radius: 5px;
            background-color: #fff;
            display: block;
        }

        .table-responsive {
            overflow-x: auto;
            max-width: 100%;
        }

        .table-invoice {
            table-layout: fixed;
            width: 100%;
            word-wrap: break-word;
        }

        .table-invoice td,
        .table-invoice th {
            white-space: normal;
            overflow-wrap: break-word;
            word-break: break-word;
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Pages</h4><span
                        class="text-muted mt-1 tx-13 mr-2 mb-0">/ Invoice</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
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
                                    {{ $invoice->company_address ?? '201 Something St., Something Town' }}<br>
                                    Tel No: {{ $invoice->company_phone ?? '324 445-4544' }}<br>
                                    Email: {{ $invoice->company_email ?? 'info@company.com' }}
                                </p>
                            </div>
                        </div>

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
                                <p class="invoice-info-row">
                                    <span>رقم الفاتورة</span><span>{{ $invoice->invoice_number }}</span></p>
                                <p class="invoice-info-row">
                                    <span>تاريخ الإصدار</span><span>{{ $invoice->invoice_date->format('d-m-Y') }}</span>
                                </p>
                                <p class="invoice-info-row">
                                    <span>تاريخ الاستحقاق</span><span>{{ $invoice->due_date->format('d-m-Y') }}</span>
                                </p>
                                <p class="invoice-info-row">
                                    <span>حالة الفاتورة</span><span>{{ $invoice->getStatusArabicAttribute() }}</span>
                                </p>
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
                                            <p>{{ $invoice->note ?? 'لا توجد ملاحظات' }}</p>
                                        </div>
                                    </td>
                                    <td class="tx-right">الإجمالي الفرعي</td>
                                    <td class="tx-right" colspan="2">
                                        {{ number_format($invoice->amount_commission, 2) }} {{ $invoice->currency ?? '$' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tx-right">الضريبة ({{ $invoice->rate_vat }}%)</td>
                                    <td class="tx-right" colspan="2">
                                        {{ number_format($invoice->value_vat, 2) }} {{ $invoice->currency ?? '$' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tx-right">الخصم</td>
                                    <td class="tx-right" colspan="2">
                                        {{ number_format($invoice->discount, 2) }} {{ $invoice->currency ?? '$' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tx-right tx-uppercase tx-bold tx-inverse">الإجمالي المستحق</td>
                                    <td class="tx-right" colspan="2">
                                        <h4 class="tx-primary tx-bold">{{ number_format($invoice->total, 2) }} {{ $invoice->currency ?? '$' }}</h4>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        @if($invoice->attachments->count())
                            <div class="mt-5">
                                <h5 class="mb-3">المرفقات (صور):</h5>
                                <div class="row">
                                    @foreach ($invoice->attachments as $attachment)
                                        <div class="col-md-3 mb-3">
                                            <img src="{{ asset('Attachments/' . $attachment->invoice_number . '/' . $attachment->file_name) }}"
                                                 alt="مرفق الفاتورة" class="attachment-img">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <hr class="mg-b-40">

                        <a href="#" class="btn btn-danger float-left mt-3 mr-2 no-print" id="printInvoiceBtn">
                            <i class="mdi mdi-printer ml-1"></i> طباعة الفاتورة
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.getElementById('printInvoiceBtn').addEventListener('click', function (e) {
            e.preventDefault();
            window.print();
        });
    </script>
@endsection
