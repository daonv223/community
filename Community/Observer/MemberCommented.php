<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Observer;

use DaoNguyen\Community\Model\Activity;
use DaoNguyen\Community\Model\Comment;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use DaoNguyen\Community\Model\ActivityFactory;

class MemberCommented implements ObserverInterface
{
    private ActivityFactory $activityFactory;

    /**
     * @param ActivityFactory $activityFactory
     */
    public function __construct(ActivityFactory $activityFactory)
    {
        $this->activityFactory = $activityFactory;
    }

    public function execute(Observer $observer)
    {
        /** @var Comment $comment */
        $comment = $observer->getEvent()->getObject();
        if ($comment->isObjectNew()) {
            $activity = $this->activityFactory->create();
            $activity->setActorId($comment->getMemberId());
            $activity->setAction(Activity::ACTIVITY_ACTION_COMMENT);
            $activity->setEntity($comment->getEntityId());
            $activity->setDataObject($comment);
            $activity->saveActivity();
        }
    }
}
