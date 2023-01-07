<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Adminhtml\Group\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton implements ButtonProviderInterface
{
    /**
     * Retrieve button-specified settings.
     *
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Delete Group'),
            'class' => 'delete',
            'sort_order' => 20
        ];
    }
}
