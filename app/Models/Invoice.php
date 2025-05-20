<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use SoftDeletes;

    protected array $dates = ['deleted_at'];

    protected $table = 'invoices';

    protected $guarded = [];

    public function getStatusArabicAttribute(): string
    {
        return match ($this->status) {
            'paid' => 'مدفوعة',
            'unpaid' => 'غير مدفوعة',
            'partial' => 'مدفوعة جزئياً',
            'overdue' => 'متأخرة',
            default => 'غير معروفة',
        };
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(InvoicesAttachment::class, 'invoice_id');
    }

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