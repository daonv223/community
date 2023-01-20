<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Wysiwyg\Images;

use DaoNguyen\Community\Helper\Media;
use DaoNguyen\Community\Model\Member\Gallery\Storage;
use Exception;
use Magento\Framework\Data\Collection\Filesystem;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

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
     * @param Storage $storage
     * @param Media $mediaHelper
     * @param Context $context
     * @param Filesystem|null $filesCollection
     * @param array $data
     */
    public function __construct(
        Storage $storage,
        Media $mediaHelper,
        Template\Context $context,
        Filesystem $filesCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->storage = $storage;
        $this->mediaHelper = $mediaHelper;
        $this->filesCollection = $filesCollection;
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
            } catch (Exception $exception) {
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
        return $this->getFiles()->count();
    }
}
