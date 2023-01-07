<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Adminhtml\Group;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Forward;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;

class NewAction extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'DaoNguyen_Community::save';

    /**
     * @var ForwardFactory
     */
    private ForwardFactory $forwardFactory;

    /**
     * @param Context $context
     * @param ForwardFactory $forwardFactory
     */
    public function __construct(Context $context, ForwardFactory $forwardFactory)
    {
        parent::__construct($context);
        $this->forwardFactory = $forwardFactory;
    }

    /**
     * Execute the request.
     *
     * @return Forward
     */
    public function execute(): Forward
    {
        $resultForward = $this->forwardFactory->create();
        return $resultForward->forward('edit');
    }
}
