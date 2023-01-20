<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Member\Gallery;

use DaoNguyen\Community\Model\Member\Media\UploaderFactory;
use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Data\Collection;
use Magento\Framework\Data\Collection\Filesystem;
use Magento\Framework\Data\Collection\FilesystemFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Directory\ReadInterface;

class Storage
{
    /**
     * @var UploaderFactory
     */
    private UploaderFactory $uploaderFactory;

    /**
     * @var FilesystemFactory
     */
    private FilesystemFactory $fileCollectionFactory;

    /**
     * @var ReadInterface
     */
    private ReadInterface $read;

    /**
     * @param UploaderFactory $uploaderFactory
     * @param FilesystemFactory $fileCollectionFactory
     * @param \Magento\Framework\Filesystem $filesystem
     */
    public function __construct(
        UploaderFactory $uploaderFactory,
        FilesystemFactory $fileCollectionFactory,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->uploaderFactory = $uploaderFactory;
        $this->fileCollectionFactory = $fileCollectionFactory;
        $this->read = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
    }

    /**
     * Upload file.
     *
     * @param string $path
     * @return array
     * @throws Exception
     */
    public function uploadFile(string $path): array
    {
        if (!$this->isDirectoryAllowed($path)) {
            throw new LocalizedException(__('Your directory is not under storage root path.'));
        }
        $uploader = $this->uploaderFactory->create(['fileId' => 'image']);
        $result = $uploader->save($path);
        if (!$result) {
            throw new LocalizedException(__('We can\'t upload the file right now.'));
        }
        return $result;
    }

    /**
     * Return files.
     *
     * @param string $path
     * @return Filesystem
     * @throws Exception
     */
    public function getFilesCollection(string $path): Filesystem
    {
        $collectFiles = $this->isDirectoryAllowed($path);
        $collection = $this->fileCollectionFactory->create();
        $collection
            ->addTargetDir($path)
            ->setCollectFiles($collectFiles)
            ->setCollectRecursively(false)
            ->setOrder(
                'mtime',
                Collection::SORT_ORDER_ASC
            );
        return $collection;
    }

    /**
     * Check if directory is allowed.
     *
     * @param string $directoryPath - Absolute path to a directory.
     * @return bool
     */
    private function isDirectoryAllowed(string $directoryPath): bool
    {
        $storageRoot = $this->read->getAbsolutePath('community/members');
        $storageRootLength = strlen($storageRoot);
        $mediaSubPathname = substr($directoryPath, $storageRootLength);
        if (!$mediaSubPathname) {
            return false;
        }
        return true;
    }
}
