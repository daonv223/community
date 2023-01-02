<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Api\Data\MemberInterface;
use DaoNguyen\Community\Model\ResourceModel\Member as ResourceModel;
use Magento\Framework\Model\AbstractModel;

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
}
