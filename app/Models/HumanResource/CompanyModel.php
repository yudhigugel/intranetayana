<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use Validator;

class CompanyModel extends Model
{   
    protected $table = 'INT_COMPANY';
    protected $primaryKey = 'COMPANY_CODE';
    public $incrementing = false; // PENTING supaya tau kalau primary key nya itu bukan auto increment
    // public $timestamps = false;
    const CREATED_AT = 'CREATED_DATE';
    const UPDATED_AT = 'MODIFIED_DATE';
    protected $connection = 'dbintranet'; // set connection default untuk model ini
    protected $guarded = [];
    

}