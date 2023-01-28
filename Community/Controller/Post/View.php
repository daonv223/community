<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Post;

use DaoNguyen\Community\Model\PostRepository;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\Manager;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class View implements HttpGetActionInterface
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
     * @param PageFactory $pageFactory
     * @param RequestInterface $request
     * @param RedirectFactory $redirectFactory
     * @param PostRepository $postRepository
     * @param Manager $messageManager
     * @param Registry $registry
     */
    public function __construct(
        PageFactory $pageFactory,
        RequestInterface $request,
        RedirectFactory $redirectFactory,
        PostRepository $postRepository,
        Manager $messageManager,
        Registry $registry
    ) {
        $this->pageFactory = $pageFactory;
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->postRepository = $postRepository;
        $this->messageManager = $messageManager;
        $this->registry = $registry;
    }

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $idPost = (int) $this->request->getParam('id');
        if ($idPost) {
            try {
                $post = $this->postRepository->getById($idPost);
                $this->registry->register('post', $post);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $this->redirectFactory->create()->setPath('community');
            }
            $page = $this->pageFactory->create();
            $page->getConfig()->getTitle()->set($post->getSubject());
            return $page;
        }
        return $this->redirectFactory->create()->setPath('community');
    }
}
