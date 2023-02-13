<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use Validator;

class BusinessCompanyModel extends Model
{   

    protected $table = 'INT_MAP_BUSINESS_COMPANY_DETAIL';
    protected $primaryKey = 'BCD_ID';
    public $timestamps = false;
    // const CREATED_AT = 'CREATED_DATE';
    // const UPDATED_AT = 'MODIFIED_DATE';
    protected $connection = 'dbintranet'; // set connection default untuk model ini
    protected $guarded = [];

}