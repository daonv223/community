<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Post\Create;

use DaoNguyen\Community\Model\Group;
use DaoNguyen\Community\ViewModel\AllGroups;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template;

class Form extends Template implements IdentityInterface
{

    /**
     * Get option for select groups.
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        /** @var AllGroups $allGroupsViewModel */
        $allGroupsViewModel = $this->getAllGroups();
        $options = [[
            'value' => '',
            'label' => __('Choose a group')
        ]];
        foreach ($allGroupsViewModel->getAllActiveGroups() as $group) {
            $options[] = [
                'value' => $group->getId(),
                'label' => $group->getName()
            ];
        }
        return $options;
    }

    /**
     * Get cache identities of block.
     *
     * @return string[]
     */
    public function getIdentities(): array
    {
        /** @var AllGroups $allGroupsViewModel */
        $allGroupsViewModel = $this->getAllGroups();
        $identities = [Group::CACHE_TAG];
        foreach ($allGroupsViewModel->getAllActiveGroups() as $group) {
            $identities[] = Group::CACHE_TAG . '_' . $group->getId();
        }
        $this->getUrl();
        return $identities;
    }
}
