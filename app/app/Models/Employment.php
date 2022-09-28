<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employment extends Model
{
    use HasFactory;

    protected $primaryKey = 'employment_id';

    protected $fillable = [
        'id',
        'company_name',
        'job_title',
        'start_date',
        'end_date',
        'user_id'
    ];

    public $timestamps = false;
}
