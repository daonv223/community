<?php

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Model\ResourceModel\NotificationReceiver as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class NotificationReceiver extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'community_notification_receiver_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
