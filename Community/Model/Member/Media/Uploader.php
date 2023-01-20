<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Member\Media;

use DaoNguyen\Community\Model\Media;
use DaoNguyen\Community\Model\Session;
use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\SessionException;
use DaoNguyen\Community\Model\MediaFactory;
use DaoNguyen\Community\Model\ResourceModel\Media as MediaResource;
use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\TargetDirectory;
use Magento\Framework\Filesystem\DriverPool;

class Uploader extends \Magento\Framework\File\Uploader
{
    /**
     * @var Session
     */
    private Session $memberSession;

    /**
     * @var MediaFactory
     */
    private MediaFactory $mediaFactory;

    /**
     * @var MediaResource
     */
    private MediaResource $mediaResource;

    /**
     * @param Session $memberSession
     * @param MediaFactory $mediaFactory
     * @param MediaResource $mediaResource
     * @param string $fileId
     * @param Mime|null $fileMime
     * @param DirectoryList|null $directoryList
     * @param DriverPool|null $driverPool
     * @param TargetDirectory|null $targetDirectory
     * @param Filesystem|null $filesystem
     */
    public function __construct(
        Session $memberSession,
        MediaFactory $mediaFactory,
        MediaResource $mediaResource,
        $fileId,
        Mime $fileMime = null,
        DirectoryList $directoryList = null,
        DriverPool $driverPool = null,
        TargetDirectory $targetDirectory = null,
        Filesystem $filesystem = null
    ) {
        parent::__construct($fileId, $fileMime, $directoryList, $driverPool, $targetDirectory, $filesystem);
        $this->memberSession = $memberSession;
        $this->mediaFactory = $mediaFactory;
        $this->mediaResource = $mediaResource;
    }

    /**
     * Save to database.
     *
     * @param array $result
     * @return $this
     * @throws NoSuchEntityException
     * @throws SessionException
     * @throws LocalizedException
     * @throws Exception
     */
    protected function _afterSave($result): Uploader
    {
        $currentMember = $this->memberSession->getCurrentMember();
        /** @var Media $media */
        $media = $this->mediaFactory->create();
        $this->mediaResource->loadByMemberIdAndPath($media, (int) $currentMember->getId(), $result['full_path']);
        if (!$media->getId()) {
            $media->setData('member_id', $currentMember->getId());
            $media->setData('path', $result['full_path']);
        }
        $media->save();
        return $this;
    }
}
