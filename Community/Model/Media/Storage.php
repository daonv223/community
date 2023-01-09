<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Media;

use DaoNguyen\Community\Model\File\UploaderFactory;
use Exception;
use Magento\Framework\Exception\LocalizedException;

class Storage
{
    /**
     * @var UploaderFactory
     */
    private UploaderFactory $uploaderFactory;

    /**
     * @param UploaderFactory $uploaderFactory
     */
    public function __construct(UploaderFactory $uploaderFactory)
    {
        $this->uploaderFactory = $uploaderFactory;
    }

    /**
     * Upload file.
     *
     * @param string $path
     * @return bool|array
     * @throws Exception
     */
    public function uploadFile(string $path): bool|array
    {
        $uploader = $this->uploaderFactory->create(['fileId' => 'image']);
        $result = $uploader->save($path);
        if (!$result) {
            throw new LocalizedException(__('We can\'t upload the file right now.'));
        }
        return $result;
    }
}
