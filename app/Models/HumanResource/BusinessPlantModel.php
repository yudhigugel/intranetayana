<?php

namespace App\Models\HumanResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use Validator;

class BusinessPlantModel extends Model
{   
    use HasFactory;
    protected $table = 'INT_BUSINESS_PLANT';
    protected $primaryKey = 'SAP_PLANT_ID';
    public $incrementing = false; // PENTING supaya tau kalau primary key nya itu bukan auto increment
    // public $timestamps = false;
    const CREATED_AT = 'CREATED_DATE';
    const UPDATED_AT = 'MODIFIED_DATE';
    protected $connection = 'dbintranet'; // set connection default untuk model ini
    protected $guarded = [];

}