<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Post;

use DaoNguyen\Community\Model\GroupRepository;
use DaoNguyen\Community\Model\Post;
use DaoNguyen\Community\Model\PostManagement;
use DaoNguyen\Community\Model\PostRepository;
use DaoNguyen\Community\Model\Session;
use Exception;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\Manager;
use DaoNguyen\Community\Model\PostFactory;

class Save implements HttpPostActionInterface
{
    /**
     * @var RedirectFactory
     */
    private RedirectFactory $redirectFactory;

    /**
     * @var Session
     */
    private Session $memberSession;

    /**
     * @var Manager
     */
    private Manager $messageManager;

    /**
     * @var RequestInterface
     */
    private RequestInterface $requestInterface;

    /**
     * @var PostFactory
     */
    private PostFactory $postFactory;

    /**
     * @var GroupRepository
     */
    private GroupRepository $groupRepository;

    /**
     * @var PostRepository
     */
    private PostRepository $postRepository;

    /**
     * @var PostManagement
     */
    private PostManagement $postManagement;

    /**
     * @param RedirectFactory $redirectFactory
     * @param Session $memberSession
     * @param Manager $messageManager
     * @param RequestInterface $requestInterface
     * @param PostFactory $postFactory
     * @param GroupRepository $groupRepository
     * @param PostRepository $postRepository
     * @param PostManagement $postManagement
     */
    public function __construct(
        RedirectFactory $redirectFactory,
        Session $memberSession,
        Manager $messageManager,
        RequestInterface $requestInterface,
        PostFactory $postFactory,
        GroupRepository $groupRepository,
        PostRepository $postRepository,
        PostManagement $postManagement
    ) {
        $this->redirectFactory = $redirectFactory;
        $this->memberSession = $memberSession;
        $this->messageManager = $messageManager;
        $this->requestInterface = $requestInterface;
        $this->postFactory = $postFactory;
        $this->groupRepository = $groupRepository;
        $this->postRepository = $postRepository;
        $this->postManagement = $postManagement;
    }

    /**
     * Handle create post requests.
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        try {
            $post = $this->postFactory->create();
            $data = $this->requestInterface->getParams();
            $post->setData($data);
            $currentMember = $this->memberSession->getCurrentMember();
            $post->setMemberId((int) $currentMember->getEntityId());
            $group = $this->groupRepository->getBydId((int) $data['group_id']);
            if (!$group->isActive()) {
                $this->messageManager->addErrorMessage('The group is not active!');
                return $this->redirectFactory->create()->setPath('community');
            }
            if ($group->getAutoApprove()) {
                $post->setStatus(Post::APPROVED);
            } else {
                $post->setStatus(Post::NOT_APPROVED);
            }
            $this->postRepository->save($post);
            $this->messageManager->addSuccessMessage(__('You saved the post.'));
            $this->postManagement->saveAssociatedProducts($post);
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $this->redirectFactory->create()->setPath('community');
    }
}
