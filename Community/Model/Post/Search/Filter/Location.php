<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Post\Search\Filter;

use DaoNguyen\Community\Model\Group;
use DaoNguyen\Community\Model\GroupRepository;
use DaoNguyen\Community\Model\Post;
use DaoNguyen\Community\Model\Post\Search\AbstractFilter;
use DaoNguyen\Community\Model\Post\Search\FilterItemFactory;
use DaoNguyen\Community\Model\Post\Search\Layer;
use DaoNguyen\Community\Model\ResourceModel\Group\CollectionFactory;
use Magento\Framework\App\RequestInterface;

class Location extends AbstractFilter
{
    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    private GroupRepository $groupRepository;

    public function __construct(
        FilterItemFactory $filterItemFactory,
        Layer $layer,
        CollectionFactory $collectionFactory,
        GroupRepository $groupRepository
    ) {
        parent::__construct($filterItemFactory, $layer);
        $this->collectionFactory = $collectionFactory;
        $this->groupRepository = $groupRepository;
    }

    public function getName()
    {
        return __('Location');
    }

    protected function getItemsData()
    {
        $data = [];
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('is_active', ['eq' => 1]);
        foreach ($collection->getItems() as /** @var Group $group */ $group) {
            $data[] = [
                'label' => $group->getName(),
                'value' => $group->getEntityId(),
            ];
        }
        return $data;
    }

    public function getRequestVar()
    {
        return 'group_id';
    }

    public function apply(RequestInterface $request)
    {
        $groupId = (int) $request->getParam($this->getRequestVar(), false);
        if ($groupId) {
            $group = $this->groupRepository->getBydId($groupId);
            $collection = $this->layer->getPostCollection();
            $collection->addFieldToFilter(Post::GROUP_ID, ['eq' => $groupId]);
            $this->layer->getState()->addFilter($this->createItem($group->getName(), $groupId));
        }
    }
}
