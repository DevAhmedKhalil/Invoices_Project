<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sections extends Model
{
    // Laravel will default to 'sections' as the table name, but if needed, you can specify it like this:
//    protected $table = 'sections';

    protected $fillable = [
        'section_name',
        'description',
        'created_by',
    ];
}
