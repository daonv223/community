<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Helper;

use DaoNguyen\Community\Model\Session;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\SessionException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;

class Media extends AbstractHelper
{
    /**
     * @var string|null
     */
    private ?string $currentPath;

    /**
     * @var WriteInterface
     */
    private WriteInterface $write;

    /**
     * @var Session
     */
    private Session $memberSession;

    /**
     * @param Context $context
     * @param Filesystem $filesystem
     * @param Session $memberSession
     * @throws FileSystemException
     */
    public function __construct(
        Context $context,
        Filesystem $filesystem,
        Session $memberSession
    ) {
        parent::__construct($context);
        $this->memberSession = $memberSession;
        $this->currentPath = null;
        $this->write = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        if (!$this->write->isExist($this->write->getRelativePath($this->getDirectoryRoot()))) {
            $this->write->create($this->getDirectoryRoot());
        }
        if (!$this->write->isExist($this->write->getRelativePath($this->getMembersPath()))) {
            $this->write->create($this->getMembersPath());
        }
    }

    /**
     * Get current path.
     *
     * @return string
     * @throws FileSystemException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @throws SessionException
     */
    public function getCurrentPath(): string
    {
        if (!$this->currentPath) {
            $member = $this->memberSession->getCurrentMember();
            $path = $this->getMembersPath() . '/' . $member->getUuid();
            if (!$this->write->isExist($this->write->getRelativePath($path))) {
                $this->write->create($path);
            }
            $this->currentPath = $path;
        }
        return $this->currentPath;
    }

    /**
     * Get directory root.
     *
     * @return string
     */
    public function getDirectoryRoot(): string
    {
        return $this->write->getAbsolutePath('community');
    }

    /**
     * Get members subdirectory.
     *
     * @return string
     */
    public function getMembersPath(): string
    {
        return $this->write->getAbsolutePath('community/members');
    }
}
