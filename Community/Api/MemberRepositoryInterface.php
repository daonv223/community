<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Api;

use DaoNguyen\Community\Api\Data\MemberInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Validation\ValidationException;

interface MemberRepositoryInterface
{
    /**
     * Get member by customer id.
     *
     * @param int $customerId
     * @return MemberInterface
     * @throws NoSuchEntityException
     */
    public function getByCustomerId(int $customerId): MemberInterface;

    /**
     * Get by id.
     *
     * @param int $memberId
     * @return MemberInterface
     * @throw NoSuchEntityException
     */
    public function getById(int $memberId): MemberInterface;

    /**
     * Save member.
     *
     * @param MemberInterface $member
     * @return MemberInterface
     * @throws ValidationException
     * @throws LocalizedException
     */
    public function save(MemberInterface $member): MemberInterface;
}
