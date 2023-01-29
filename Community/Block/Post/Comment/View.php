<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Post\Comment;

use DaoNguyen\Community\Helper\MemberHelper;
use DaoNguyen\Community\Model\Comment;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;

class View extends Template
{
    /**
     * Template of block.
     *
     * @var string
     */
    protected $_template = 'DaoNguyen_Community::post/comment/view.phtml';

    /**
     * @var Comment
     */
    private Comment $comment;

    /**
     * @var MemberHelper
     */
    private MemberHelper $memberHelper;

    /**
     * @param MemberHelper $memberHelper
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        MemberHelper $memberHelper,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->memberHelper = $memberHelper;
    }

    /**
     * Get rendered comment.
     *
     * @return Comment
     */
    public function getComment(): Comment
    {
        return $this->comment;
    }

    /**
     * Set rendered comment.
     *
     * @param Comment $comment
     */
    public function setComment(Comment $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * Retrieve avatar author url of comment.
     *
     * @return string
     */
    public function getAvatarAuthorUrl(): string
    {
        try {
            $author = $this->getComment()->getMember();
            return $this->memberHelper->getMemberAvatarUrl($author);
        } catch (NoSuchEntityException) {
            return $this->getViewFileUrl('DaoNguyen_Community::images/avatar-default.png');
        }
    }
}
