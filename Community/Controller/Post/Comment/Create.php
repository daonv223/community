<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Post\Comment;

use DaoNguyen\Community\Controller\Member\MemberRegistrationInterface;
use DaoNguyen\Community\Model\CommentRepository;
use DaoNguyen\Community\Model\PostRepository;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\Manager;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Create implements HttpGetActionInterface, MemberRegistrationInterface
{
    /**
     * @var PageFactory
     */
    private PageFactory $pageFactory;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var RedirectFactory
     */
    private RedirectFactory $redirectFactory;

    /**
     * @var PostRepository
     */
    private PostRepository $postRepository;

    /**
     * @var Manager
     */
    private Manager $messageManager;

    /**
     * @var Registry
     */
    private Registry $registry;

    /**
     * @var CommentRepository
     */
    private CommentRepository $commentRepository;

    /**
     * @param PageFactory $pageFactory
     * @param RequestInterface $request
     * @param RedirectFactory $redirectFactory
     * @param PostRepository $postRepository
     * @param Manager $messageManager
     * @param Registry $registry
     * @param CommentRepository $commentRepository
     */
    public function __construct(
        PageFactory $pageFactory,
        RequestInterface $request,
        RedirectFactory $redirectFactory,
        PostRepository $postRepository,
        Manager $messageManager,
        Registry $registry,
        CommentRepository $commentRepository
    ) {
        $this->pageFactory = $pageFactory;
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->postRepository = $postRepository;
        $this->messageManager = $messageManager;
        $this->registry = $registry;
        $this->commentRepository = $commentRepository;
    }

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $postID = (int) $this->request->getParam('post_id', 0);
        if ($postID) {
            try {
                $post = $this->postRepository->getById($postID);
                $this->registry->register('post', $post);
                $commentId = (int) $this->request->getParam('comment_id', 0);
                if ($commentId) {
                    $comment = $this->commentRepository->getById($commentId);
                    $this->registry->register('comment', $comment);
                }
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $this->redirectFactory->create()->setPath('community');
            }
            return $this->pageFactory->create();
        }
        return $this->redirectFactory->create()->setPath('community');
    }
}
