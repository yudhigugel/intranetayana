<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use Validator;

class TerritoryModel extends Model
{   
    protected $table = 'INT_TERRITORY';
    protected $primaryKey = 'TERRITORY_ID';
    public $incrementing = false; // PENTING supaya tau kalau primary key nya itu bukan auto increment
    // public $timestamps = false;
    const CREATED_AT = 'CREATED_DATE';
    const UPDATED_AT = 'MODIFIED_DATE';
    protected $connection = 'dbintranet'; // set connection default untuk model ini
    protected $guarded = [];
    

}