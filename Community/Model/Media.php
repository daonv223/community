<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Model\ResourceModel\Media as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class Media extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'community_media_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel::class);
    }
}
