<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Post\Search\Filter;

use DaoNguyen\Community\Model\Post\Search\AbstractFilter;
use Magento\Framework\App\RequestInterface;

class Product extends AbstractFilter
{
    public const YES = 'yes';
    public const NO = 'no';

    private array $options = [
        self::YES => 'Yes',
        self::NO => 'No'
    ];

    public function getName()
    {
        return __('Product');
    }

    protected function getItemsData()
    {
        $data = [];
        foreach ($this->options as $value => $label) {
            $data[] = [
                'label' => __($label),
                'value' => $value
            ];
        }
        return $data;
    }

    public function getRequestVar()
    {
        return 'link_with_product';
    }

    public function apply(RequestInterface $request)
    {
        $requestVar = $request->getParam($this->getRequestVar(), false);
        if ($requestVar) {
            $collection = $this->layer->getPostCollection();
            $join = $collection->getResource()->getConnection()->select()
                ->from('community_associated_product', ['post_id', 'count(*) as count_products'])
                ->group('post_id');
            $collection->getSelect()->joinLeft(
                ['cas' => $join],
                'main_table.entity_id = cas.post_id',
                ['cas.count_products']
            );
            if ($requestVar === self::YES) {
                $collection->addFieldToFilter('cas.count_products', ['gteq' => 1]);
            } elseif ($requestVar === self::NO) {
                $collection->addFieldToFilter('cas.count_products', ['null' => true]);
            }
            $this->layer->getState()->addFilter($this->createItem($this->options[$requestVar], $requestVar));
        }
    }
}
