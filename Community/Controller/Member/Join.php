<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Member;

use DaoNguyen\Community\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\SessionException;
use Magento\Framework\Message\Manager;

class Join implements HttpPostActionInterface
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
    public function execute(): Redirect
    {
        try {
            $currentMember = $this->session->getCurrentMember();
            $groupId = $this->request->getParam('group_id');
            $currentMember->joinGroups([$groupId]);
            $this->messageManager->addSuccessMessage('You joined this group successfully.');
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->redirectFactory->create()->setPath('community/member');
        } catch (SessionException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->redirectFactory->create()->setPath('customer/account/login');
        }
        return $this->redirectFactory->create()->setPath('community/group/all');
    }
}
