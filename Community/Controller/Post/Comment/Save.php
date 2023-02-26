<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Post\Comment;

use DaoNguyen\Community\Model\Comment;
use DaoNguyen\Community\Model\CommentManagement;
use DaoNguyen\Community\Model\CommentRepository;
use DaoNguyen\Community\Model\Session;
use Exception;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use DaoNguyen\Community\Model\CommentFactory;
use Magento\Framework\Exception\SessionException;
use Magento\Framework\Message\Manager;

class Save implements HttpPostActionInterface
{
    /**
     * @var RedirectFactory
     */
    private RedirectFactory $redirectFactory;

    /**
     * @var CommentFactory
     */
    private CommentFactory $commentFactory;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var Session
     */
    private Session $memberSession;

    /**
     * @var Manager
     */
    private Manager $messageManager;

    /**
     * @var CommentRepository
     */
    private CommentRepository $commentRepository;

    /**
     * @var CommentManagement
     */
    private CommentManagement $commentManagement;

    /**
     * @param RedirectFactory $redirectFactory
     * @param CommentFactory $commentFactory
     * @param RequestInterface $request
     * @param Session $session
     * @param Manager $messageManager
     * @param CommentRepository $commentRepository
     * @param CommentManagement $commentManagement
     */
    public function __construct(
        RedirectFactory $redirectFactory,
        CommentFactory $commentFactory,
        RequestInterface $request,
        Session $session,
        Manager $messageManager,
        CommentRepository $commentRepository,
        CommentManagement $commentManagement
    ) {
        $this->redirectFactory = $redirectFactory;
        $this->commentFactory = $commentFactory;
        $this->request = $request;
        $this->memberSession = $session;
        $this->messageManager = $messageManager;
        $this->commentRepository = $commentRepository;
        $this->commentManagement = $commentManagement;
    }

    /**
     * Execute the request.
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        $comment = $this->commentFactory->create();
        $comment->setData($this->request->getParams());
        $postId = $this->request->getParam('post_id');
        try {
            $currentMember = $this->memberSession->getCurrentMember();
            $comment->setMemberId($currentMember->getEntityId());
            $this->commentRepository->save($comment);
            $this->commentManagement->sendMailToSubscriber($comment);
            $this->messageManager->addSuccessMessage('You saved the comment');
        } catch (SessionException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->redirectFactory->create()->setPath('customer/account/login');
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $this->redirectFactory->create()->setPath('community/post/view/id/' . $postId);
    }
}
