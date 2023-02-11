<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Post\Search;

use Magento\Framework\App\RequestInterface;

interface FilterInterface
{
    public function getName();

    public function getItems();

    public function getRequestVar();

    public function getItemsCount();

    public function apply(RequestInterface $request);
}
