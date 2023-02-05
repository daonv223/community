<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Model\ResourceModel\Post as ResourcePost;
use DaoNguyen\Community\Model\ResourceModel\Post\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;

class PostRepository
{
    /**
     * @var ResourcePost
     */
    private ResourcePost $resource;

    /**
     * @var PostFactory
     */
    private PostFactory $postFactory;

    /**
     * @var ResourcePost\CollectionFactory
     */
    private ResourcePost\CollectionFactory $collectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @param ResourcePost $resource
     * @param PostFactory $postFactory
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourcePost $resource,
        PostFactory $postFactory,
        ResourcePost\CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->postFactory = $postFactory;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * Save post.
     *
     * @param Post $post
     * @return Post
     * @throws AlreadyExistsException
     */
    public function save(Post $post): Post
    {
        $this->resource->save($post);
        return $post;
    }

    /**
     * Get post by id.
     *
     * @param int $postId
     * @return Post
     * @throws NoSuchEntityException
     */
    public function getById(int $postId): Post
    {
        $post = $this->postFactory->create();
        $post->load($postId);
        if (!$post->getEntityId()) {
            throw new NoSuchEntityException(__('The community post with the "%1" ID doesn\'t exist.', $postId));
        }
        return $post;
    }

    /**
     * Lists posts that match specified search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return ResourcePost\Collection
     */
    public function getList(SearchCriteriaInterface $searchCriteria): ResourcePost\Collection
    {
        $searchResult = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $searchResult);
        return $searchResult;
    }
}
