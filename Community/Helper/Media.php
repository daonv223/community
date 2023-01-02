<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Helper;

use DaoNguyen\Community\Api\MemberRepositoryInterface;
use DaoNguyen\Community\Model\Session;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\FileSystemException;
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
     * @var MemberRepositoryInterface
     */
    private MemberRepositoryInterface $memberRepository;

    /**
     * @param Context $context
     * @param Filesystem $filesystem
     * @param Session $memberSession
     * @param MemberRepositoryInterface $memberRepository
     * @throws FileSystemException
     */
    public function __construct(
        Context $context,
        Filesystem $filesystem,
        Session $memberSession,
        MemberRepositoryInterface $memberRepository
    ) {
        parent::__construct($context);
        $this->write = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->write->create($this->getDirectoryRoot());
        $this->memberSession = $memberSession;
        $this->memberRepository = $memberRepository;
        $this->currentPath = null;
    }

    /**
     * Get current path.
     *
     * @return string
     * @throws FileSystemException
     */
    public function getCurrentPath(): string
    {
        if (!$this->currentPath) {
            $currentPath = $this->getDirectoryRoot();
            $memberId = $this->memberSession->getMemberId();
            if ($memberId) {
                $member = $this->memberRepository->getById($memberId);
                $path = $this->getDirectoryRoot() . '/' .$member->getNickname();
                if (!$this->write->isDirectory($this->write->getRelativePath($path))) {
                    $this->write->create($path);
                }
                $currentPath = $path;
            }
            $this->currentPath = $currentPath;
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
}
