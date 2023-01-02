<?php

namespace DaoNguyen\Community\Model\ResourceModel\Member;

use DaoNguyen\Community\Model\Member as Model;
use DaoNguyen\Community\Model\ResourceModel\Member as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'community_member_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
