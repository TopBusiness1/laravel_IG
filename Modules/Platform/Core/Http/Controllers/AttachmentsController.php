<?php

namespace Modules\Platform\Core\Http\Controllers;

use Carbon\Carbon;
use Cog\Contracts\Ownership\Ownable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Modules\Core\Notifications\GenericNotification;
use Modules\Platform\Core\Helper\FileHelper;
use Modules\Platform\Core\Http\Requests\AttachmentRequest;
use Modules\Platform\Core\Repositories\AttachmentsRepository;
use Modules\Platform\Notifications\Entities\NotificationPlaceholder;
use Modules\Platform\User\Entities\User;

class AttachmentsController extends AppBaseController
{

    /**
     * @var AttachmentsRepository
     */
    private $attachmentRepository;

    /**
     * AttachmentsController constructor.
     * @param AttachmentsRepository $repo
     */
    public function __construct(AttachmentsRepository $repo)
    {
        parent::__construct();

        $this->attachmentRepository = $repo;
    }

    /**
     * @param $entityClass
     * @param $entityId
     * @param $key
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAttachment($entityClass, $entityId, $key)
    {
        $user = \Auth::user();

        $entityClass = str_replace('&quot;', '', $entityClass);
        $entityClass = str_replace('"','',$entityClass);
        $entityClass = str_replace('_','\\',$entityClass);

        $entity = app($entityClass)->find($entityId);


        if ($entity != null) {
            $attachment = $entity->attachment($key);

            if ($attachment != null) {
                $result = $attachment->delete();

                if ($result) {
                    $message = 'attachment_deleted';
                } else {
                    $message = 'error_while_deleting_attachment';
                }

                return \Response::json([
                    'message' => $message,
                ]);
            }
        } else {
            return \Response::json([
                'message' => 'entity_not_found'
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttachments(Request $request)
    {
        $user = \Auth::user();

        $entityClass = $request->get('entityClass');
        $entityId = $request->get('entityId');

        $entityClass = str_replace('&quot;', '', $entityClass);
        $entityClass = str_replace('"','',$entityClass);
        

        $entity = app($entityClass)->find($entityId);


        if ($entity != null) {
            $files = [];

            foreach ($entity->attachments as $attachment) {
                $files[] = $this->prepareAttachment($attachment, $entity, $entityId);
            }

            return \Response::json([
                'files' => $files,
            ]);
        }

        return \Response::json([
            'message' => 'no_attachments_found'
        ]);
    }

    /**
     * @param $attachment
     * @param $entity
     * @param $entityId
     * @return array
     */
    private function prepareAttachment($attachment, $entity, $entityId)
    {
        $entityId = $entity->id;
        $entityClass = get_class($entity);
        $entityClass = str_replace('\\', '_', $entityClass);
        // return ['entityClass'=>$entityClass];

        return [

            'url' => $attachment->url,
            'thumbnailUrl' => FileHelper::displayAttachmentIcon($attachment),
            'name' => $attachment->filename,
            'type' => $attachment->filetype,
            'size' => $attachment->filesize,
            'deleteUrl' => route('core.ext.attachments.delete-attachment', [
                'entityClass' => $entityClass,
                'entityId' => $entityId,
                'key' => $attachment->key
            ]),
            'deleteType' => 'delete'
        ];
    }

    /**
     * Attachment request
     *
     * @param AttachmentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAttachments(AttachmentRequest $request)
    {
        $user = \Auth::user();

        if (config('vaance.demo')) {
            return \Response::json([
                'files' => [
                    [
                        'url' => '/vaance/images/file_icon.png',
                        'thumbnailUrl' => '/vaance/images/file_icon.png',
                        'name' => trans('core::core.you_cant_do_that_its_demo'),
                        'size' => 1234,
                        'type' => 'png'
                    ]
                ],
            ]);
        }

        $entityClass = $request->get('entityClass');
        $entityId = $request->get('entityId');

        $entityClass = str_replace('&quot;', '', $entityClass);


        $entity = app($entityClass)->find($entityId);

        $path = $request->get('path');

        if ($entity != null) {
            $attachment = $entity->attach(\Request::file('files'));


            $files[] = $this->prepareAttachment($attachment, $entity, $entityId);

            if(config('vaance.attachment_notification_enabled')) { // Check if attachment notification is enabled
                if ($entity instanceof Ownable) { // Entity is ownable and we can send notification

                    if ($entity->getOwner() instanceof User) {

                        if($entity->getOwner()->id != \Auth::user()->id) { // Dont send notification for myself
                            try {
                                $commentOn = $entity->name;
                                $commentOn = ' ' . trans('core::core.on') . ' ' . $commentOn;
                            } catch (\Exception $exception) {
                                $commentOn = '';
                            }

                            $placeholder = new NotificationPlaceholder()
                            ;

                            $placeholder->setRecipient($entity->getOwner());
                            $placeholder->setAuthorUser($user);
                            $placeholder->setAuthor($user->name);
                            $placeholder->setColor('bg-deep-orange');
                            $placeholder->setIcon('attach_file');
                            $placeholder->setContent(trans('notifications::notifications.new_attachment', ['user' => $user->name]) . $commentOn);

                            $placeholder->setUrl($path);

                            $entity->getOwner()->notify(new GenericNotification($placeholder));
                        }
                    }
                }
            }

            return \Response::json([
                'files' => $files,
            ]);
        } else {
            return \Response::json([
                'message' => 'entity_not_found'
            ]);
        }
    }
}
