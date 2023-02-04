<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Post;

use Magento\Framework\View\Element\Template;

class Orders extends Template
{
    /**
     * Is order current.
     *
     * @param string $value
     * @return bool
     */
    public function isOrderCurrent(string $value): bool
    {
        $currentOrder = $this->getRequest()->getParam('post_list_order', false);
        if ($currentOrder) {
            return $value === $currentOrder;
        } elseif ($value === $this->getPostOrders()->getDefaultOrder()) {
            return true;
        }
        return false;
    }

    /**
     * Get order link.
     *
     * @param string $value
     * @return string
     */
    public function getOrderLink(string $value): string
    {
        return $this->_urlBuilder->getUrl('community') . '?post_list_order=' . $value;
    }
}
