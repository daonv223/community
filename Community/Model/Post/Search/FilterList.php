<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Post\Search;

use Magento\Framework\ObjectManagerInterface;

class FilterList
{
    private ObjectManagerInterface $objectManager;

    private array $filters;

    private array $filterClasses;

    public function __construct(
        ObjectManagerInterface $objectManager,
        array $filters = [],
        array $filterClasses = []
    ) {
        $this->objectManager = $objectManager;
        $this->filters = $filters;
        $this->filterClasses = $filterClasses;
    }

    /**
     * @return FilterInterface[]
     */
    public function getFilters(): array
    {
        if (!count($this->filters)) {
            foreach ($this->filterClasses as $filterClass) {
                $this->filters[] = $this->objectManager->create($filterClass);
            }
        }
        return $this->filters;
    }
}
