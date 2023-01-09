<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Model\Group\GroupSearchResults;
use Exception;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use DaoNguyen\Community\Model\ResourceModel\Group as GroupResource;
use DaoNguyen\Community\Model\ResourceModel\Group\CollectionFactory;
use DaoNguyen\Community\Model\Group\GroupSearchResultsFactory;

class GroupRepository
{
    /**
     * @var GroupFactory
     */
    private GroupFactory $groupFactory;

    /**
     * @var GroupResource
     */
    private GroupResource $resource;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $groupCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @var GroupSearchResultsFactory
     */
    private GroupSearchResultsFactory $groupSearchResultsFactory;

    /**
     * @param GroupFactory $groupFactory
     * @param GroupResource $resource
     * @param CollectionFactory $groupCollectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param GroupSearchResultsFactory $groupSearchResultsFactory
     */
    public function __construct(
        GroupFactory $groupFactory,
        GroupResource $resource,
        CollectionFactory $groupCollectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        GroupSearchResultsFactory $groupSearchResultsFactory,
    ) {
        $this->groupFactory = $groupFactory;
        $this->resource = $resource;
        $this->groupCollectionFactory = $groupCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->groupSearchResultsFactory = $groupSearchResultsFactory;
    }

    /**
     * Get group by id.
     *
     * @param int $entityId
     * @return Group
     * @throws NoSuchEntityException
     */
    public function getBydId(int $entityId): Group
    {
        /** @var Group $group */
        $group = $this->groupFactory->create();
        $group->load($entityId);
        if (!$group->getId()) {
            throw new NoSuchEntityException(__('The community group with the "%1" ID doesn\'t exist.', $entityId));
        }
        return $group;
    }

    /**
     * Save a group.
     *
     * @param Group $group
     * @return Group
     * @throws CouldNotSaveException
     */
    public function save(Group $group): Group
    {
        try {
            $avatar = $group->getData('avatar_path');
            if (is_array($avatar)) {
                $group->setData('avatar_path', $avatar[0]['value']);
            }
            $this->resource->save($group);
        } catch (Exception $e) {
            throw new CouldNotSaveException(
                __('Could not save the group: %1', $e->getMessage()),
                $e
            );
        }
        return $group;
    }

    /**
     * Get group list.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return GroupSearchResults
     */
    public function getList(SearchCriteriaInterface $searchCriteria): GroupSearchResults
    {
        $collection = $this->groupCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        /** @var GroupSearchResults $searchResults */
        $searchResults = $this->groupSearchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }
}
