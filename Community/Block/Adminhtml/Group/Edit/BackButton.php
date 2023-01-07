<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Adminhtml\Group\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class BackButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * Retrieve button-specified settings.
     *
     * @return array
     */
    public function getButtonData(): array
    {
        $backUrl = $this->context->getUrlBuilder()->getUrl('*/*/');
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $backUrl),
            'class' => 'back',
            'sort_order' => 10
        ];
    }
}
