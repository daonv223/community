<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use DaoNguyen\Community\Model\ResourceModel\Group as GroupResource;

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
     * @param GroupFactory $groupFactory
     * @param GroupResource $resource
     */
    public function __construct(GroupFactory $groupFactory, GroupResource $resource)
    {
        $this->groupFactory = $groupFactory;
        $this->resource = $resource;
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
            $this->resource->save($group);
        } catch (Exception $e) {
            throw new CouldNotSaveException(
                __('Could not save the group: %1', $e->getMessage()),
                $e
            );
        }
        return $group;
    }
}
