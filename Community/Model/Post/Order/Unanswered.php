<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Post\Order;

use DaoNguyen\Community\Model\Post\PostOrderInterface;
use DaoNguyen\Community\Model\ResourceModel\Post\Collection;

class Unanswered implements PostOrderInterface
{
    public function setOrder(Collection $collection)
    {
        $collection->setOrder('replies', 'ASC');
    }
}
