<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Observer;

use DaoNguyen\Community\Model\Activity;
use DaoNguyen\Community\Model\ActivityFactory;
use DaoNguyen\Community\Model\Post;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Posted implements ObserverInterface
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
        /** @var Post $post */
        $post = $observer->getEvent()->getObject();
        if ($post->isObjectNew()) {
            $activity = $this->activityFactory->create();
            $activity->setActorId($post->getMemberId());
            $activity->setAction(Activity::ACTIVITY_ACTION_POST);
            $activity->setEntity($post->getEntityId());
            $activity->saveActivity();
        }
    }
}
