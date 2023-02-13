<?php

namespace DaoNguyen\Community\Model\ResourceModel\Activity;

use DaoNguyen\Community\Model\Activity as Model;
use DaoNguyen\Community\Model\ResourceModel\Activity as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'community_activity_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
