<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use Magento\Framework\App\ResourceConnection;

class CommunityManagement
{
    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    public function getCommunityStats(): array
    {
        $data = [];
        $conn = $this->resourceConnection->getConnection();
        $select = $conn->select()
            ->from($conn->getTableName('community_member'), 'count(*)');
        $data['memberCount'] = (int) $conn->fetchOne($select);
        $select = $conn->select()
            ->from($conn->getTableName('community_post'), 'count(*)');
        $data['postCount'] = (int) $conn->fetchOne($select);
        return $data;
    }
}
