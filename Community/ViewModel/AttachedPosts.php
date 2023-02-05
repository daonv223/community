<?php
declare(strict_types=1);

namespace DaoNguyen\Community\ViewModel;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class AttachedPosts implements ArgumentInterface
{
    /**
     * @var UrlInterface
     */
    private UrlInterface $url;

    /**
     * @param UrlInterface $url
     */
    public function __construct(UrlInterface $url)
    {
        $this->url = $url;
    }

    /**
     * Get attached posts.
     *
     * @return array
     */
    public function getAttachedPosts(): array
    {
        return [
            [
                'title' => '3 Steps to Getting Started',
                'href' => $this->url->getUrl('community/post/view', ['id' => 5])
            ],
            [
                'title' => 'Community Guidelines',
                'href' => $this->url->getUrl('community/post/view', ['id' => 7])
            ],
            [
                'title' => 'Community FAQs',
                'href' => $this->url->getUrl('community/post/view', ['id' => 6])
            ],
            [
                'title' => 'Introduce Yourself!',
                'href' => $this->url->getUrl('community/post/view', ['id' => 3])
            ],
            [
                'title' => 'Community Rank System 101',
                'href' => $this->url->getUrl('community/post/view', ['id' => 8])
            ]
        ];
    }
}
