<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Group\View;

use DaoNguyen\Community\Model\Group;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class GroupView extends Template implements IdentityInterface
{
    private Registry $registry;

    public function __construct(
        Registry $registry,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->registry->registry('group');
    }

    public function getIdentities()
    {
        $group = $this->getGroup();
        return [Group::CACHE_TAG . '_' . $group->getId()];
    }
}
