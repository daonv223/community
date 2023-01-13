<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Post extends AbstractDb
{
    /**
     * @var string
     */
    protected string $_eventPrefix = 'community_post_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('community_post', 'entity_id');
    }
}
