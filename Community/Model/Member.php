<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Api\Data\MemberInterface;
use DaoNguyen\Community\Model\ResourceModel\Member as ResourceModel;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Zend_Db_Statement_Exception;

class Member extends AbstractModel implements MemberInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'community_member_model';

    /**
     * @var CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * @param CustomerRepositoryInterface $customerRepository
     * @param Context $context
     * @param Registry $registry
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->customerRepository = $customerRepository;
    }

    /**
     * Initialize member model.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @inheritdoc
     */
    public function isRegistered(): bool
    {
        return (bool) $this->getId();
    }

    /**
     * @inheritdoc
     */
    public function setCustomerId(int $customerId): MemberInterface
    {
        $this->setData(MemberInterface::CUSTOMER_ID, $customerId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setNickname(string $nickname): MemberInterface
    {
        $this->setData(MemberInterface::NICKNAME, $nickname);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setBio(string $bio): MemberInterface
    {
        $this->setData(MemberInterface::BIO, $bio);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getNickname(): ?string
    {
        return $this->getData(MemberInterface::NICKNAME);
    }

    /**
     * @inheritdoc
     */
    public function getCustomerId(): int|null
    {
        $customerId = $this->getData(MemberInterface::CUSTOMER_ID);
        return $customerId === null ? null : (int) $customerId;
    }

    /**
     * @inheritdoc
     */
    public function getBio(): ?string
    {
        return $this->getData(MemberInterface::BIO);
    }

    /**
     * @inheritdoc
     */
    public function setStatus(int $status): MemberInterface
    {
        return $this->setData(MemberInterface::STATUS, $status);
    }

    /**
     * @inheritdoc
     */
    public function setUpdatedAt(string $updatedAt): MemberInterface
    {
        $this->setData(MemberInterface::UPDATED_AT, $updatedAt);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setAvatarPath(string $avatarPath): MemberInterface
    {
        $this->setData(MemberInterface::AVATAR_PATH, $avatarPath);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAvatarPath(): ?string
    {
        return $this->getData(MemberInterface::AVATAR_PATH);
    }

    /**
     * Join groups.
     *
     * @param array $groupIds
     * @return int
     */
    public function joinGroups(array $groupIds): int
    {
        if (!$this->getId()) {
            return 0;
        }
        $connection = $this->getResource()->getConnection();
        $linkTable = $connection->getTableName('community_group_member');
        $data = [];
        foreach ($groupIds as $groupId) {
            $data[] = [
                'group_id' => $groupId,
                'member_id' => $this->getId()
            ];
        }
        return $connection->insertMultiple($linkTable, $data);
    }

    /**
     * Get joined groups.
     *
     * @return array
     * @throws Zend_Db_Statement_Exception
     */
    public function getJoinedGroups(): array
    {
        $connection = $this->getResource()->getConnection();
        $select = $connection->select()
            ->from('community_group_member', 'group_id')
            ->where('member_id = ?', $this->getId());
        return $connection->fetchCol($select);
    }

    public function getLikedPosts(): array
    {
        $connection = $this->getResource()->getConnection();
        $select = $connection->select()
            ->from('community_post_reaction', 'post_id')
            ->where('member_id = ?', $this->getId());
        return $connection->fetchCol($select);
    }

    /**
     * Set uuid.
     *
     * @param string $uuid
     * @return MemberInterface
     */
    public function setUuid(string $uuid): MemberInterface
    {
        $this->setData(MemberInterface::UUID, $uuid);
        return $this;
    }

    /**
     * Get uuid.
     *
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->getData(MemberInterface::UUID);
    }

    /**
     * Is like post.
     *
     * @param int $postId
     * @return bool
     */
    public function isLikedPost(int $postId): bool
    {
        $conn = $this->getResource()->getConnection();
        $select = $conn->select()
            ->from($conn->getTableName('community_post_reaction'))
            ->where('member_id = ?', $this->getId())
            ->where('post_id = ?', $postId);
        return (bool) $conn->fetchRow($select);
    }

    /**
     * Reaction post id.
     *
     * @param int $postId
     * @return void
     */
    public function reaction(int $postId): void
    {
        $isLikedPost = $this->isLikedPost($postId);
        $conn = $this->getResource()->getConnection();
        $tableName = $conn->getTableName('community_post_reaction');
        if (!$isLikedPost) {
            $conn->insertMultiple(
                $conn->getTableName($tableName),
                [
                    'member_id' => $this->getId(),
                    'post_id' => $postId
                ]
            );
        } else {
            $conn->delete(
                $tableName,
                [
                    'member_id' => $this->getId(),
                    'post_id' => $postId
                ]
            );
        }
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

    public function getRankPoints(): int
    {
        if ($this->getData('rank_points') === null) {
            $conn = $this->getResource()->getConnection();
            $select = $conn->select()
                ->from('community_activity', 'SUM(points)')
                ->where('actor_id = ?', $this->getEntityId());
            $points = (int) $conn->fetchOne($select);
            $this->setData('rank_points', $points);
        }
        return $this->getData('rank_points');
    }

    /**
     * Get customer.
     *
     * @return CustomerInterface
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCustomer(): CustomerInterface
    {
        return $this->customerRepository->getById($this->getCustomerId());
    }
}
