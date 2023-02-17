<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Activity;

use DaoNguyen\Community\Model\NotificationFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\UrlInterface;

class AbstractAction
{
    protected string $pointConfigPath;

    private $dataObject;

    protected ScopeConfigInterface $scopeConfig;

    protected NotificationFactory $notificationFactory;

    protected UrlInterface $urlBuilder;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        NotificationFactory $notificationFactory,
        UrlInterface $urlBuilder
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->notificationFactory = $notificationFactory;
        $this->urlBuilder = $urlBuilder;
    }

    public function getPoint(): int
    {
        return (int) $this->scopeConfig->getValue($this->pointConfigPath);
    }

    public function createNotifications()
    {
        return null;
    }

    /**
     * @return DataObject
     */
    public function getDataObject(): DataObject
    {
        return $this->dataObject;
    }

    /**
     * @param DataObject $dataObject
     */
    public function setDataObject($dataObject)
    {
        $this->dataObject = $dataObject;
        return $this;
    }
}
