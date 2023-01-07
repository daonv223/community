<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Adminhtml\Group;

use DaoNguyen\Community\Model\Group;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'DaoNguyen_Community::group_save';

    /**
     * @var PageFactory
     */
    private PageFactory $pageFactory;

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(Context $context, PageFactory $pageFactory)
    {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
    }

    /**
     * Execute the request.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $groupId = $this->getRequest()->getParam('entity_id', false);
        $group = $this->_objectManager->create(Group::class);
        if ($groupId) {
            $group->load($groupId);
            if (!$group->getId()) {
                $this->messageManager->addErrorMessage(__('This group no longer exists'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        /** @var Page $resultPage */
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('DaoNguyen_Community::group')
            ->addBreadcrumb(__('Community'), __('Community'))
            ->addBreadcrumb(__('Manage Groups'), __('Manage Groups'))
            ->addBreadcrumb(
                $groupId ? __('Edit Group') : __('New Group'),
                $groupId ? __('Edit Group') : __('New Group')
            );
        $resultPage->getConfig()->getTitle()->prepend(__('Groups'));
        $resultPage->getConfig()->getTitle()
            ->prepend($group->getId() ? $group->getName() : __('New Group'));
        return $resultPage;
    }
}
