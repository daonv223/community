<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Wysiwyg\Images;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Layout;
use Magento\Framework\View\Result\LayoutFactory;

class Contents implements HttpGetActionInterface
{
    /**
     * @var LayoutFactory
     */
    private LayoutFactory $layoutFactory;

    /**
     * @param LayoutFactory $layoutFactory
     */
    public function __construct(LayoutFactory $layoutFactory)
    {
        $this->layoutFactory = $layoutFactory;
    }

    /**
     * Execute the request.
     *
     * @return Layout
     */
    public function execute(): Layout
    {
        return $this->layoutFactory->create();
    }
}
