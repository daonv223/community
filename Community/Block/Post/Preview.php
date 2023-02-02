<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Post;

use DaoNguyen\Community\Helper\ContentHelper;
use DaoNguyen\Community\Helper\MemberHelper;
use DaoNguyen\Community\Model\Member;
use DaoNguyen\Community\Model\ResourceModel\Comment;
use DaoNguyen\Community\Model\Post;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Preview extends Template
{
    /**
     * Template of this block.
     *
     * @var string
     */
    protected $_template = 'DaoNguyen_Community::post/preview.phtml';

    /**
     * @var Post
     */
    private Post $post;

    /**
     * @var MemberHelper
     */
    private MemberHelper $memberHelper;

    /**
     * @var ContentHelper
     */
    private ContentHelper $contentHelper;

    /**
     * @var Comment
     */
    private Comment $commentResource;

    /**
     * @var Comment\CollectionFactory
     */
    private Comment\CollectionFactory $commentCollection;

    /**
     * @param MemberHelper $memberHelper
     * @param ContentHelper $contentHelper
     * @param Comment $commentResource
     * @param Comment\CollectionFactory $commentCollection
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        MemberHelper $memberHelper,
        ContentHelper $contentHelper,
        Comment $commentResource,
        Comment\CollectionFactory $commentCollection,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->memberHelper = $memberHelper;
        $this->contentHelper = $contentHelper;
        $this->commentResource = $commentResource;
        $this->commentCollection = $commentCollection;
    }

    /**
     * Get post.
     *
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * Set post.
     *
     * @param Post $post
     */
    public function setPost(Post $post): void
    {
        $this->post = $post;
    }

    /**
     * Retrieve avatar author url of post.
     *
     * @param Member $author
     * @return string
     */
    public function getAvatarAuthorUrl(Member $author): string
    {
        try {
            return $this->memberHelper->getMemberAvatarUrl($author);
        } catch (NoSuchEntityException) {
            return $this->getViewFileUrl('DaoNguyen_Community::images/avatar-default.png');
        }
    }

    /**
     * Truncate content.
     *
     * @param string $content
     * @return string
     */
    public function truncate(string $content): string
    {
        $link = '<a href="' . $this->getUrl('community/post/view', ['id' => $this->getPost()->getEntityId()]) .'">...read more</a>';
        return $this->contentHelper->truncateHtml($content, 150, $link);
    }

    /**
     * Get recent comment.
     *
     * @return \DaoNguyen\Community\Model\Comment
     */
    public function getRecentComment(): \DaoNguyen\Community\Model\Comment
    {
        $commentCollection = $this->commentCollection->create();
        $commentCollection->addFieldToFilter('post_id', ['eq' => $this->getPost()->getEntityId()])
            ->addFieldToFilter('parent_id', ['null' => true])
            ->setOrder('updated_at');
        return $commentCollection->getFirstItem();
    }

    /**
     * Count comment
     *
     * @return int
     */
    public function countComment(): int
    {
        return $this->commentResource->countComments($this->getPost());
    }
}
