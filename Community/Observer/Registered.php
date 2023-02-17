<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Observer;

use DaoNguyen\Community\Model\Activity;
use DaoNguyen\Community\Model\ActivityFactory;
use DaoNguyen\Community\Model\Member;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Registered implements ObserverInterface
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
        /** @var Member $member */
        $member = $observer->getEvent()->getObject();
        if ($member->isObjectNew()) {
            $activity = $this->activityFactory->create();
            $activity->setActorId($member->getEntityId());
            $activity->setAction(Activity::ACTIVITY_ACTION_REGISTRATION);
            $activity->setEntity($member->getEntityId());
            $activity->setDataObject($member);
            $activity->saveActivity();
        }
    }
}
