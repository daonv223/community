<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\ResourceModel\Post;

use DaoNguyen\Community\Model\Post as Model;
use DaoNguyen\Community\Model\ResourceModel\Post as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'community_post_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }

    public function addReplies()
    {
        $this->getSelect()->joinLeft(
            ['cc' => 'community_comment'],
            'main_table.entity_id = cc.post_id',
            ['count(cc.entity_id) AS replies']
        )->group(
            [
                'main_table.entity_id',
                'main_table.member_id',
                'main_table.group_id',
                'main_table.subject',
                'main_table.content',
                'main_table.status',
                'main_table.is_subscribed',
                'main_table.views',
                'main_table.created_at',
                'main_table.updated_at'
            ]
        );
    }
}
