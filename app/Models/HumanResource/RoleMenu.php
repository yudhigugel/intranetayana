<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleMenu extends Model
{
    use HasFactory;
    protected $table = 'INT_ROLE_MENU';
    protected $primaryKey = 'SEQ_ID';
    public $timestamps = false;
    protected $connection = 'dbintranet'; // set connection default untuk model ini
    protected $guarded = [];
}
