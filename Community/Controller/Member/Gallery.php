<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Member;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;

class Gallery implements HttpGetActionInterface, MemberRegistrationInterface
{
    private PageFactory $pageFactory;

    /**
     * @param PageFactory $pageFactory
     */
    public function __construct(PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
    }

    public function execute()
    {
        return $this->pageFactory->create();
    }
}
