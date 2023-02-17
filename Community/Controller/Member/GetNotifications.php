<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Member;

use DaoNguyen\Community\Model\ResourceModel\NotificationReceiver;
use DaoNguyen\Community\Model\Session;
use Exception;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

class GetNotifications implements HttpGetActionInterface
{
    protected JsonFactory $jsonFactory;

    private Session $session;

    private NotificationReceiver $notificationReceiver;

    /**
     * @param JsonFactory $jsonFactory
     * @param Session $session
     * @param NotificationReceiver $notificationReceiver
     */
    public function __construct(
        JsonFactory $jsonFactory,
        Session $session,
        NotificationReceiver $notificationReceiver
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->session = $session;
        $this->notificationReceiver = $notificationReceiver;
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
            $response = [
                'top_notifications' => $this->notificationReceiver->getTopNotifications($currentMember),
                'msgUnReadData' => $this->notificationReceiver->getMsgUnReadData($currentMember)
            ];
        } catch (Exception $e) {
            $response = [];
        }
        return $this->jsonFactory->create()->setData($response);
    }
}
