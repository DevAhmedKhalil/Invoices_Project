<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    protected $table = 'products'; // Correct table name

    protected $fillable = [
        'product_name',          // Changed from product_name
        'description',
        'price',
        'section_id'
    ];

    // Relationship with section
    public function section(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    // Relationship with invoices (if needed)
    public function invoice()
    {
        return $this->hasMany(Invoice::class);
    }
}