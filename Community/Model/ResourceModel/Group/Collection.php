<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\ResourceModel\Group;

use DaoNguyen\Community\Model\Group as Model;
use DaoNguyen\Community\Model\ResourceModel\Group as ResourceModel;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\Api\Search\AggregationInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

class Collection extends AbstractCollection implements SearchResultInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'community_group_collection';

    /**
     * @var AggregationInterface
     */
    private AggregationInterface $aggregation;

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }

    /**
     * Get total count.
     *
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->getSize();
    }

    /**
     * Get search criteria.
     *
     * @return null
     */
    public function getSearchCriteria()
    {
        return null;
    }

    /**
     * Set Items.
     *
     * @param array|null $items
     * @return $this
     */
    public function setItems(array $items = null): Collection
    {
        return $this;
    }

    /**
     * Get aggregations.
     *
     * @return AggregationInterface
     */
    public function getAggregations(): AggregationInterface
    {
        return $this->aggregation;
    }

    /**
     * Set Aggregations.
     *
     * @param AggregationInterface $aggregations
     * @return Collection
     */
    public function setAggregations($aggregations): Collection
    {
        $this->aggregation = $aggregations;
        return $this;
    }

    /**
     * Set search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return $this
     */
    public function setSearchCriteria(SearchCriteriaInterface $searchCriteria)
    {
        return $this;
    }

    /**
     * Set total count.
     *
     * @param int $totalCount
     * @return $this
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }
}
