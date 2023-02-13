<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionModel extends Model
{
    use HasFactory;
    protected $table = 'INT_PERMISSIONS';
    protected $primaryKey = 'SEQ_ID';
    protected $connection = 'dbintranet'; // set connection default untuk model ini
    protected $guarded = [];
}
