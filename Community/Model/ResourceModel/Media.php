<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Media extends AbstractDb
{
    /**
     * @var string
     */
    protected string $_eventPrefix = 'community_media_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('community_media', 'value_id');
    }

    /**
     * Load by member id and media name.
     *
     * @param \DaoNguyen\Community\Model\Media $media
     * @param int $memberId
     * @param string $path
     * @return void
     * @throws LocalizedException
     */
    public function loadByMemberIdAndPath(\DaoNguyen\Community\Model\Media $media, int $memberId, string $path): void
    {
        $conn = $this->getConnection();
        $select = $conn->select()->from(
            $this->getMainTable()
        )->where(
            'member_id = ?',
            $memberId
        )->where(
            'path = ?',
            $path
        );
        $data = $conn->fetchRow($select);
        if ($data) {
            $media->setData($data);
        }
    }
}
