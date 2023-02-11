<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Post\Search\Navigation;

use DaoNguyen\Community\Model\Post\Search\FilterItem;
use DaoNguyen\Community\Model\Post\Search\Layer;
use Magento\Framework\View\Element\Template;

class State extends Template
{
    protected $_template = 'DaoNguyen_Community::post/search/navigation/state.phtml';

    /**
     * @var Layer
     */
    private Layer $layer;

    public function __construct(
        Layer $layer,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->layer = $layer;
    }

    /**
     * @return FilterItem[]
     */
    public function getActiveFilters()
    {
        return $this->layer->getState()->getFilters();
    }

    public function getClearUrl()
    {
        $filterState = [];
        foreach ($this->getActiveFilters() as $item) {
            $filterState[$item->getFilter()->getRequestVar()] = null;
        }
        $params['_current'] = true;
        $params['_use_rewrite'] = true;
        $params['_query'] = $filterState;
        $params['_escape'] = true;
        return $this->_urlBuilder->getUrl('*/*/*', $params);
    }
}
