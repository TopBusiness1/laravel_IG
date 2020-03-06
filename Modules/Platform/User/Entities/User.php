<?php

namespace Modules\Platform\User\Entities;

use Cog\Contracts\Ownership\CanBeOwner;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Platform\Core\Traits\CanComment;
use Modules\Platform\Settings\Entities\Language;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 * @package Modules\Platform\User\Entities
 */
class User extends Authenticatable implements CanBeOwner
{
    use Notifiable, HasRoles, SoftDeletes, CausesActivity, CanComment;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'is_active',
        'last_name',
        'first_name',
        'title',
        'department',
        'office_phone',
        'office_phone',
        'mobile_phone',
        'home_phone',
        'fax',
        'secondary_email',
        'left_panel_hide',
        'theme',
        'address_country',
        'address_state',
        'address_city',
        'address_postal_code',
        'address_street',
        'signature',
        'time_zone',
        'date_format_id',
        'time_format_id',
        'profile_pic_conf',
        'profile_image_path',
        'name',
        'language_id',
        'password',
        'access_to_all_entity',
        'verification_token'

    ];



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at'
    ];


    protected $dates = [
        'created_at',
        'created_at',
        'updated_at'
    ];

    public function dateFormat()
    {
        return $this->belongsTo(DateFormat::class);
    }

    public function timeFormat()
    {
        return $this->belongsTo(TimeFormat::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class)->withTimestamps();
    }

    public function theme()
    {
        return $this->theme;
    }
}
