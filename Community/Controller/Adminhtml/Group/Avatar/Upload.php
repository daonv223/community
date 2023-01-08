<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Adminhtml\Group\Avatar;

use DaoNguyen\Community\Model\Media\GroupAvatarUploader;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Upload extends Action implements HttpPostActionInterface
{
    /**
     * @var GroupAvatarUploader
     */
    private GroupAvatarUploader $avatarUploader;

    /**
     * @param Context $context
     * @param GroupAvatarUploader $avatarUploader
     */
    public function __construct(Context $context, GroupAvatarUploader $avatarUploader)
    {
        parent::__construct($context);
        $this->avatarUploader = $avatarUploader;
    }

    /**
     * Upload avatar media action.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $imageId = $this->_request->getParam('param_name', 'avatar_path');
        try {
            $result = $this->avatarUploader->saveAvatarToTmpDir($imageId);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
