<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMenuAccess extends Model
{
    use HasFactory;
    protected $table = 'INT_MASTER_MENU_ACCESS';
    protected $primaryKey = 'SEQ_ID';
    const CREATED_AT = 'DATE_CREATED';
    const UPDATED_AT = 'DATE_MODIFIED';
    protected $connection = 'dbintranet'; // set connection default untuk model ini
    protected $guarded = [];
}
