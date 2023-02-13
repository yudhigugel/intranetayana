<?php

namespace App\Models\Zapier;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use Validator;

class ZapierVCCLog extends Model
{
    protected $table = 'ZAPIER_VCC_LOG';
    protected $primaryKey = 'SEQ_ID';
    public $timestamps = false;
    protected $connection = 'dbintranet'; // set connection default untuk model ini
    protected $guarded = [];


}


