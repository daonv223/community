<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Api\MemberRepositoryInterface;
use DaoNguyen\Community\Model\ResourceModel\Comment as ResourceModel;
use DaoNguyen\Community\Model\ResourceModel\Comment\CollectionFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class Comment extends AbstractModel implements IdentityInterface
{
    public const ENTITY_ID = 'entity_id';
    public const MEMBER_ID = 'member_id';
    public const CONTENT = 'content';
    public const POST_ID = 'post_id';
    public const PARENT_ID = 'parent_id';
    public const UPDATED_AT = 'updated_at';
    public const CACHE_TAG = 'com_c';

    /**
     * @var string
     */
    protected $_eventPrefix = 'community_comment_model';

    /**
     * @var MemberRepositoryInterface
     */
    private MemberRepositoryInterface $memberRepository;

    /**
     * @var ResourceModel\CollectionFactory
     */
    private ResourceModel\CollectionFactory $collectionFactory;

    /**
     * @param MemberRepositoryInterface $memberRepository
     * @param CollectionFactory $collectionFactory
     * @param Context $context
     * @param Registry $registry
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        MemberRepositoryInterface $memberRepository,
        ResourceModel\CollectionFactory $collectionFactory,
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->memberRepository = $memberRepository;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Initialize magento model.
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * Set member id.
     *
     * @param int $memberID
     * @return $this
     */
    public function setMemberId(int $memberID): Comment
    {
        $this->setData(self::MEMBER_ID, $memberID);
        return $this;
    }

    /**
     * Get entity id of this model.
     *
     * @return int
     */
    public function getEntityId(): int
    {
        return (int) $this->getData('entity_id');
    }

    /**
     * Retrieve this member id.
     *
     * @return int
     */
    public function getMemberId(): int
    {
        return (int) $this->getData(self::MEMBER_ID);
    }

    /**
     * Retrieve author of post.
     *
     * @return Member
     * @throws NoSuchEntityException
     */
    public function getMember(): Member
    {
        $member = $this->getData('member');
        if (!$member) {
            $memberId = $this->getMemberId();
            $member = $this->memberRepository->getById($memberId);
            $this->setData('member', $member);
        }
        return $this->getData('member');
    }

    /**
     * Get comment content.
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * Retrieve created_at of this model.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData('created_at');
    }

    /**
     * Retrieve updated_at of this model.
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData('updated_at');
    }

    /**
     * Retrieve post id.
     *
     * @return int
     */
    public function getPostId(): int
    {
        return (int) $this->getData(self::POST_ID);
    }

    /**
     * Load all children.
     *
     * @return array
     */
    public function getChildren(): array
    {
        if ($this->getData('children') === null) {
            $children = [];
            $childrenCollection = $this->collectionFactory->create();
            $childrenCollection->addFieldToFilter(self::PARENT_ID, ['eq' => $this->getEntityId()]);
            foreach ($childrenCollection as $child) {
                $children[] = $child;
            }
            $this->setData('children', $children);
        }
        return $this->getData('children');
    }

    public function getLevel(): int
    {
        return $this->getData('level');
    }

    public function setLevel(int $level): Comment
    {
        $this->setData('level', $level);
        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return string[]
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getEntityId(), self::CACHE_TAG, Post::CACHE_TAG . '_' . $this->getPostId()];
    }
}
