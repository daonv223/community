<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Member;

use DaoNguyen\Community\Model\Session;
use Exception;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

class Reaction implements HttpPostActionInterface
{
    /**
     * @var JsonFactory
     */
    private JsonFactory $jsonFactory;

    /**
     * @var Session
     */
    private Session $session;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @param JsonFactory $jsonFactory
     * @param Session $session
     * @param RequestInterface $request
     */
    public function __construct(JsonFactory $jsonFactory, Session $session, RequestInterface $request)
    {
        $this->jsonFactory = $jsonFactory;
        $this->session = $session;
        $this->request = $request;
    }

    /**
     * Execute the request.
     *
     * @return Json
     */
    public function execute(): Json
    {
        try {
            $currentMember = $this->session->getCurrentMember();
            $postId = (int) $this->request->getParam('postId');
            $currentMember->reaction($postId);
            $response = [
                'error' => false
            ];
        } catch (Exception $e) {
            $response = [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
        return $this->jsonFactory->create()->setData($response);
    }
}
