<?php

namespace Modules\Platform\Settings\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Modules\Platform\User\Entities\User;

/**
 * Count users widget for settings
 *
 * Class UserCountWidget
 * @package Modules\Platform\Settings\Widgets
 */
class UserCountWidget extends AbstractWidget
{
    protected $config = [
        'count_active' => true,
        'widget_title' => 'active',
        'color' => 'bg-light-green'
    ];

    public function run()
    {
        $userCount = User::all()->where('is_active', '=', $this->config['count_active'])->count();

        return view('settings::widgets.user_count', [
            'config' => $this->config,
            'userCount' => $userCount
        ]);
    }
}
