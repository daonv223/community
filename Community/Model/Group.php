<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Model\ResourceModel\Group as ResourceModel;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class Group extends AbstractModel implements IdentityInterface
{
    public const STATUS_ENABLED = 1;
    public const STATUS_DISABLE = 0;
    public const AUTO_APPROVE = 1;
    public const NOT_AUTO_APPROVE = 0;
    public const CACHE_TAG = 'com_g';

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param StoreManagerInterface $storeManager
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        StoreManagerInterface $storeManager,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->storeManager = $storeManager;
    }

    /**
     * @var string
     */
    protected $_eventPrefix = 'community_group_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->getData('name');
    }

    /**
     * Get avatar url.
     *
     * @return string
     */
    public function getAvatarUrl(): string
    {
        try {
            $url = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)
                . $this->getData('avatar_path');
        } catch (NoSuchEntityException $e) {
            $url = '';
        }
        return $url;
    }

    /**
     * Get avatar path
     *
     * @return string|null
     */
    public function getAvatarPath(): ?string
    {
        return $this->getData('avatar_path');
    }

    /**
     * Get group description.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->getData('description');
    }

    /**
     * @inheritdoc
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG];
    }
}
