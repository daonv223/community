<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Adminhtml\Group\Edit;

use Magento\Backend\Block\Widget\Context;

class GenericButton
{
    /**
     * @var Context
     */
    protected Context $context;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }
}
