<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\ResourceModel\Media;

use DaoNguyen\Community\Model\Media as Model;
use DaoNguyen\Community\Model\ResourceModel\Media as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'community_media_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
