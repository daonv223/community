<?php
declare(strict_types=1);

namespace DaoNguyen\Community\CustomerData;

use DaoNguyen\Community\Model\ResourceModel\NotificationReceiver;
use DaoNguyen\Community\Model\Session;
use Exception;
use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Customer\Helper\Session\CurrentCustomer;

class Member implements SectionSourceInterface
{
    /**
     * @var CurrentCustomer
     */
    private CurrentCustomer $currentCustomer;

    /**
     * @var Session
     */
    private Session $memberSession;

    /**
     * @var NotificationReceiver
     */
    private NotificationReceiver $notificationResource;

    /**
     * @param CurrentCustomer $currentCustomer
     * @param Session $memberSession
     * @param NotificationReceiver $notificationResource
     */
    public function __construct(
        CurrentCustomer $currentCustomer,
        Session $memberSession,
        NotificationReceiver $notificationResource
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->memberSession = $memberSession;
        $this->notificationResource = $notificationResource;
    }

    /**
     * Get member section data.
     *
     * @return array
     */
    public function getSectionData(): array
    {
        if (!$this->currentCustomer->getCustomerId()) {
            return [];
        }
        try {
            $member = $this->memberSession->getCurrentMember();
            $data = $member->getData();
            $data['joined_groups'] = $member->getJoinedGroups();
            $data['liked_posts'] = $member->getLikedPosts();
            return $data;
        } catch (Exception $e) {
            return [];
        }
    }
}
