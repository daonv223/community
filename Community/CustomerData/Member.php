<?php
declare(strict_types=1);

namespace DaoNguyen\Community\CustomerData;

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
     * @param CurrentCustomer $currentCustomer
     * @param Session $memberSession
     */
    public function __construct(CurrentCustomer $currentCustomer, Session $memberSession)
    {
        $this->currentCustomer = $currentCustomer;
        $this->memberSession = $memberSession;
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
        } catch (Exception) {
            return [];
        }
    }
}
