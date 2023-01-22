<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Wysiwyg;

use DaoNguyen\Community\Block\Wysiwyg\ProductInfo;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\LayoutFactory;

class Product implements HttpGetActionInterface
{
    /**
     * @var LayoutFactory
     */
    private LayoutFactory $layoutFactory;

    /**
     * @var JsonFactory
     */
    private JsonFactory $jsonFactory;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @param LayoutFactory $layoutFactory
     * @param JsonFactory $jsonFactory
     * @param RequestInterface $request
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        LayoutFactory $layoutFactory,
        JsonFactory $jsonFactory,
        RequestInterface $request,
        ProductRepositoryInterface $productRepository
    ) {
        $this->layoutFactory = $layoutFactory;
        $this->jsonFactory = $jsonFactory;
        $this->request = $request;
        $this->productRepository = $productRepository;
    }

    /**
     * Execute the request.
     *
     * @return Json
     */
    public function execute(): Json
    {
        $sku = $this->request->getParam('sku');
        $json = $this->jsonFactory->create();
        try {
            $product = $this->productRepository->get($sku);
            $layout = $this->layoutFactory->create()->getLayout();
            /** @var ProductInfo $productInfoBlock */
            $productInfoBlock = $layout->createBlock(ProductInfo::class);
            $productInfoBlock->setProduct($product);
            $response = ['content' => $productInfoBlock->toHtml()];
        } catch (NoSuchEntityException $e) {
            $response = ['content' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $json->setData($response);
    }
}
