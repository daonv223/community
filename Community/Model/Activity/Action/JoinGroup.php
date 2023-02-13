<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Activity\Action;

use DaoNguyen\Community\Model\Activity\AbstractAction;

class JoinGroup extends AbstractAction
{
    protected string $pointConfigPath = 'community_rank_system/actions/join_group';
}
