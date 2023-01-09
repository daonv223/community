<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Member;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;

class Join implements MemberRegistrationInterface, HttpPostActionInterface
{
    /**
     * @var RedirectFactory
     */
    private RedirectFactory $redirectFactory;

    /**
     * @param RedirectFactory $redirectFactory
     */
    public function __construct(RedirectFactory $redirectFactory)
    {
        $this->redirectFactory = $redirectFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {

    }
}
