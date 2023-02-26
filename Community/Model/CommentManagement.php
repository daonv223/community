<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Model;

use Magento\Customer\Helper\View;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\Store;

class CommentManagement
{
    private TransportBuilder $transportBuilder;

    private View $customerViewHelper;

    private Emulation $emulation;

    /**
     * @param TransportBuilder $transportBuilder
     * @param View $customerViewHelper
     * @param Emulation $emulation
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        View $customerViewHelper,
        Emulation $emulation
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->customerViewHelper = $customerViewHelper;
        $this->emulation = $emulation;
    }

    public function sendMailToSubscriber(Comment $comment)
    {
        $subscriber = $comment->getPost()->getMember()->getCustomer();
        $author = $comment->getMember();
        $sender = [
            'name' => 'Community Support',
            'email' => 'support@community.com'
        ];
        $storeId = Store::DEFAULT_STORE_ID;
        $transport = $this->transportBuilder->setTemplateIdentifier('community_send_mail_comment')
            ->setTemplateOptions(['area' => 'frontend', 'store' => $storeId])
            ->setTemplateVars([
                'notification' =>  $author->getNickname() . ' commented on your post.'
            ])
            ->setFrom($sender)
            ->addTo(
                $subscriber->getEmail(),
                $this->customerViewHelper->getCustomerName($subscriber)
            )
            ->getTransport();
        $this->emulation->startEnvironmentEmulation($storeId);
        $transport->sendMessage();
        $this->emulation->stopEnvironmentEmulation();
    }
}
