<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Model\ResourceModel\Group as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class Group extends AbstractModel
{
    public const STATUS_ENABLED = 1;
    public const STATUS_DISABLE = 0;
    public const AUTO_APPROVE = 1;
    public const NOT_AUTO_APPROVE = 0;

    /**
     * @var string
     */
    protected $_eventPrefix = 'community_group_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->getData('name');
    }
}
