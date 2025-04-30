<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    protected $table = 'sections'; // Explicit table name

    protected $fillable = [
        'section_name',
        'description',
        'created_by'     // Should be foreign key to users
    ];

    // Relationship with products
    public function product()
    {
        return $this->hasMany(Product::class);
    }

    // Relationship with creator user
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}