<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Post\View;

use DaoNguyen\Community\Helper\MemberHelper;
use DaoNguyen\Community\Model\Post;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class PostView extends Template implements IdentityInterface
{
    /**
     * @var Registry
     */
    protected Registry $registry;

    /**
     * @var MemberHelper
     */
    private MemberHelper $memberHelper;

    /**
     * @param Registry $registry
     * @param MemberHelper $memberHelper
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Registry $registry,
        MemberHelper $memberHelper,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->memberHelper = $memberHelper;
    }

    /**
     * Retrieve current post model.
     *
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->registry->registry('post');
    }

    /**
     * Get group name.
     *
     * @return string
     */
    public function getGroupName(): string
    {
        try {
            $group = $this->getPost()->getGroup();
            return $group->getName();
        } catch (NoSuchEntityException) {
            return 'Unknown';
        }
    }

    /**
     * Retrieve avatar author url of post.
     *
     * @return string
     */
    public function getAvatarAuthorUrl(): string
    {
        $author = $this->getPost()->getMember();
        try {
            return $this->memberHelper->getMemberAvatarUrl($author);
        } catch (NoSuchEntityException) {
            return $this->getViewFileUrl('DaoNguyen_Community::images/avatar-default.png');
        }
    }

    /**
     * @inheritdoc
     *
     * @return array|string[]
     */
    public function getIdentities(): array
    {
        return $this->getPost()->getIdentities();
    }
}
