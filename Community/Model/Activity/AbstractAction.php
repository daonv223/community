<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Activity;

use Magento\Framework\App\Config\ScopeConfigInterface;

class AbstractAction
{
    protected string $pointConfigPath;

    protected ScopeConfigInterface $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function getPoint(): int
    {
        return (int) $this->scopeConfig->getValue($this->pointConfigPath);
    }
}
