<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Post;

use DaoNguyen\Community\Model\ResourceModel\Post\Collection;

interface PostOrderInterface
{
    /**
     * Order by provided collection.
     *
     * @param Collection $collection
     */
    public function setOrder(Collection $collection);
}
