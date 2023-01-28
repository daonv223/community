<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Helper;

use DaoNguyen\Community\Api\Data\MemberInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Asset\Repository;
use Magento\Store\Model\StoreManagerInterface;

class MemberHelper
{
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var Repository
     */
    private Repository $assetRepo;

    /**
     * @param StoreManagerInterface $storeManager
     * @param Repository $assetRepo
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Repository $assetRepo
    ) {
        $this->storeManager = $storeManager;
        $this->assetRepo = $assetRepo;
    }

    /**
     * Get member avatar url.
     *
     * @param MemberInterface $member
     * @return string
     * @throws NoSuchEntityException
     */
    public function getMemberAvatarUrl(MemberInterface $member): string
    {
        $baseUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        if ($member->getAvatarPath()) {
            return $baseUrl . $member->getAvatarPath();
        }
        return $this->assetRepo->getUrlWithParams('DaoNguyen_Community::images/avatar-default.png', []);
    }
}
