<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Member;

use DaoNguyen\Community\Model\Member;
use DaoNguyen\Community\Model\ResourceModel\Media\Collection;
use DaoNguyen\Community\Model\Session;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use DaoNguyen\Community\Model\ResourceModel\Media\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class Gallery extends Template
{
    private Session $session;

    private CollectionFactory $collectionFactory;

    private $currentMember;

    private StoreManagerInterface $storeManager;

    public function __construct(
        CollectionFactory $collectionFactory,
        Session $session,
        StoreManagerInterface $storeManager,
        Template\Context $context,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->session = $session;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * @return Collection
     */
    public function getMedia()
    {
        $collection = $this->collectionFactory->create();
        $currentMember = $this->getMember();
        $collection->addFieldToFilter('member_id', ['eq' => $currentMember->getId()]);
        return $collection;
    }

    public function getMediaPath(string $path)
    {
        $currentMember = $this->getMember();
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'community/members/' . $currentMember->getUuid() . '/' . $path;
    }

    /**
     * @return Member
     */
    public function getMember()
    {
        if ($this->currentMember === null) {
            $this->currentMember = $this->session->getCurrentMember();
        }
        return $this->currentMember;
    }
}
