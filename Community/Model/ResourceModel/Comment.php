<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

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
}
