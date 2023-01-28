<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Member;

use DaoNguyen\Community\Model\Member;
use DaoNguyen\Community\Model\Session;
use Exception;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

class LikedPost implements HttpGetActionInterface
{
    /**
     * @var JsonFactory
     */
    private JsonFactory $jsonFactory;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var Session
     */
    private Session $memberSession;

    /**
     * @param JsonFactory $jsonFactory
     * @param RequestInterface $request
     * @param Session $memberSession
     */
    public function __construct(JsonFactory $jsonFactory, RequestInterface $request, Session $memberSession)
    {
        $this->jsonFactory = $jsonFactory;
        $this->request = $request;
        $this->memberSession = $memberSession;
    }

    /**
     * Execute action based on request and return result
     *
     * @return Json
     */
    public function execute(): Json
    {
        try {
            /** @var Member $currentMember */
            $currentMember = $this->memberSession->getCurrentMember();
            $postId = (int) $this->request->getParam('postId');
            $isLiked = $currentMember->isLikedPost($postId);
        } catch (Exception) {
            $isLiked = false;
        }
        return $this->jsonFactory->create()->setData(['isLiked' => $isLiked]);
    }
}
