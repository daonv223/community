<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Post\View;

use DaoNguyen\Community\Helper\MemberHelper;
use DaoNguyen\Community\Model\Post\PostStats;
use DaoNguyen\Community\Model\PostManagement;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;

class StatsBlock extends PostView
{
    /**
     * @var PostManagement
     */
    private PostManagement $postManagement;

    /**
     * @param PostManagement $postManagement
     * @param Registry $registry
     * @param MemberHelper $memberHelper
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        PostManagement $postManagement,
        Registry $registry,
        MemberHelper $memberHelper,
        Context $context,
        array $data = []
    ) {
        parent::__construct($registry, $memberHelper, $context, $data);
        $this->postManagement = $postManagement;
    }

    public function getStats(): PostStats
    {
        return $this->postManagement->getPostStats($this->getPost());
    }
}
