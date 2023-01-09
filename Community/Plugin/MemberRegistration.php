<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Plugin;

use DaoNguyen\Community\Controller\Member\MemberRegistrationInterface;
use DaoNguyen\Community\Model\Session;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

class MemberRegistration
{
    /**
     * @var Session
     */
    private Session $memberSession;

    /**
     * @param Session $memberSession
     */
    public function __construct(Session $memberSession)
    {
        $this->memberSession = $memberSession;
    }

    /**
     * Executes original method if allowed, otherwise - redirects to log in
     *
     * @param MemberRegistrationInterface $subject
     * @param callable $proceed
     * @return ResponseInterface|ResultInterface
     */
    public function aroundExecute(MemberRegistrationInterface $subject, callable $proceed)
    {
        if ($this->memberSession->authenticate()) {
            return $proceed();
        }
    }
}
