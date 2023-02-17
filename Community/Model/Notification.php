<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Model\ResourceModel\Notification as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class Notification extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'community_notification_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    public function setReceiverIds(array $receiverIds)
    {
        $this->setData('receiver_ids', $receiverIds);
    }

    /**
     * @return array|null
     */
    public function getReceiverIds()
    {
        return $this->getData('receiver_ids');
    }

    public function afterSave()
    {
        $receiverIds = $this->getReceiverIds();
        if ($receiverIds !== null) {
            $data = [];
            foreach ($receiverIds as $receiverId) {
                $data[] = [
                    'receiver_id' => $receiverId,
                    'notification_id' => $this->getId()
                ];
            }
            if ($data) {
                $conn = $this->getResource()->getConnection();
                $conn->insertMultiple('community_notification_receiver', $data);
            }
        }
        return parent::afterSave();
    }
}
