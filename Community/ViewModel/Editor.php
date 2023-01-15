<?php
declare(strict_types=1);

namespace DaoNguyen\Community\ViewModel;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;

class Editor implements ArgumentInterface
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var Repository
     */
    private Repository $assetRepo;

    /**
     * @param SerializerInterface $serializer
     * @param StoreManagerInterface $storeManager
     * @param Repository $assetRepo
     */
    public function __construct(
        SerializerInterface $serializer,
        StoreManagerInterface $storeManager,
        Repository $assetRepo
    ) {
        $this->serializer = $serializer;
        $this->storeManager = $storeManager;
        $this->assetRepo = $assetRepo;
    }

    /**
     * Get config for wysiwyg.
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getConfig(): string
    {
        $currentStore = $this->storeManager->getStore();
        $config = [
            'baseStaticUrl' => $currentStore->getBaseUrl(UrlInterface::URL_TYPE_STATIC),
            'baseStaticDefaultUrl' => $currentStore->getBaseUrl() . $currentStore->getBaseStaticDir() . '/',
            'directives_url' => '',
            'width' => '100%',
            'height' => '500px',
            'plugins' => [
                [
                    'name' => 'image'
                ]
            ],
            'directives_url_quoted' => '',
            'activeEditorPath' => 'mage/adminhtml/wysiwyg/tiny_mce/tinymce5Adapter',
            'tinymce' => [
                'toolbar' => "undo redo | styleselect | fontsizeselect | lineheight | forecolor backcolor | bold italic underline | alignleft aligncenter alignright | numlist bullist | link image table charmap",
                'plugins' => 'advlist autolink lists link charmap media noneditable table paste code help table image',
                'content_css' => [
                    $this->assetRepo->getUrl('mage/adminhtml/wysiwyg/tiny_mce/themes/ui.css')
                ]
            ],
            'settings' => [
                'fontsize_formats' => '10px 12px 14px 16px 18px 20px 24px 26px 28px 32px 34px 36px 38px 40px 42px 48px 52px 56px 64px 72px',
                'lineheight_formats' => '10px 12px 14px 16px 18px 20px 24px 26px 28px 32px 34px 36px 38px 40px 42px 48px 52px 56px 64px 72px',
                'style_formats' => [
                    'paragraph' => [
                        'title' => 'Paragraph',
                        'block' => 'p'
                    ],
                    'heading1' => [
                        'title' => 'Heading 1',
                        'block' => 'h1'
                    ],
                    'heading2' => [
                        'title' => 'Heading 2',
                        'block' => 'h2'
                    ],
                    'heading3' => [
                        'title' => 'Heading 3',
                        'block' => 'h3'
                    ],
                    'heading4' => [
                        'title' => 'Heading 4',
                        'block' => 'h4'
                    ],
                    'heading5' => [
                        'title' => 'Heading 5',
                        'block' => 'h5'
                    ],
                    'heading6' => [
                        'title' => 'Heading 6',
                        'block' => 'h6'
                    ],
                    'important' => [
                        'title' => 'Important',
                        'block' => 'div',
                        'classes' => 'cms-content-important'
                    ],
                    'preformatted' => [
                        'title' => 'Preformatted',
                        'block' => 'pre'
                    ]
                ]
            ]
        ];
        return $this->serializer->serialize($config);
    }
}
