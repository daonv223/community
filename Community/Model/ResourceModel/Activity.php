<?php

namespace DaoNguyen\Community\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Activity extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'community_activity_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('community_activity', 'activity_id');
    }
}
