<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Model\Activity\AbstractAction;
use DaoNguyen\Community\Model\Activity\Action\UploadMedia;
use DaoNguyen\Community\Model\ResourceModel\Activity as ResourceModel;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\ObjectManagerInterface;

class Activity extends AbstractModel
{
    public const ACTIVITY_ACTION_REGISTRATION = 1;
    public const ACTIVITY_ACTION_POST = 2;
    public const ACTIVITY_ACTION_COMMENT = 3;
    public const ACTIVITY_ACTION_UPLOAD_MEDIA = 5;

    public static array $actionModelClasses = [
        self::ACTIVITY_ACTION_COMMENT => \DaoNguyen\Community\Model\Activity\Action\Comment::class,
        self::ACTIVITY_ACTION_POST => \DaoNguyen\Community\Model\Activity\Action\Post::class,
        self::ACTIVITY_ACTION_REGISTRATION => \DaoNguyen\Community\Model\Activity\Action\Registration::class,
        self::ACTIVITY_ACTION_UPLOAD_MEDIA => UploadMedia::class
    ];

    /**
     * @var string
     */
    protected $_eventPrefix = 'community_activity_model';

    /**
     * @var ObjectManagerInterface
     */
    private ObjectManagerInterface $objectManager;

    public function __construct(
        ObjectManagerInterface $objectManager,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->objectManager = $objectManager;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @throws \Exception
     */
    public function saveActivity()
    {
        if ($this->canSaveActivity()) {
            $actionInstance = $this->getActionInstance();
            $this->setPoints($actionInstance->getPoint());
            $this->save();
            $actionInstance->setDataObject($this->getDataObject());
            $actionInstance->createNotifications();
        }
    }

    /**
     * @param int $action
     * @return AbstractAction
     */
    public function getActionInstance()
    {
        if ($this->getData('actionInstance') === null) {
            /** @var AbstractAction $instance */
            $instance = $this->objectManager->create(self::$actionModelClasses[$this->getAction()]);
            $this->setData('actionInstance', $instance);
        }
        return $this->getData('actionInstance');
    }

    public function canSaveActivity()
    {
        $conn = $this->getResource()->getConnection();
        $select = $conn->select()->from($this->getResource()->getMainTable())
            ->where('actor_id = ?', $this->getActorId())
            ->where('action = ?', $this->getAction())
            ->where('entity = ?', $this->getEntity());
        return !$conn->fetchOne($select);
    }
}
