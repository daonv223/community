<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Group;

use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;
use DaoNguyen\Community\Model\ResourceModel\Group\CollectionFactory;

class DataProvider extends ModifierPoolDataProvider
{
    public function __construct(
        CollectionFactory $groupCollectionFactory,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
        $this->collection = $groupCollectionFactory->create();
    }
}
