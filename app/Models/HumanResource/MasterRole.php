<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterRole extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'INT_MASTER_ROLE';
    protected $primaryKey = 'SEQ_ID';
    protected $connection = 'dbintranet'; // set connection default untuk model ini
    protected $guarded = [];

    protected function setPrimaryKey($key)
    {
      $this->primaryKey = $key;
    }

    public function menu(){
        $relation = $this->belongsToMany('App\Models\HumanResource\MasterRole', 'INT_ROLE_MENU', 'ROLE_ID', 'ROLE_ID');
        return $relation;
    }
}
