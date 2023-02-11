<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Post\Search;

abstract class AbstractFilter implements FilterInterface
{
    protected $items;

    protected Layer $layer;

    /**
     * @var FilterItemFactory
     */
    private FilterItemFactory $filterItemFactory;

    /**
     * @param FilterItemFactory $filterItemFactory
     * @param Layer $layer
     */
    public function __construct(
        FilterItemFactory $filterItemFactory,
        Layer $layer,
    ) {
        $this->filterItemFactory = $filterItemFactory;
        $this->layer = $layer;
    }

    abstract protected function getItemsData();

    public function getItems()
    {
        if ($this->items === null) {
            $this->initItems();
        }
        return $this->items;
    }

    protected function initItems()
    {
        $data = $this->getItemsData();
        $items = [];
        foreach ($data as $itemData) {
            $items[] = $this->createItem($itemData['label'], $itemData['value']);
        }
        $this->items = $items;
        return $this;
    }

    protected function createItem($label, $value)
    {
        return $this->filterItemFactory->create()
            ->setFilter($this)
            ->setLabel($label)
            ->setValue($value);
    }

    public function getItemsCount()
    {
        return count($this->getItems());
    }
}
