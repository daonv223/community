<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Member;

use DaoNguyen\Community\Api\Data\MemberInterface;
use DaoNguyen\Community\Api\MemberRepositoryInterface;
use DaoNguyen\Community\Helper\Media;
use DaoNguyen\Community\Model\Media\Storage;
use Exception;
use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Filesystem\DirectoryResolver;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Filesystem;
use Magento\Framework\Validation\ValidationException;
use Magento\Customer\Model\Session;

class Save extends AbstractAccount implements HttpPostActionInterface
{
    /**
     * @var RedirectFactory
     */
    private RedirectFactory $redirectFactory;

    /**
     * @var Session
     */
    private Session $customerSession;

    /**
     * @var Validator
     */
    private Validator $formKeyValidator;

    /**
     * @var MemberRepositoryInterface
     */
    private MemberRepositoryInterface $memberRepository;

    /**
     * @var Storage
     */
    private Storage $storage;

    /**
     * @var DirectoryResolver
     */
    private DirectoryResolver $directoryResolver;

    /**
     * @var Filesystem\Directory\ReadInterface
     */
    private Filesystem\Directory\ReadInterface $read;

    /**
     * @param Context $context
     * @param RedirectFactory $redirectFactory
     * @param Session $customerSession
     * @param Validator $formKeyValidator
     * @param MemberRepositoryInterface $memberRepository
     * @param Storage $storage
     * @param DirectoryResolver $directoryResolver
     * @param Filesystem $filesystem
     */
    public function __construct(
        Context $context,
        RedirectFactory $redirectFactory,
        Session $customerSession,
        Validator $formKeyValidator,
        MemberRepositoryInterface $memberRepository,
        Storage $storage,
        DirectoryResolver $directoryResolver,
        Filesystem $filesystem
    ) {
        parent::__construct($context);
        $this->redirectFactory = $redirectFactory;
        $this->customerSession = $customerSession;
        $this->formKeyValidator = $formKeyValidator;
        $this->memberRepository = $memberRepository;
        $this->storage = $storage;
        $this->directoryResolver = $directoryResolver;
        $this->read = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
    }

    /**
     * Execute the request
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $this->redirectFactory->create()->setPath('/community/member');
        }
        $customerId = (int) $this->customerSession->getCustomerId();
        if (!$customerId) {
            $this->messageManager->addErrorMessage(__('Something went wrong while saving your registration.'));
        } else {
            try {
                $member = $this->memberRepository->getByCustomerId($customerId);
                $member->setCustomerId($customerId);
                $member->setNickname($this->getRequest()->getParam('nickname'));
                $member->setBio($this->getRequest()->getParam('bio'));
                $member->setStatus(MemberInterface::ACTIVE_STATUS);
                $this->memberRepository->save($member);
                $result = $this->uploadAvatar();
                if ($result) {
                    $avatarPath = '/' . $this->read->getRelativePath($result['path']) . '/' . $result['name'];
                    $member->setAvatarPath($avatarPath);
                    $this->memberRepository->save($member);
                }
            } catch (ValidationException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $this->redirectFactory->create()->setPath('community/member');
            } catch (Exception) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving your registration.'));
                return $this->redirectFactory->create()->setPath('community/member');
            }
            $this->messageManager->addSuccessMessage(__('We have saved your registration.'));
        }
        return $this->redirectFactory->create()->setPath('community/member');
    }

    /**
     * Upload avatar.
     *
     * @throws Exception
     */
    private function uploadAvatar(): bool|array
    {
        $currentPath = $this->_objectManager->get(Media::class)->getCurrentPath();
        if ($this->directoryResolver->validatePath($currentPath)) {
            return $this->storage->uploadFile($currentPath);
        } else {
            $this->messageManager->addErrorMessage(__('Directory %1 is not under storage root path.', $currentPath));
            return false;
        }
    }
}
