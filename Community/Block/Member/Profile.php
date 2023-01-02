<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Member;

use DaoNguyen\Community\Api\Data\MemberInterface;
use DaoNguyen\Community\Api\Data\MemberInterfaceFactory;
use DaoNguyen\Community\Api\MemberRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;

class Profile extends Template
{
    /**
     * @var MemberRepositoryInterface
     */
    private MemberRepositoryInterface $memberRepository;

    /***
     * @var Session
     */
    private Session $customerSession;

    /**
     * @var MemberInterface|null
     */
    private ?MemberInterface $currentMember;

    /**
     * @var MemberInterfaceFactory
     */
    private MemberInterfaceFactory $memberInterfaceFactory;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     * @param MemberInterfaceFactory $memberInterfaceFactory
     * @param MemberRepositoryInterface $memberRepository
     * @param Session $session
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        MemberInterfaceFactory $memberInterfaceFactory,
        MemberRepositoryInterface $memberRepository,
        Session $session,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->memberRepository = $memberRepository;
        $this->customerSession = $session;
        $this->memberInterfaceFactory = $memberInterfaceFactory;
        $this->storeManager = $storeManager;
        $this->currentMember = null;
    }

    /**
     * Is customer join the community.
     *
     * @return bool
     */
    public function isRegistered(): bool
    {
        return $this->getMember()->isRegistered();
    }

    /**
     * Get avatar path.
     *
     * @return string
     */
    public function getAvatarPath(): string
    {
        $member = $this->getMember();
        if ($member->getAvatarPath()) {
            try {
                return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $member->getAvatarPath();
            } catch (NoSuchEntityException $e) {
                return $this->getViewFileUrl('DaoNguyen_Community::images/avatar-default.png');
            }
        }
        return $this->getViewFileUrl('DaoNguyen_Community::images/avatar-default.png');
    }

    /**
     * Get member.
     */
    public function getMember(): MemberInterface
    {
        if ($this->currentMember === null) {
            try {
                $this->currentMember = $this->memberRepository->getByCustomerId(
                    (int)$this->customerSession->getCustomerId()
                );
            } catch (LocalizedException) {
                $this->currentMember = $this->memberInterfaceFactory->create();
            }
        }
        return $this->currentMember;
    }
}
