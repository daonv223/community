<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Model\ResourceModel\Post as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class Post extends AbstractModel
{
    public const MEMBER_ID = 'member_id';
    public const STATUS = 'status';
    public const CONTENT = 'content';
    public const APPROVED = 1;
    public const NOT_APPROVED = 0;

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
     * @return int
     */
    public function getEntityId(): int
    {
        return (int) $this->getData('entity_id');
    }
}
