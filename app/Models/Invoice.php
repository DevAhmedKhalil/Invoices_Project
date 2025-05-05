<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use SoftDeletes;

    protected $table = 'invoices';

    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'due_date',
        'discount',
        'rate_vat',
        'value_vat',
        'total',
        'status',
        'status_value',
        'note',
        'section_id',
        'product_id',
        'user_id'
    ];

    protected array $dates = ['deleted_at'];

    // Relationship with Section
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    // Relationship with Product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship with User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}