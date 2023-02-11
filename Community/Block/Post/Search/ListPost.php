<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Post\Search;

use DaoNguyen\Community\Block\Post\PostList\Toolbar;
use DaoNguyen\Community\Block\Post\Preview;
use DaoNguyen\Community\Model\Post;
use DaoNguyen\Community\Model\Post\Search\Layer;
use DaoNguyen\Community\Model\ResourceModel\Post\Collection;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template;

class ListPost extends Template implements IdentityInterface
{
    /**
     * @var Collection
     */
    private $postCollection;

    /**
     * @var Layer
     */
    private Layer $layer;

    public function __construct(
        Layer $layer,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->layer = $layer;
    }

    protected function _beforeToHtml()
    {
        $collection = $this->getPostCollection();
        $this->configureToolbar($this->getToolbar(), $collection);
    }

    /**
     * @return Collection
     */
    public function getPostCollection()
    {
        if ($this->postCollection === null) {
            return $this->postCollection = $this->initializePostCollection();
        }
        return $this->postCollection;
    }

    /**
     * @return Collection
     */
    public function initializePostCollection()
    {
        return $this->layer->getPostCollection();
    }

    /**
     * Retrieve list toolbar HTML
     *
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('post.list.toolbar');
    }

    private function configureToolbar(Toolbar $toolbar, Collection $collection)
    {
        $toolbar->setCollection($collection);
    }

    /**
     * @return Toolbar
     */
    private function getToolbar()
    {
        return $this->getChildBlock('post.list.toolbar');
    }

    public function getPostPreviewHtml(Post $post): string
    {
        /** @var Preview $previewBlock */
        $previewBlock = $this->getChildBlock('post.preview');
        $previewBlock->setPost($post);
        return $previewBlock->toHtml();
    }

    public function getIdentities()
    {
        $identities = [Post::CACHE_TAG];
        foreach ($this->getPostCollection() as /** @var Post $post */ $post) {
            $identities[] = Post::CACHE_TAG . '_' . $post->getEntityId();
        }
        return $identities;
    }
}
