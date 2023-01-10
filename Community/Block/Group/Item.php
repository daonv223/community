<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Group;

use DaoNguyen\Community\Model\Group;
use DaoNguyen\Community\Model\Session;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Item extends Template
{
    /**
     * @var Group
     */
    private Group $group;

    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @var Session
     */
    private Session $memberSession;

    /**
     * @param Session $session
     * @param ResourceConnection $resourceConnection
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Session $session,
        ResourceConnection $resourceConnection,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->resourceConnection = $resourceConnection;
        $this->memberSession = $session;
    }

    /**
     * Get group.
     *
     * @return Group
     */
    public function getGroup(): Group
    {
        return $this->group;
    }

    /**
     * Set group.
     *
     * @param Group $group
     * @return $this
     */
    public function setGroup(Group $group): Item
    {
        $this->group = $group;
        return  $this;
    }

    /**
     * Truncate description.
     *
     * @param string $description
     * @return string
     */
    public function truncateDescription(string $description): string
    {
        return $this->filterManager->truncate($description, ['length' => 120]);
    }

    /**
     * Is member join.
     *
     * @param int $groupId
     * @return bool
     */
    public function isMemberJoin(int $groupId): bool
    {
        $connection = $this->resourceConnection->getConnection();
        try {
            $select = $connection->select()
                ->from('community_group_member')
                ->where('group_id = ?', $groupId)
                ->where('member_id = ?', $this->memberSession->getCurrentMember()->getId());
        } catch (LocalizedException) {
            return false;
        }
        return (bool) $connection->fetchRow($select);
    }
}
