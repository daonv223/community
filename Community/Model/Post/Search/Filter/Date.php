<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Post\Search\Filter;

use DaoNguyen\Community\Model\Post;
use DaoNguyen\Community\Model\Post\Search\AbstractFilter;
use Magento\Framework\App\RequestInterface;

class Date extends AbstractFilter
{
    public const DAY ='day';
    public const WEEK = 'week';
    public const MONTH = 'month';
    public const YEAR = 'year';

    private array $options = [
        self::DAY => 'A day ago',
        self::WEEK => 'A week ago',
        self::MONTH => 'A month ago',
        self::YEAR => 'A year ago'
    ];

    public function getName()
    {
        return __('Date');
    }

    public function getRequestVar()
    {
        return 'range';
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

    public function apply(RequestInterface $request)
    {
        $requestVar = $request->getParam($this->getRequestVar(), false);
        if ($requestVar) {
            switch ($requestVar) {
                case self::DAY:
                    $modifier = '-1 day';
                    break;
                case self::WEEK:
                    $modifier = '-7 day';
                    break;
                case self::MONTH:
                    $modifier = '-1 month';
                    break;
                case self::YEAR:
                    $modifier = '-1 year';
                    break;
                default:
                    $modifier = false;
            }
            if ($modifier) {
                $now = new \DateTime();
                $to = $now->format('Y-m-d H:i:s');
                $from = $now->modify($modifier)->format('Y-m-d H:i:s');
                $collection = $this->layer->getPostCollection();
                $collection->addFieldToFilter(
                    'main_table.updated_at',
                    [
                        'from' => $from,
                        'to' => $to
                    ]
                );
            }
            $this->layer->getState()->addFilter($this->createItem($this->options[$requestVar], $requestVar));
        }
    }
}
