<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Api\MemberRepositoryInterface;
use DaoNguyen\Community\Model\ResourceModel\Post as ResourceModel;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class Post extends AbstractModel
{
    public const MEMBER_ID = 'member_id';
    public const STATUS = 'status';
    public const CONTENT = 'content';
    public const APPROVED = 1;
    public const NOT_APPROVED = 0;

    /**
     * @var GroupRepository
     */
    private GroupRepository $groupRepository;

    /**
     * @var MemberRepositoryInterface
     */
    private MemberRepositoryInterface $memberRepository;

    /**
     * @param GroupRepository $groupRepository
     * @param MemberRepositoryInterface $memberRepository
     * @param Context $context
     * @param Registry $registry
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        GroupRepository $groupRepository,
        MemberRepositoryInterface $memberRepository,
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->groupRepository = $groupRepository;
        $this->memberRepository = $memberRepository;
    }

    /**
     * @var string
     */
    protected $_eventPrefix = 'community_post_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * Set member id.
     *
     * @param int $memberId
     * @return $this
     */
    public function setMemberId(int $memberId): Post
    {
        $this->setData(self::MEMBER_ID, $memberId);
        return $this;
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
     * Set status.
     *
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): Post
    {
        $this->setData(self::STATUS, $status);
        return $this;
    }

    /**
     * Get content.
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->getData('content');
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
     * Get group id of this model.
     *
     * @return int
     */
    public function getGroupId(): int
    {
        return (int) $this->getData('group_id');
    }

    /**
     * Get group model.
     *
     * @return Group
     * @throws NoSuchEntityException
     */
    public function getGroup(): Group
    {
        $group = $this->getData('group');
        if (!$group) {
            $groupId = $this->getGroupId();
            $group = $this->groupRepository->getBydId($groupId);
            $this->setData('group', $group);
        }
        return $this->getData('group');
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
     * Retrieve subject of this model.
     *
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->getData('subject');
    }

    /**
     * Retrieve author of post.
     *
     * @return Member
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
}
