<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMenu extends Model
{
    use HasFactory;
    protected $table = 'INT_MASTER_MENU';
    protected $primaryKey = 'SEQ_ID';
    const CREATED_AT = 'DATE_CREATED';
    const UPDATED_AT = 'DATE_MODIFIED';
    // public $timestamps = false;
    protected $connection = 'dbintranet'; // set connection default untuk model ini
    protected $guarded = [];

    public function findChild($IS_PARENT, $SEQ_ID, $populated_menu){
        $relation = $this->whereIn('SEQ_ID', $populated_menu)
        ->where(['IS_PARENT_MENU'=>$IS_PARENT,'PARENT_ID'=>$SEQ_ID, 'IS_ACTIVE'=>1])->get();
        // $relation = DataRoleMenu::whereIn('MENU_ID ')
        return $relation;
    }
}
