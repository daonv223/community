<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

class Session extends \Magento\Customer\Model\Session
{
    /**
     * Set member id.
     *
     * @param int $memberId
     * @return $this
     */
    public function setMemberId(int $memberId): Session
    {
        $this->storage->setData('member_id', $memberId);
        return $this;
    }

    /**
     * Get member id.
     *
     * @return int|null
     */
    public function getMemberId(): ?int
    {
        if ($this->storage->getData('member_id')) {
            return (int) $this->storage->getData('member_id');
        }
        return null;
    }
}
