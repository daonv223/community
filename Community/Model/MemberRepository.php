<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Api\Data\MemberInterface;
use DaoNguyen\Community\Api\Data\MemberInterfaceFactory;
use DaoNguyen\Community\Api\MemberRepositoryInterface;
use DaoNguyen\Community\Model\ResourceModel\Member as MemberResource;
use Magento\Framework\Validation\ValidationException;
use Ramsey\Uuid\Uuid;

class MemberRepository implements MemberRepositoryInterface
{
    /**
     * @var MemberInterfaceFactory
     */
    private MemberInterfaceFactory $memberInterfaceFactory;

    /**
     * @var MemberResource
     */
    private MemberResource $memberResource;

    /**
     * @param MemberInterfaceFactory $memberInterfaceFactory
     * @param MemberResource $memberResource
     */
    public function __construct(MemberInterfaceFactory $memberInterfaceFactory, MemberResource $memberResource)
    {
        $this->memberInterfaceFactory = $memberInterfaceFactory;
        $this->memberResource = $memberResource;
    }

    /**
     * @inheritdoc
     */
    public function getByCustomerId(int $customerId): MemberInterface
    {
        /** @var Member $member */
        $member = $this->memberInterfaceFactory->create();
        $this->memberResource->load($member, $customerId, MemberInterface::CUSTOMER_ID);
        return $member;
    }

    /**
     * @inheritdoc
     */
    public function save(MemberInterface $member): MemberInterface
    {
        /** @var Member $oldMember */
        $oldMember = $this->memberInterfaceFactory->create();
        $this->memberResource->load($oldMember, $member->getNickname(), MemberInterface::NICKNAME);
        if ($oldMember->getId() && $oldMember->getCustomerId() !== $member->getCustomerId()) {
            throw new ValidationException(__('The Nickname Already Exists!'));
        }
        $member->setUpdatedAt((string) time());
        if (!$member->getId()) {
            $member->setUuid(Uuid::uuid4()->toString());
        }
        $this->memberResource->save($member);
        return $member;
    }

    /**
     * Get member by id.
     *
     * @param int $memberId
     * @return MemberInterface
     */
    public function getById(int $memberId): MemberInterface
    {
        $member = $this->memberInterfaceFactory->create();
        $this->memberResource->load($member, $memberId);
        return $member;
    }
}
