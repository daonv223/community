<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Adminhtml\Group;

use DaoNguyen\Community\Model\Group;
use DaoNguyen\Community\Model\GroupRepository;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'DaoNguyen_Community::group_save';

    /**
     * @var GroupRepository
     */
    private GroupRepository $groupRepository;

    /**
     * @param Context $context
     * @param GroupRepository $groupRepository
     */
    public function __construct(
        Context $context,
        GroupRepository $groupRepository
    ) {
        parent::__construct($context);
        $this->groupRepository = $groupRepository;
    }

    /**
     * Execute the request.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }
            $group = $this->_objectManager->create(Group::class);
            $id = $this->getRequest()->getParam('entity_id');
            if ($id) {
                try {
                    $group = $this->groupRepository->getBydId((int) $id);
                } catch (LocalizedException) {
                    $this->messageManager->addErrorMessage(__('This group no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }
            $group->setData($data);
            try {
                $this->groupRepository->save($group);
                $this->messageManager->addSuccessMessage(__('You saved the group.'));
            } catch (CouldNotSaveException $exception) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the group'));
            }
        }
        return $resultRedirect->setPath('*/*/');
    }
}
