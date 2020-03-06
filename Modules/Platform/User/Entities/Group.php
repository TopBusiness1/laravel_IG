<?php

namespace Modules\Platform\User\Entities;

use Cog\Contracts\Ownership\CanBeOwner;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

class Group extends CachableModel implements CanBeOwner
{
    use SoftDeletes;

    public $table = 'groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
