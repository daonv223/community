<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Member;

use DaoNguyen\Community\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\Manager;

class Join implements MemberRegistrationInterface, HttpPostActionInterface, HttpGetActionInterface
{
    /**
     * @var RedirectFactory
     */
    private RedirectFactory $redirectFactory;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var Session
     */
    private Session $session;

    /**
     * @var Manager
     */
    private Manager $messageManager;

    /**
     * @param RedirectFactory $redirectFactory
     * @param RequestInterface $request
     * @param Session $session
     * @param Manager $messageManager
     */
    public function __construct(
        RedirectFactory $redirectFactory,
        RequestInterface $request,
        Session $session,
        Manager $messageManager
    ) {
        $this->redirectFactory = $redirectFactory;
        $this->request = $request;
        $this->session = $session;
        $this->messageManager = $messageManager;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $groupId = $this->request->getParam('group_id');
        try {
            $this->session->getCurrentMember()->joinGroups([$groupId]);
            $this->messageManager->addSuccessMessage('You joined this group successfully.');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $this->redirectFactory->create()->setPath('community/group/all');
    }
}
