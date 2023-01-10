<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Api\Data;

interface MemberInterface
{
    public const CUSTOMER_ID = 'customer_id';
    public const NICKNAME = 'nickname';
    public const BIO = 'bio';
    public const STATUS = 'status';
    public const ACTIVE_STATUS = 1;
    public const UPDATED_AT = 'updated_at';
    public const AVATAR_PATH = 'avatar_path';

    /**
     * Is member registered.
     *
     * @return bool
     */
    public function isRegistered(): bool;

    /**
     * Set customer id.
     *
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId(int $customerId): MemberInterface;

    /**
     * Get customer id.
     *
     * @return int|null
     */
    public function getCustomerId(): int|null;

    /**
     * Set nickname.
     *
     * @param string $nickname
     * @return MemberInterface
     */
    public function setNickname(string $nickname): MemberInterface;

    /**
     * Get nickname.
     *
     * @return string|null
     */
    public function getNickname(): ?string;

    /**
     * Set bio.
     *
     * @param string $bio
     * @return MemberInterface
     */
    public function setBio(string $bio): MemberInterface;

    /**
     * Set bio.
     *
     * @return string|null
     */
    public function getBio(): ?string;

    /**
     * Set status.
     *
     * @param int $status
     * @return MemberInterface
     */
    public function setStatus(int $status): MemberInterface;

    /**
     * Set updated_at.
     *
     * @param string $updatedAt
     * @return MemberInterface
     */
    public function setUpdatedAt(string $updatedAt): MemberInterface;

    /**
     * Set avatar path.
     *
     * @param string $avatarPath
     * @return MemberInterface
     */
    public function setAvatarPath(string $avatarPath): MemberInterface;

    /**
     * Get avatar path.
     *
     * @return string|null
     */
    public function getAvatarPath(): ?string;

    /**
     * Join groups.
     *
     * @param array $groupIds
     * @return int
     */
    public function joinGroups(array $groupIds): int;
}
