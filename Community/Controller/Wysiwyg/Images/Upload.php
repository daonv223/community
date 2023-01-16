<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Wysiwyg\Images;

use DaoNguyen\Community\Helper\Media;
use DaoNguyen\Community\Model\Media\Storage;
use Exception;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

class Upload implements HttpPostActionInterface
{
    /**
     * @var JsonFactory
     */
    private JsonFactory $jsonFactory;

    /**
     * @var Storage
     */
    private Storage $storage;

    /**
     * @var Media
     */
    private Media $mediaHelper;

    /**
     * @param JsonFactory $jsonFactory
     * @param Storage $storage
     * @param Media $mediaHelper
     */
    public function __construct(JsonFactory $jsonFactory, Storage $storage, Media $mediaHelper)
    {
        $this->jsonFactory = $jsonFactory;
        $this->storage = $storage;
        $this->mediaHelper = $mediaHelper;
    }

    /**
     * Execute the request.
     *
     * @return Json
     */
    public function execute(): Json
    {
        $resultJson = $this->jsonFactory->create();
        try {
            $currentPath = $this->mediaHelper->getCurrentPath();
            $uploaded = $this->storage->uploadFile($currentPath);
            $response = [
                'name' => $uploaded['name'],
                'type' => $uploaded['type'],
                'error' => $uploaded['error'],
                'size' => $uploaded['size'],
                'file' => $uploaded['file']
            ];
        } catch (Exception $e) {
            $response = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $resultJson->setData($response);
    }
}
