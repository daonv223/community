<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Post\Search;

use DaoNguyen\Community\Model\Post\Search\FilterInterface;
use DaoNguyen\Community\Model\Post\Search\FilterList;
use DaoNguyen\Community\Model\Post\Search\Layer;
use Magento\Framework\View\Element\Template;

class Navigation extends Template
{
    private FilterList $filterList;

    /**
     * @var Layer
     */
    private Layer $layer;

    public function __construct(
        FilterList $filterList,
        Layer $layer,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->filterList = $filterList;
        $this->layer = $layer;
    }

    protected function _prepareLayout()
    {
        foreach ($this->filterList->getFilters() as $filter) {
            $filter->apply($this->getRequest());
        }
        return parent::_prepareLayout();
    }

    /**
     * @return FilterInterface[]
     */
    public function getFilters()
    {
        return $this->filterList->getFilters();
    }

    public function getLayer()
    {
        return $this->layer;
    }

    public function getClearUrl()
    {
        return $this->getChildBlock('state')->getClearUrl();
    }
}
