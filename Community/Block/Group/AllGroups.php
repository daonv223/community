<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Group;

use DaoNguyen\Community\Model\Group;
use DaoNguyen\Community\Model\GroupRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template;

class AllGroups extends Template implements IdentityInterface
{
    /**
     * @var GroupRepository
     */
    private GroupRepository $groupRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var array
     */
    private array $allGroups = [];

    /**
     * @param GroupRepository $groupRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        GroupRepository $groupRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->groupRepository = $groupRepository;
        $this->searchCriteriaBuilder =  $searchCriteriaBuilder;
    }

    /**
     * Get all groups.
     *
     * @return array
     */
    public function getAllGroups(): array
    {
        if (!$this->allGroups) {
            $searchCriteria = $this->searchCriteriaBuilder->addFilter(Group::IS_ACTIVE, 1)->create();
            $this->allGroups = $this->groupRepository->getList($searchCriteria)->getItems();
        }
        return $this->allGroups;
    }

    /**
     * Get group card html.
     *
     * @param Group $group
     * @return string
     */
    public function getGroupCardHtml(Group $group): string
    {
        /** @var Item $block */
        $block = $this->getChildBlock('item.renderer');
        $block->setGroup($group);
        return $block->toHtml();
    }

    /**
     * @inheritdoc
     */
    public function getIdentities(): array
    {
        $allGroups = $this->getAllGroups();
        $identities = [Group::CACHE_TAG];
        foreach ($allGroups as $group) {
            /** @var Group $group */
            $identities[] = Group::CACHE_TAG . '_' . $group->getId();
        }
        return $identities;
    }
}
