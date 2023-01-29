<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Model\ResourceModel\Comment as CommentResource;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;

class CommentRepository
{
    /**
     * @var CommentResource
     */
    private CommentResource $resource;

    /**
     * @var CommentFactory
     */
    private CommentFactory $commentFactory;

    /**
     * @param CommentResource $resource
     */
    public function __construct(
        CommentResource $resource,
        CommentFactory $commentFactory
    ) {
        $this->resource = $resource;
        $this->commentFactory = $commentFactory;
    }

    /**
     * Save the model.
     *
     * @param Comment $comment
     * @return Comment
     * @throws AlreadyExistsException
     */
    public function save(Comment $comment): Comment
    {
        $this->resource->save($comment);
        return $comment;
    }

    /**
     * Get comment by id.
     *
     * @param int $commentId
     * @return Comment
     * @throws NoSuchEntityException
     */
    public function getById(int $commentId): Comment
    {
        $comment = $this->commentFactory->create();
        $comment->load($commentId);
        if (!$comment->getEntityId()) {
            throw new NoSuchEntityException(__('The community comment with the "%1" ID doesn\'t exist.', $comment));
        }
        return $comment;
    }
}
