<?php

namespace DaoNguyen\Community\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Notification extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'community_notification_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('community_notification', 'notification_id');
    }
}
