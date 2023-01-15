<?php
declare(strict_types=1);

namespace DaoNguyen\Community\ViewModel;

use DaoNguyen\Community\Model\Group;
use DaoNguyen\Community\Model\GroupRepository;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class AllGroups implements ArgumentInterface
{
    /**
     * @var array
     */
    private array $groups;

    /**
     * @var GroupRepository
     */
    private GroupRepository $groupRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    private FilterBuilder $filterBuilder;

    /**
     * @param GroupRepository $groupRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        GroupRepository $groupRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder
    ) {
        $this->groupRepository = $groupRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * Get all active groups.
     *
     * @return Group[]
     */
    public function getAllActiveGroups(): array
    {
        if (!isset($this->groups)) {
            $activeFilter = $this->filterBuilder
                ->setField('is_active')
                ->setValue(1)
                ->setConditionType('eq')->create();
            $searchCriteria = $this->searchCriteriaBuilder->addFilter($activeFilter)->create();
            $searchResults = $this->groupRepository->getList($searchCriteria);
            $this->groups = $searchResults->getItems();
        }
        return $this->groups;
    }
}
