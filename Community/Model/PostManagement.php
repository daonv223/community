<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

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
}
