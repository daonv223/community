<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Wysiwyg\Images;

use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template;

class Content extends Template
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param SerializerInterface $serializer
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        SerializerInterface $serializer,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->serializer = $serializer;
    }

    /**
     * Get file browser setup object.
     *
     * @return string
     */
    public function getFilebrowserSetupObject(): string
    {
        $config = [
            'newFolder'
        ];
        return $this->serializer->serialize($config);
    }
}
