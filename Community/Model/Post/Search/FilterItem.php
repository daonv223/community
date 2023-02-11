<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Post\Search;

use Magento\Framework\DataObject;
use Magento\Framework\UrlInterface;

class FilterItem extends DataObject
{
    protected UrlInterface $url;

    public function __construct(
        UrlInterface $url,
        array $data = []
    ) {
        parent::__construct($data);
        $this->url = $url;
    }

    public function getUrl()
    {
        $query = [
            $this->getFilter()->getRequestVar() => $this->getValue(),
            'q' => null,
        ];
        return $this->url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);
    }

    /**
     * @return FilterInterface
     */
    public function getFilter()
    {
        return $this->getData('filter');
    }

    public function getName()
    {
        return $this->getFilter()->getName();
    }

    public function getRemoveUrl()
    {
        $query = [$this->getFilter()->getRequestVar() => null];
        $params['_current'] = true;
        $params['_use_rewrite'] = true;
        $params['_query'] = $query;
        $params['_escape'] = true;
        return $this->url->getUrl('*/*/*', $params);
    }
}
