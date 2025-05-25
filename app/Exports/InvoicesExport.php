<?php

namespace App\Exports;

use App\Models\Invoice;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InvoicesExport implements FromCollection, WithHeadings
{
    /**
     * Return the collection of invoices.
     */
    public function collection()
    {
        return Invoice::select([
            'id',
            'invoice_number',
            'invoice_date',
            'due_date',
            'section_id',
            'discount',
            'rate_vat',
            'value_vat',
            'total',
            'status',
            'note',
            'created_at',
            'updated_at'
        ])->get();
    }

    /**
     * Define headings for the exported file.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Invoice Number',
            'Invoice Date',
            'Due Date',
            'Section ID',
            'Discount',
            'VAT Rate',
            'VAT Value',
            'Total',
            'Status',
            'Note',
            'Created At',
            'Updated At'
        ];
    }
}
