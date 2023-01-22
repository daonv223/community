<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Wysiwyg;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Block\Product\ReviewRendererInterface;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Helper\Output;
use Magento\Framework\Pricing\Render;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Template\Context;

class ProductInfo extends Template
{
    /**
     * @var string
     */
    protected $_template = 'DaoNguyen_Community::wysiwyg/product.phtml';

    /**
     * @var Image
     */
    private Image $imageHelper;

    /**
     * @var Output
     */
    private Output $outputHelper;

    /**
     * @param Image $imageHelper
     * @param Output $outputHelper
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Image $imageHelper,
        Output $outputHelper,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->imageHelper = $imageHelper;
        $this->outputHelper = $outputHelper;
    }

    /**
     * @var ProductInterface
     */
    private ProductInterface $product;

    /**
     * Get product.
     *
     * @return ProductInterface
     */
    public function getProduct(): ProductInterface
    {
        return $this->product;
    }

    /**
     * Set product.
     *
     * @param ProductInterface $product
     */
    public function setProduct(ProductInterface $product): void
    {
        $this->product = $product;
    }

    /**
     * Get product image url.
     *
     * @param Product $product
     * @return string
     */
    public function getImageUrl(Product $product): string
    {
        $imageUrl = $this->imageHelper->init($product, 'category_page_list');
        return $imageUrl->getUrl();
    }

    /**
     * Get output helper.
     *
     * @return Output
     */
    public function getHelper(): Output
    {
        return $this->outputHelper;
    }

    /**
     * Get product price.
     *
     * @param Product $product
     * @return string
     */
    public function getProductPrice(Product $product): string
    {
        $priceRender = $this->getLayout()->createBlock(
            Render::class,
            'product.price.render.default',
            [
                'is_product_list' => true
            ]
        );
        $price = '';
        if ($priceRender) {
            $priceRender->setPriceRenderHandle('catalog_product_prices');
            $priceRender->_prepareLayout();
            $price = $priceRender->render(
                'final_price',
                $product,
                [
                    'include_container' => true,
                    'display_minimal_price' => true,
                    'zone' => Render::ZONE_ITEM_LIST,
                    'list_category_page' => true
                ]
            );
        }
        return $price;
    }
}
