<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Adminhtml\Group;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'DaoNguyen_Community::group_save';

    /**
     * @var PageFactory
     */
    private PageFactory $pageFactory;

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
    }

    /**
     * Execute request.
     *
     * @return Page
     */
    public function execute(): Page
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('DaoNguyen_Community::group');
        $resultPage->addBreadcrumb(__('Community'), __('Community'));
        $resultPage->addBreadcrumb(__('Manage Groups'), __('Manage Groups'));
        $resultPage->getConfig()->getTitle()->prepend(__('Groups'));

        return $resultPage;
    }
}
