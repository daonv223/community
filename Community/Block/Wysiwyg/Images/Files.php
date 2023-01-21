<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Wysiwyg\Images;

use DaoNguyen\Community\Helper\Media;
use DaoNguyen\Community\Model\Member\Gallery\Storage;
use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Data\Collection\Filesystem;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;

class Files extends Template
{
    /**
     * @var Storage
     */
    private Storage $storage;

    /**
     * @var Media
     */
    private Media $mediaHelper;

    /**
     * @var Filesystem|null
     */
    private ?Filesystem $filesCollection;

    /**
     * @var ReadInterface
     */
    private ReadInterface $read;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @param Storage $storage
     * @param Media $mediaHelper
     * @param \Magento\Framework\Filesystem $filesystem
     * @param StoreManagerInterface $storeManager
     * @param Context $context
     * @param Filesystem|null $filesCollection
     * @param array $data
     */
    public function __construct(
        Storage $storage,
        Media $mediaHelper,
        \Magento\Framework\Filesystem $filesystem,
        StoreManagerInterface $storeManager,
        Template\Context $context,
        Filesystem $filesCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->storage = $storage;
        $this->mediaHelper = $mediaHelper;
        $this->filesCollection = $filesCollection;
        $this->read = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $this->storeManager = $storeManager;
    }

    /**
     * Prepared files collection for current directory.
     *
     * @return Filesystem|bool
     */
    public function getFiles(): Filesystem|bool
    {
        if ($this->filesCollection === null) {
            try {
                $this->filesCollection = $this->storage->getFilesCollection(
                    $this->mediaHelper->getCurrentPath()
                );
            } catch (Exception) {
                return false;
            }
        }
        return $this->filesCollection;
    }

    /**
     * Files collection count getter.
     *
     * @return int
     */
    public function getFilesCount(): int
    {
        $files = $this->getFiles();
        if ($files) {
            return $files->count();
        }
        return 0;
    }

    /**
     * Get view file url.
     *
     * @param DataObject $file
     * @return string
     */
    public function getFileUrl(DataObject $file): string
    {
        $relativePath = $this->read->getRelativePath($file['filename']);
        try {
            return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $relativePath;
        } catch (NoSuchEntityException) {
            return '';
        }
    }
}
