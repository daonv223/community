<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Observer;

use DaoNguyen\Community\Model\Activity;
use DaoNguyen\Community\Model\ActivityFactory;
use DaoNguyen\Community\Model\Media;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Uploaded implements ObserverInterface
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
        /** @var Media $media */
        $media = $observer->getEvent()->getObject();
        if ($media->isObjectNew()) {
            $activity = $this->activityFactory->create();
            $activity->setActorId($media->getData('member_id'));
            $activity->setAction(Activity::ACTIVITY_ACTION_UPLOAD_MEDIA);
            $activity->setEntity($media->getData('value_id'));
            $activity->setDataObject($media);
            $activity->saveActivity();
        }
    }
}
