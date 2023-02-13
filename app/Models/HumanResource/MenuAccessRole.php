<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuAccessRole extends Model
{
    use HasFactory;
    protected $table = 'INT_ROLE_HAS_ACCESS';
    protected $primaryKey = 'SEQ_ID';
    public $timestamps = false;
    protected $connection = 'dbintranet'; // set connection default untuk model ini
    protected $guarded = [];
}
