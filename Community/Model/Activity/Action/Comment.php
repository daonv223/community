<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Activity\Action;

use DaoNguyen\Community\Model\Activity\AbstractAction;

class Comment extends AbstractAction
{
    protected string $pointConfigPath = 'community_rank_system/actions/comment';

    public function createNotifications()
    {
        /** @var \DaoNguyen\Community\Model\Comment $comment */
        $comment = $this->getDataObject();
        $commentAuthor = $comment->getMember();
        $postAuthor = $comment->getPost()->getMember();
        if ($commentAuthor->getEntityId() !== $postAuthor->getEntityId()) {
            $postAuthorNotification = $this->notificationFactory->create();
            $message = $commentAuthor->getNickname() . ' commented on your post.';
            $href = $this->urlBuilder->getUrl('community/post/view', ['id' => $comment->getPost()->getEntityId()]);
            $postAuthorNotification->setMessage($message);
            $postAuthorNotification->setHref($href);
            $postAuthorNotification->setReceiverIds([$postAuthor->getEntityId()]);
            $postAuthorNotification->save();
        }
        $parentComment = $comment->getParentComment();
        if ($parentComment !== null && $parentComment->getMember()->getEntityId() !== $commentAuthor->getEntityId()) {
            $parentNotification = $this->notificationFactory->create();
            $message = $commentAuthor->getNickname() . ' replied on your comment.';
            $href = $this->urlBuilder->getUrl('community/post/view', ['id' => $comment->getPost()->getEntityId()]);
            $parentNotification->setMessage($message);
            $parentNotification->setHref($href);
            $parentNotification->setReceiverIds([$parentComment->getMember()->getEntityId()]);
            $parentNotification->save();
        }
    }
}
