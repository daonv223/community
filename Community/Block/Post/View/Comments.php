<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Post\View;

use DaoNguyen\Community\Block\Post\Comment\View;
use DaoNguyen\Community\Model\Comment;
use DaoNguyen\Community\Model\Post;
use DaoNguyen\Community\Model\ResourceModel\Comment\Collection;
use DaoNguyen\Community\Model\ResourceModel\Comment\CollectionFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Theme\Block\Html\Pager;

class Comments extends Template
{
    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var Registry
     */
    private Registry $registry;

    /**
     * @param CollectionFactory $collectionFactory
     * @param Registry $registry
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        Registry          $registry,
        Template\Context  $context,
        array             $data = []
    )
    {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
        $this->registry = $registry;
    }

    /**
     * Retrieve current post.
     *
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->registry->registry('post');
    }

    /**
     * Get comment collection to render.
     *
     * @return Collection
     */
    public function getCommentCollection(): Collection
    {
        $comments = $this->collectionFactory->create();
        $comments->addFieldToFilter(Comment::POST_ID, ['eq' => $this->getPost()->getEntityId()])
            ->addFieldToFilter(Comment::PARENT_ID, ['null' => true]);
        $comments->setCurPage($this->getCurrentPage());
        $comments->setPageSize(5);
        return $comments;
    }

    /**
     * Render comment html.
     *
     * @param Comment $comment
     * @return string
     */
    public function renderComment(Comment $comment): string
    {
        try {
            /** @var View $blockComment */
            $blockComment = $this->getLayout()->getBlock('community.post.comment');
        } catch (LocalizedException) {
            return '';
        }
        $blockComment->setComment($comment);
        return $blockComment->toHtml();
    }

    public function getSubComments(Comment $comment): array
    {
        $comment->setLevel(0);
        $subComments = [];
        $this->addComments($subComments, $comment);
        return $subComments;
    }

    private function addComments(array &$comments, Comment $comment): void
    {
        $children = $comment->getChildren();
        foreach ($children as /** @var Comment $child */ $child) {
            $level = $comment->getLevel() > 2 ? 3 : $comment->getLevel() + 1;
            $child->setLevel($level);
            $comments[] = $child;
            $this->addComments($comments, $child);
        }
    }

    public function getPagerHtml()
    {
        /** @var Pager $pagerBlock */
        $pagerBlock = $this->getChildBlock('comments_pager');
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
            $this->getCommentCollection()
        );
        return $pagerBlock->toHtml();
    }

    public function getCurrentPage(): int
    {
        $page = (int) $this->getRequest()->getParam('p');
        return $page ?: 1;
    }
}
