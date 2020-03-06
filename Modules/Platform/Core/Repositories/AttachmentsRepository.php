<?php

namespace Modules\Platform\Core\Repositories;

use Bnb\Laravel\Attachments\Attachment;

/**
 * Class AttachmentsRepository
 * @package Modules\Platform\Core\Repositories
 */
class AttachmentsRepository extends PlatformRepository
{
    public function model()
    {
        return Attachment::class;
    }

    public function findByKey($key)
    {
        try {
            return $this->findByField('key', $key);
        } catch (\Exception $exception) {
            return null;
        }
    }
}
