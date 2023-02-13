<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use Validator;

class CostCenterModel extends Model
{   
    protected $table = 'INT_SAP_COST_CENTER';
    protected $primaryKey = 'SEQ_ID';
    // public $timestamps = false;
    const CREATED_AT = 'CREATED_DATE';
    const UPDATED_AT = 'MODIFIED_DATE';
    protected $connection = 'dbintranet'; // set connection default untuk model ini
    protected $guarded = [];
    

}