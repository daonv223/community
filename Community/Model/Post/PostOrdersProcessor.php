<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Post;

use DaoNguyen\Community\Model\ResourceModel\Post\Collection;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\ObjectManagerInterface;

class PostOrdersProcessor implements PostOrderInterface
{
    /**
     * @var array
     */
    private array $orderClasses;

    /**
     * @var ObjectManagerInterface
     */
    private ObjectManagerInterface $objectManager;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @param RequestInterface $request
     * @param ObjectManagerInterface $objectManager
     * @param array $orderClasses
     */
    public function __construct(
        RequestInterface $request,
        ObjectManagerInterface $objectManager,
        array $orderClasses = []
    ) {
        $this->request = $request;
        $this->objectManager = $objectManager;
        $this->orderClasses = $orderClasses;
    }

    /**
     * @inheritdoc
     */
    public function setOrder(Collection $collection): void
    {
        $orderRequest = $this->request->getParam('post_list_order', 'most_recent');
        /** @var PostOrderInterface $order */
        $order = $this->objectManager->get($this->orderClasses[$orderRequest]);
        $order->setOrder($collection);
    }
}
