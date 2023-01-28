<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Api\Data\MemberInterface;
use DaoNguyen\Community\Model\ResourceModel\Member as ResourceModel;
use Magento\Framework\Model\AbstractModel;
use Zend_Db_Statement_Exception;

class Member extends AbstractModel implements MemberInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'community_member_model';

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
}
