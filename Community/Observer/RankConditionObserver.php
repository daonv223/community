<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class RankConditionObserver implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        $additional = $observer->getAdditional();
        $conditions = (array) $additional->getConditions();
        $conditions = array_merge_recursive($conditions, [
            [
                'value' => [
                    [
                        'value' => \DaoNguyen\Community\Model\Rule\Condition\Rank::class,
                        'label' => __('Rank')
                    ]
                ],
                'label' => __('Community Condition')
            ]
        ]);
        $additional->setConditions($conditions);
    }
}
