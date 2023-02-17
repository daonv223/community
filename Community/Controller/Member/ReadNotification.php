<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Member;

use DaoNguyen\Community\Model\ResourceModel\NotificationReceiver;
use DaoNguyen\Community\Model\Session;
use Exception;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

class ReadNotification extends Reaction
{
    private NotificationReceiver $notificationReceiver;

    public function __construct(
        JsonFactory $jsonFactory,
        Session $session,
        RequestInterface $request,
        NotificationReceiver $notificationReceiver
    ) {
        parent::__construct($jsonFactory, $session, $request);
        $this->notificationReceiver = $notificationReceiver;
    }

    public function execute(): Json
    {
        try {
            $currentMember = $this->session->getCurrentMember();
            $id = (int) $this->request->getParam('id', 0);
            if ($id) {
                $this->notificationReceiver->readNotification($id);
            } else {
                $this->notificationReceiver->readAll($currentMember);
            }
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
