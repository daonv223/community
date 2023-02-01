<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Model\Post\PostStats;
use Magento\Framework\App\ResourceConnection;

class PostManagement
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

    /**
     * Save associated products.
     *
     * @param Post $post
     * @return void
     */
    public function saveAssociatedProducts(Post $post): void
    {
        $content = $post->getContent();
        preg_match_all('/data-product-id="([0-9]+)"/', $content, $matches, PREG_PATTERN_ORDER);
        $productIds = $matches[1];
        $data = [];
        $postId = $post->getEntityId();
        foreach ($productIds as $productId) {
            $data = [
                'post_id' => $postId,
                'product_id' => $productId
            ];
        }
        if ($data) {
            $connection = $this->resourceConnection->getConnection();
            $connection->insertMultiple($connection->getTableName('community_associated_product'), $data);
        }
    }

    public function getPostStats(Post $post): PostStats
    {
        $postStats = new PostStats();
        $postStats->setViews($post->getViews());
        $connection = $post->getResource()->getConnection();
        $select = $connection->select()
            ->from($connection->getTableName('community_comment'), 'COUNT(*)')
            ->where('post_id = ?', $post->getEntityId());
        $postStats->setReplies((int) $connection->fetchOne($select));
        $select = $connection->select()
            ->from($connection->getTableName('community_post_reaction'), 'COUNT(*)')
            ->where('post_id = ?', $post->getEntityId());
        $postStats->setHeartsGiver((int) $connection->fetchOne($select));
        $select = $connection->select()
            ->from($connection->getTableName('community_comment'), 'COUNT(DISTINCT member_id)')
            ->where('post_id = ?', $post->getEntityId());
        $postStats->setContributors((int) $connection->fetchOne($select));
        return $postStats;
    }
}
