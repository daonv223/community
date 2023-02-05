<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Post\Order;

use DaoNguyen\Community\Model\Post\PostOrderInterface;
use DaoNguyen\Community\Model\ResourceModel\Post\Collection;

class Popular implements PostOrderInterface
{
    /**
     * @inheritdoc
     */
    public function setOrder(Collection $collection): void
    {
        $collection->setOrder('views');
    }
}
