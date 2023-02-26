<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Group;

use DaoNguyen\Community\Model\GroupRepository;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class View extends Action implements HttpGetActionInterface
{
    private GroupRepository $groupRepository;

    private Registry $registry;

    private PageFactory $pageFactory;

    public function __construct(
        GroupRepository $groupRepository,
        Registry $registry,
        PageFactory $pageFactory,
        Context $context
    ) {
        parent::__construct($context);
        $this->groupRepository = $groupRepository;
        $this->registry = $registry;
        $this->pageFactory = $pageFactory;
    }

    public function execute()
    {
        $groupId = (int) $this->getRequest()->getParam('id', false);
        if ($groupId) {
            try {
                $group = $this->groupRepository->getBydId($groupId);
                $this->registry->register('group', $group);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $this->_redirect('community');
            }
            $page = $this->pageFactory->create();
            $page->getConfig()->getTitle()->set($group->getName());
            return $page;
        }
        return $this->_redirect('community');
    }
}
