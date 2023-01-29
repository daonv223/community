<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Group;

use DaoNguyen\Community\Model\GroupRepository;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;
use DaoNguyen\Community\Model\ResourceModel\Group\CollectionFactory;
use DaoNguyen\Community\Model\GroupFactory;

class DataProvider extends ModifierPoolDataProvider
{
    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var GroupRepository
     */
    private GroupRepository $groupRepository;

    /**
     * @var GroupFactory
     */
    private GroupFactory $groupFactory;

    /**
     * @var WriteInterface
     */
    private WriteInterface $mediaDirectory;

    /**
     * @var array
     */
    private array $loadedData;

    /**
     * @var Mime
     */
    private Mime $mime;

    /**
     * @param RequestInterface $request
     * @param GroupRepository $groupRepository
     * @param GroupFactory $groupFactory
     * @param CollectionFactory $groupCollectionFactory
     * @param Filesystem $filesystem
     * @param Mime $mime
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     * @throws FileSystemException
     */
    public function __construct(
        RequestInterface $request,
        GroupRepository $groupRepository,
        GroupFactory $groupFactory,
        CollectionFactory $groupCollectionFactory,
        Filesystem $filesystem,
        Mime $mime,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
        $this->collection = $groupCollectionFactory->create();
        $this->request = $request;
        $this->groupRepository = $groupRepository;
        $this->groupFactory = $groupFactory;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->mime = $mime;
    }

    /**
     * Get data for form.
     *
     * @return array
     */
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $entityId = (int) $this->request->getParam($this->getRequestFieldName());
        if ($entityId) {
            try {
                $group = $this->groupRepository->getBydId($entityId);
            } catch (NoSuchEntityException) {
                $group = $this->groupFactory->create();
            }
            $data = $group->getData();
            if ($group->getAvatarPath()) {
                unset($data['avatar_path']);
                $avatarPath = $group->getAvatarPath();
                $stat = $this->mediaDirectory->stat($group->getAvatarPath());
                $data['avatar_path'][0]['name'] = basename($avatarPath);
                $data['avatar_path'][0]['url'] = $group->getAvatarUrl();
                $data['avatar_path'][0]['type'] = $this->mime
                    ->getMimeType($this->mediaDirectory->getAbsolutePath($avatarPath));
                $data['avatar_path'][0]['value'] = $avatarPath;
                $data['avatar_path'][0]['size'] = $stat['size'];
            }
            $this->loadedData[$group->getId()] = $data;
            return $this->loadedData;
        }
        return [];
    }
}
