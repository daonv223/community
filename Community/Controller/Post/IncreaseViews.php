<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Controller\Post;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Data\Form\FormKey\Validator;

class IncreaseViews implements HttpPostActionInterface
{
    /**
     * @var Validator
     */
    private Validator $formKeyValidator;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @param Validator $formKeyValidator
     * @param RequestInterface $request
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        Validator $formKeyValidator,
        RequestInterface $request,
        ResourceConnection $resourceConnection
    ) {
        $this->formKeyValidator = $formKeyValidator;
        $this->request = $request;
        $this->resourceConnection = $resourceConnection;
    }

    public function execute()
    {
        if ($this->formKeyValidator->validate($this->request)) {
            $postId = (int) $this->request->getParam('postId');
            $data = ['views' => new \Zend_Db_Expr('views + 1'), 'updated_at' => new \Zend_Db_Expr('updated_at')];
            $this->resourceConnection->getConnection()->update('community_post', $data, 'entity_id = ' . $postId);
        }
    }
}
