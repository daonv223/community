<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model\Media;

use DaoNguyen\Community\Model\File\Uploader;
use DaoNguyen\Community\Model\File\UploaderFactory;
use DaoNguyen\Community\Model\Session;
use Exception;
use Magento\Framework\Exception\LocalizedException;

class Storage
{
    /**
     * @var Session
     */
    private Session $session;

    /**
     * @var UploaderFactory
     */
    private UploaderFactory $uploaderFactory;

    /**
     * @param Session $session
     * @param UploaderFactory $uploaderFactory
     */
    public function __construct(Session $session, UploaderFactory $uploaderFactory)
    {
        $this->session = $session;
        $this->uploaderFactory = $uploaderFactory;
    }

    /**
     * Get session.
     *
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
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
