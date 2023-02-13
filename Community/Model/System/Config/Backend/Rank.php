<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\System\Config\Backend;

use DaoNguyen\Community\Helper\RankSystem;

class Rank extends \Magento\Framework\App\Config\Value
{
    /**
     * @var RankSystem
     */
    private RankSystem $rankSystemHelper;

    public function __construct(
        RankSystem $rankSystem,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->rankSystemHelper = $rankSystem;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }


    public function beforeSave()
    {
        $value = $this->getValue();
        $value = $this->rankSystemHelper->makeStorableArrayFieldValue($value);
        $this->setValue($value);
    }

    public function _afterLoad()
    {
        $value = $this->getValue();
        if ($value !== null) {
            $value = $this->rankSystemHelper->makeArrayFieldValue($value);
            $this->setValue($value);
        }
    }
}
