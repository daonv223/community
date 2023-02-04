<?php
declare(strict_types=1);

namespace DaoNguyen\Community\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class PostOrders implements ArgumentInterface
{
    /**
     * Get available orders.
     *
     * @return array
     */
    public function getOrders(): array
    {
        return [
            'most_recent' => __('Most Recent'),
            'popular' => __('Popular'),
            'unanswered' => __('Unanswered')
        ];
    }

    /**
     * Get default order.
     *
     * @return string
     */
    public function getDefaultOrder(): string
    {
        return 'most_recent';
    }
}
