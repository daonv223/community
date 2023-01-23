<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Model\ResourceModel\Post as ResourcePost;
use Magento\Framework\Exception\AlreadyExistsException;

class PostRepository
{
    /**
     * @var ResourcePost
     */
    private ResourcePost $resource;

    /**
     * @param ResourcePost $resource
     */
    public function __construct(ResourcePost $resource)
    {
        $this->resource = $resource;
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
}
