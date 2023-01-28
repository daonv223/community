<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Model\ResourceModel\Post as ResourcePost;
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
     * @param ResourcePost $resource
     * @param PostFactory $postFactory
     */
    public function __construct(
        ResourcePost $resource,
        PostFactory $postFactory
    ) {
        $this->resource = $resource;
        $this->postFactory = $postFactory;
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
}
