<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Post\PostList;

use DaoNguyen\Community\Model\Post\PostOrderInterface;
use DaoNguyen\Community\Model\ResourceModel\Post\Collection;
use DaoNguyen\Community\ViewModel\PostOrders;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Magento\Theme\Block\Html\Pager;

class Toolbar extends Template
{
    public const PAGE_PARAM_NAME = 'p';
    public const PAGE_SIZE = 5;
    public const ORDER_PARAM_NAME = 'post_list_order';
    public const DIRECTION_PARAM_NAME = 'post_list_dir';

    /**
     * @var Collection
     */
    private $postCollection;

    public function __construct(
        PostOrderInterface $postOrder,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function setCollection($collection)
    {
        $this->postCollection = $collection;
        $this->postCollection->setCurPage($this->getCurrentPage());
        $this->postCollection->setPageSize(self::PAGE_SIZE);
        $this->postCollection->addReplies();
        $currentOrder = $this->getRequest()->getParam(self::ORDER_PARAM_NAME, $this->getPostOrdersViewModel()->getDefaultOrder());
        $this->postCollection->setOrder(
            PostOrders::MAP[$currentOrder],
            $this->getCurrentDirection()
        );
        return $this;
    }

    public function getCollection()
    {
        return $this->postCollection;
    }

    public function getCurrentPage()
    {
        $page = (int) $this->getRequest()->getParam(self::PAGE_PARAM_NAME);
        return $page ?: 1;
    }

    /**
     * Return last page number.
     *
     * @return int
     */
    public function getLastPageNum()
    {
        return $this->getCollection()->getLastPageNumber();
    }

    /**
     * Pager number of items from which products started on current page.
     *
     * @return int
     */
    public function getFirstNum()
    {
        $collection = $this->getCollection();
        return $collection->getPageSize() * ($collection->getCurPage() - 1) + 1;
    }

    /**
     * Pager number of items products finished on current page.
     *
     * @return int
     */
    public function getLastNum()
    {
        $collection = $this->getCollection();
        return $collection->getPageSize() * ($collection->getCurPage() - 1) + $collection->count();
    }

    /**
     * Total number of products in current category.
     *
     * @return int
     */
    public function getTotalNum()
    {
        return $this->getCollection()->getSize();
    }

    /**
     * @return array
     */
    public function getOrders()
    {
        return $this->getPostOrdersViewModel()->getOrders();
    }

    /**
     * @param $order
     * @return bool
     */
    public function isOrderCurrent($order)
    {
        $currentOrder = $this->getRequest()->getParam(self::ORDER_PARAM_NAME, false);
        if ($currentOrder) {
            return $order === $currentOrder;
        } elseif ($order === $this->getPostOrdersViewModel()->getDefaultOrder()) {
            return true;
        }
        return false;
    }

    public function  getCurrentDirection()
    {
        $dir = $this->getRequest()->getParam(self::DIRECTION_PARAM_NAME, false);
        if (!$dir) {
            return 'desc';
        }
        return $dir;
    }

    public function getWidgetOptionsJson()
    {
        $options = [
            'direction' => self::DIRECTION_PARAM_NAME,
            'order' => self::ORDER_PARAM_NAME,
            'directionDefault' => 'desc',
            'orderDefault' => 'most_recent',
            'url' => $this->getPagerUrl()
        ];
        return json_encode($options);
    }

    /**
     * Return current URL with rewrites and additional parameters
     *
     * @param array $params Query parameters
     * @return string
     */
    public function getPagerUrl($params = [])
    {
        $urlParams = [];
        $urlParams['_current'] = true;
        $urlParams['_escape'] = false;
        $urlParams['_use_rewrite'] = true;
        $urlParams['_query'] = $params;
        return $this->getUrl('*/*/*', $urlParams);
    }

    public function getPagerHtml()
    {
        $pagerBlock = $this->getChildBlock('post_list_toolbar_pager');
        if ($pagerBlock instanceof DataObject) {
            /** @var Pager $pagerBlock */
            $pagerBlock->setShowPerPage(
                false
            )->setShowPerPage(
                false
            )->setShowAmounts(
                false
            )->setFrameLength(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            )->setJump(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame_skip',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            )->setLimit(
                self::PAGE_SIZE
            )->setCollection(
                $this->getCollection()
            );
            return $pagerBlock->toHtml();
        }
        return '';
    }
}
