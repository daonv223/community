<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Post\Search;

use DaoNguyen\Community\Helper\Post\Search\Data;
use DaoNguyen\Community\Model\ResourceModel\Post\Collection;
use Magento\Framework\View\Element\Template;

class Result extends Template
{
    private $postCollection;

    protected function _prepareLayout()
    {
        $queryText = $this->getRequest()->getParam(Data::QUERY_VAR_NAME);
        $title = __("Search results for: '%1'", $this->_escaper->escapeHtml($queryText));
        $this->pageConfig->getTitle()->set($title);
        return parent::_prepareLayout();
    }

    /**
     * @return ListPost
     */
    public function getListBlock()
    {
        return $this->getChildBlock('post.search.result.list');
    }

    /**
     * @return string
     */
    public function getPostListHtml()
    {
        return $this->getChildHtml('post.search.result.list');
    }

    /**
     * @return Collection
     */
    public function getPostCollection()
    {
        if ($this->postCollection === null) {
            $this->postCollection = $this->getListBlock()->getPostCollection();
        }
        return $this->postCollection;
    }

    public function getResultCount()
    {
        if (!$this->getData('result_count')) {
            $size = $this->getPostCollection()->getSize();
            $this->setResultCount($size);
        }
        return $this->getData('result_count');
    }
}
