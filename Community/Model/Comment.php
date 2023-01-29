<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Api\MemberRepositoryInterface;
use DaoNguyen\Community\Model\ResourceModel\Comment as ResourceModel;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class Comment extends AbstractModel
{
    public const MEMBER_ID = 'member_id';
    public const CONTENT = 'content';
    public const POST_ID = 'post_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'community_comment_model';

    /**
     * @var MemberRepositoryInterface
     */
    private MemberRepositoryInterface $memberRepository;

    /**
     * @param MemberRepositoryInterface $memberRepository
     * @param Context $context
     * @param Registry $registry
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        MemberRepositoryInterface $memberRepository,
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->memberRepository = $memberRepository;
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
}
