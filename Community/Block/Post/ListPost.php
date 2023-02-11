<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Post;

use DaoNguyen\Community\Model\Post;
use DaoNguyen\Community\Model\Post\PostOrderInterface;
use DaoNguyen\Community\Model\ResourceModel\Post\Collection;
use DaoNguyen\Community\Model\ResourceModel\Post\CollectionFactory;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Theme\Block\Html\Pager;

class ListPost extends Template implements IdentityInterface
{
    /**
     * @var Collection|null
     */
    private ?Collection $collection;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var PostOrderInterface
     */
    private PostOrderInterface $postOrder;

    /**
     * @param CollectionFactory $collectionFactory
     * @param PostOrderInterface $postOrder
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        PostOrderInterface $postOrder,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
        $this->postOrder = $postOrder;
        $this->collection = null;
    }

    /**
     * Get list post.
     *
     * @return Collection
     */
    public function getListPost(): Collection
    {
        if ($this->collection === null) {
            $collection = $this->collectionFactory->create();
            $collection->addFieldToFilter(Post::STATUS, ['eq' => 1]);
            $collection->addReplies();
            $this->postOrder->setOrder($collection);
            $collection->setPageSize(5);
            $collection->setCurPage($this->getCurrentPage());
            $this->collection = $collection;
        }
        return $this->collection;
    }

    public function getPostPreviewHtml(Post $post): string
    {
        /** @var Preview $previewBlock */
        $previewBlock = $this->getChildBlock('community.home.post.preview');
        $previewBlock->setPost($post);
        return $previewBlock->toHtml();
    }

    /**
     * Get identities of block.
     *
     * @return array|string[]
     */
    public function getIdentities(): array
    {
        $identities = [Post::CACHE_TAG];
        foreach ($this->getListPost() as /** @var Post $post */ $post) {
            $identities[] = Post::CACHE_TAG . '_' . $post->getEntityId();
        }
        return $identities;
    }

    public function getPagerHtml()
    {
        /** @var Pager $pagerBlock */
        $pagerBlock = $this->getChildBlock('post_pager');
        $pagerBlock->setFrameLength(
            $this->_scopeConfig->getValue(
                'design/pagination/pagination_frame',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            )
        )->setJump(
            $this->_scopeConfig->getValue(
                'design/pagination/pagination_frame_skip',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            )
        )->setUseContainer(
            true
        )->setShowPerPage(
            false
        )->setShowAmounts(
            false
        )->setLimit(
            5
        )->setCollection(
            $this->getListPost()
        );
        return $pagerBlock->toHtml();
    }

    public function getCurrentPage(): int
    {
        $page = (int) $this->getRequest()->getParam('p');
        return $page ?: 1;
    }
}
