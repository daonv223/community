<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Member extends AbstractDb
{
    /**
     * @var string
     */
    protected string $_eventPrefix = 'community_member_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('community_member', 'entity_id');
    }
}
