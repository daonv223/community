<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Group;

use DaoNguyen\Community\Model\Group;
use Magento\Framework\View\Element\Template;

class Item extends Template
{
    /**
     * @var Group
     */
    private Group $group;

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
}
