<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Post\Comment;

use DaoNguyen\Community\Block\Post\View\PostView;
use DaoNguyen\Community\Model\Comment;

class Form extends PostView
{
    /**
     * Retrieve current parent comment.
     *
     * @return Comment|null
     */
    public function getParentComment(): ?Comment
    {
        return $this->registry->registry('comment');
    }
}
