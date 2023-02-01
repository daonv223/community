<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Post;

class PostStats
{
    private int $replies;
    private int $views;
    private int $heartsGiver;
    private int $contributors;

    /**
     * @return int
     */
    public function getReplies(): int
    {
        return $this->replies;
    }

    /**
     * @param int $replies
     */
    public function setReplies(int $replies): void
    {
        $this->replies = $replies;
    }

    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * @param int $views
     */
    public function setViews(int $views): void
    {
        $this->views = $views;
    }

    /**
     * @return int
     */
    public function getHeartsGiver(): int
    {
        return $this->heartsGiver;
    }

    /**
     * @param int $heartsGiver
     */
    public function setHeartsGiver(int $heartsGiver): void
    {
        $this->heartsGiver = $heartsGiver;
    }

    /**
     * @return int
     */
    public function getContributors(): int
    {
        return $this->contributors;
    }

    /**
     * @param int $contributors
     */
    public function setContributors(int $contributors): void
    {
        $this->contributors = $contributors;
    }

}
