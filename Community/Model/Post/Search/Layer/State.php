<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Post\Search\Layer;

use DaoNguyen\Community\Model\Post\Search\FilterItem;
use Magento\Framework\DataObject;

class State extends DataObject
{
    public function addFilter($filter)
    {
        $filters = $this->getFilters();
        $filters[] = $filter;
        $this->setFilters($filters);
        return $this;
    }

    public function setFilters($filters)
    {
        $this->setData('filters', $filters);
        return $this;
    }

    /**
     * @return FilterItem[]
     */
    public function getFilters()
    {
        $filters = $this->getData('filters');
        if ($filters === null) {
            $filters = [];
            $this->setData('filters', $filters);
        }
        return $filters;
    }
}
