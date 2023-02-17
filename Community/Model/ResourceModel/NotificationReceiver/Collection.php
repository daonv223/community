<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\ResourceModel\NotificationReceiver;

use DaoNguyen\Community\Model\NotificationReceiver as Model;
use DaoNguyen\Community\Model\ResourceModel\NotificationReceiver as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'community_notification_receiver_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
