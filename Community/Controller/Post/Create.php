<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Post;

use DaoNguyen\Community\Controller\Member\MemberRegistrationInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Create implements HttpGetActionInterface, MemberRegistrationInterface
{
    /**
     * @var PageFactory
     */
    private PageFactory $pageFactory;

    /**
     * @param PageFactory $pageFactory
     */
    public function __construct(PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
    }

    /**
     * Execute the request.
     *
     * @return Page|null
     */
    public function execute(): ?Page
    {
        return $this->pageFactory->create();
    }
}
