<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block;

use DaoNguyen\Community\Model\CommunityManagement;
use Magento\Framework\View\Element\Template;

class Intro extends Template
{
    /**
     * @var CommunityManagement
     */
    private CommunityManagement $communityManagement;

    /**
     * @param CommunityManagement $communityManagement
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        CommunityManagement $communityManagement,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->communityManagement = $communityManagement;
    }

    /**
     * Get community name base on settings.
     *
     * @return string
     */
    public function getCommunityName(): string
    {
        return $this->_scopeConfig->getValue('community_general/community_information/name');
    }

    public function getCommunityStat(): array
    {
        return $this->communityManagement->getCommunityStats();
    }
}
