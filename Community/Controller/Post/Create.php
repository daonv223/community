<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Post;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Create implements HttpGetActionInterface
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
     * @return Page
     */
    public function execute()
    {
        return $this->pageFactory->create();
    }
}
