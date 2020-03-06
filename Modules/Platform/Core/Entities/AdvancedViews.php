<?php

namespace Modules\Platform\Core\Entities;

use Illuminate\Database\Eloquent\Model;


class AdvancedViews extends Model
{

    protected $fillable = [
        'view_name',
        'module_name',
        'is_public',
        'is_accepted',
        'is_default',
        'defined_columns',
        'filter_rules',
        'owner_id'
    ];

    public $table = 'vaance_advanced_views';

    protected $casts = [

    ];

    public function isVisible(){

        if($this->is_public && $this->is_accepted){
            return true;
        }
        if(!$this->is_public && $this->owner_id == \Auth::user()->id){
            return true;
        }

    }

}
