<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use DaoNguyen\Community\Api\Data\MemberInterface;
use DaoNguyen\Community\Api\MemberRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Response\Http;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\SessionException;
use Magento\Framework\UrlFactory;

class Session
{
    /**
     * @var CustomerSession
     */
    private CustomerSession $customerSession;

    /**
     * @var MemberRepositoryInterface
     */
    private MemberRepositoryInterface $memberRepository;

    /**
     * @var Http
     */
    private Http $response;

    /**
     * @var UrlFactory
     */
    private UrlFactory $urlFactory;

    /**
     * @param CustomerSession $customerSession
     * @param MemberRepositoryInterface $memberRepository
     * @param Http $response
     * @param UrlFactory $urlFactory
     */
    public function __construct(
        CustomerSession $customerSession,
        MemberRepositoryInterface $memberRepository,
        Http $response,
        UrlFactory $urlFactory
    ) {
        $this->customerSession = $customerSession;
        $this->memberRepository = $memberRepository;
        $this->response = $response;
        $this->urlFactory = $urlFactory;
    }

    /**
     * Get current member.
     *
     * @return MemberInterface
     * @throws SessionException
     * @throws NoSuchEntityException
     */
    public function getCurrentMember(): MemberInterface
    {
        $customerId = (int) $this->customerSession->getCustomerId();
        if (!$customerId) {
            throw new SessionException(__('The session value is invalid. Verify and try again.'));
        }
        return $this->memberRepository->getByCustomerId($customerId);
    }

    /**
     * Authenticate.
     *
     * @return bool
     */
    public function authenticate(): bool
    {
        try {
            if ($this->customerSession->authenticate() && $this->getCurrentMember()) {
                return true;
            } else {
                return false;
            }
        } catch (SessionException) {
            return false;
        } catch (NoSuchEntityException) {
            $this->response->setRedirect(
                $this->urlFactory->create()->getUrl('community/member')
            );
            return false;
        }
    }
}
