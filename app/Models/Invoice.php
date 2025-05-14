<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use SoftDeletes;

    protected $table = 'invoices';

    protected $guarded = [];
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