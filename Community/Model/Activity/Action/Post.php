<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Activity\Action;

use DaoNguyen\Community\Model\Activity\AbstractAction;
use DaoNguyen\Community\Model\Notification;

class Post extends AbstractAction
{
    protected string $pointConfigPath = 'community_rank_system/actions/post';

    public function createNotifications()
    {
        /** @var \DaoNguyen\Community\Model\Post $post */
        $post = $this->getDataObject();
        /** @var Notification $notification */
        $notification = $this->notificationFactory->create();
        $authorName = $post->getMember()->getNickname();
        $group = $post->getGroup()->getName();
        $message = $authorName . ' posted in ' . $group  . '.';
        $notification->setMessage($message);
        $notification->setHref($this->urlBuilder->getUrl('community/post/view', ['id' => $post->getEntityId()]));
        $notification->setReceiverIds($post->getGroup()->getAllMemberIds());
        $notification->save();
    }
}
