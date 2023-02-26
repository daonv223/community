<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Member;

use DaoNguyen\Community\Model\Group;
use DaoNguyen\Community\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\SessionException;
use Magento\Framework\Message\Manager;
use Magento\PageCache\Model\Cache\Type;

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

    private Type $fullPageCache;

    /**
     * @param RedirectFactory $redirectFactory
     * @param RequestInterface $request
     * @param Session $session
     * @param Manager $messageManager
     * @param Type $fullPageCache
     */
    public function __construct(
        RedirectFactory $redirectFactory,
        RequestInterface $request,
        Session $session,
        Manager $messageManager,
        Type $fullPageCache
    ) {
        $this->redirectFactory = $redirectFactory;
        $this->request = $request;
        $this->session = $session;
        $this->messageManager = $messageManager;
        $this->fullPageCache = $fullPageCache;
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
            $this->fullPageCache->clean(\Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, [Group::CACHE_TAG . '_' . $groupId]);
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
