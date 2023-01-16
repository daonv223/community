<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Wysiwyg\Images;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Layout;
use Magento\Framework\View\Result\LayoutFactory;

class Index implements HttpGetActionInterface
{
    /**
     * @var LayoutFactory
     */
    private LayoutFactory $resultLayoutFactory;

    /**
     * @param LayoutFactory $resultLayoutFactory
     */
    public function __construct(LayoutFactory $resultLayoutFactory)
    {
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * Execute the request.
     *
     * @return Layout
     */
    public function execute(): Layout
    {
        return $this->resultLayoutFactory->create();
    }
}
