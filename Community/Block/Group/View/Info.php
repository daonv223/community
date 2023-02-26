<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Group\View;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class Info extends GroupView
{
    private ResourceConnection $resourceConnection;

    public function __construct(
        ResourceConnection $resourceConnection,
        Registry $registry,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($registry, $context, $data);
        $this->resourceConnection = $resourceConnection;
    }

    public function getMemberCount()
    {
        $conn = $this->resourceConnection->getConnection();
        $select = $conn->select()
            ->from('community_group_member', 'COUNT(*)')
            ->where('group_id = ?', $this->getGroup()->getEntityId());
        return (int) $conn->fetchOne($select);
    }

    public function getPostCount()
    {
        $conn = $this->resourceConnection->getConnection();
        $select = $conn->select()
            ->from('community_post', 'COUNT(*)')
            ->where('group_id = ?', $this->getGroup()->getEntityId());
        return (int) $conn->fetchOne($select);
    }
}
