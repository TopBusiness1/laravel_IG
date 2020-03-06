<?php

namespace Modules\Platform\Settings\Repositories;

use Modules\Platform\Core\Repositories\PlatformRepository;
use Modules\Platform\Settings\Entities\Country;
use Modules\Platform\Settings\Entities\Language;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ProvinceRepository
 * @package Modules\Platform\Settings\Repositories
 */
class ProvinceRepository extends PlatformRepository
{
    public function model()
    {
        return Province::class;
    }
}
