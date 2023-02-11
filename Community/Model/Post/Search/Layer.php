<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Post\Search;

use DaoNguyen\Community\Model\Post;
use DaoNguyen\Community\Model\Post\Search\Layer\State;
use DaoNguyen\Community\Model\Post\Search\Layer\StateFactory;
use DaoNguyen\Community\Model\ResourceModel\Post\Collection;
use DaoNguyen\Community\Model\ResourceModel\Post\CollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObject;

class Layer extends DataObject
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * @var StateFactory
     */
    private StateFactory $stateFactory;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    public function __construct(
        StateFactory $stateFactory,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        array $data = []
    ) {
        parent::__construct($data);
        $this->stateFactory = $stateFactory;
        $this->collectionFactory = $collectionFactory;
        $this->request = $request;
    }

    /**
     * @return State
     */
    public function getState()
    {
        $state = $this->getData('state');
        if ($state === null) {
            $state = $this->stateFactory->create();
            $this->setData('state', $state);
        }
        return $state;
    }

    /**
     * @return Collection
     */
    public function getPostCollection()
    {
        if ($this->collection === null) {
            $this->collection = $this->collectionFactory->create();
            $this->collection->addFieldToFilter(Post::STATUS, ['eq' => 1]);
            $query = $this->request->getParam('post_query', false);
            if ($query) {
                $columns = ['main_table.subject', 'main_table.content'];
                $this->collection->getSelect()->where(
                    'MATCH(' . implode(',', $columns) . ') AGAINST(?)',
                    $query
                );
            }
        }
        return $this->collection;
    }
}
