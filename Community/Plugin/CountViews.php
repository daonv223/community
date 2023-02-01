<?php

namespace DaoNguyen\Community\Plugin;

use Magento\Framework\App\FrontControllerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;

class CountViews
{
    /**
     * @param FrontControllerInterface $subject
     * @param RequestInterface $request
     * @return array
     */
    public function beforeDispatch(FrontControllerInterface $subject, RequestInterface $request): array
    {
        // TODO: Implement plugin method.
        return [$request];
    }
}
