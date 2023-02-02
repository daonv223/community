<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use DaoNguyen\Community\Model\Post;

class Comment extends AbstractDb
{
    /**
     * @var string
     */
    protected string $_eventPrefix = 'community_comment_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('community_comment', 'entity_id');
    }

    /**
     * Count comments of post.
     *
     * @param Post $post
     * @return int
     */
    public function countComments(Post $post): int
    {
        $conn = $this->getConnection();
        $select = $conn->select()
            ->from($this->getMainTable(), 'COUNT(*)')
            ->where('post_id = ?', $post->getEntityId());
        return (int) $conn->fetchOne($select);
    }
}
