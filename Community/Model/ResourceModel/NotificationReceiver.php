<?php

namespace DaoNguyen\Community\Model\ResourceModel;

use DaoNguyen\Community\Api\Data\MemberInterface;
use DaoNguyen\Community\Model\ResourceModel\NotificationReceiver\Collection;
use DaoNguyen\Community\Model\ResourceModel\NotificationReceiver\CollectionFactory;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class NotificationReceiver extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'community_notification_receiver_resource_model';

    private CollectionFactory $collectionFactory;

    public function __construct(
        CollectionFactory $collectionFactory,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('community_notification_receiver', 'id');
    }

    public function getTopNotifications(MemberInterface $member): array
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('receiver_id', ['eq' => $member->getEntityId()]);
        $collection->join(
            ['notification' => $this->getTable('community_notification')],
            'notification.notification_id = main_table.notification_id',
            ['message', 'href']
        );
        $collection->setOrder('created_at');
        $topNotifications = [];
        foreach ($collection->getItems() as $item) {
            $topNotifications[] = [
                'id' => $item->getData('id'),
                'status' => $item->getData('status'),
                'message' => $item->getData('message'),
                'href' => $item->getData('href'),
            ];
        }
        return $topNotifications;
    }

    public function readNotification(int $id)
    {
        $conn = $this->getConnection();
        $where = [
            $conn->quoteInto('id=?', $id)
        ];
        $conn->update($this->getMainTable(), ['status' => 1], $where);
    }

    public function readAll(MemberInterface $member)
    {
        $conn = $this->getConnection();
        $where = [
            $conn->quoteInto('receiver_id=?', $member->getEntityId())
        ];
        $conn->update($this->getMainTable(), ['status' => 1], $where);
    }

    public function getMsgUnReadData(MemberInterface $member)
    {
        $conn = $this->getConnection();
        $select = $conn->select()->from($this->getMainTable(), 'count(*)')
            ->where('receiver_id = ?', $member->getEntityId())
            ->where('status = 0');
        return (int) $conn->fetchOne($select);
    }
}
