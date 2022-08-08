<?php

namespace Magepow\Customemail\Controller;


class Router implements \Magento\Framework\App\RouterInterface
{
    protected $actionFactory;
    protected $_brand;
    protected $helper;
    protected $_response;

    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\ResponseInterface $response,
        \Magiccart\Lookbook\Helper\Data $helper
    )
    {
        $this->actionFactory = $actionFactory;
        $this->_response = $response;
        $this->helper = $helper;
    }

    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $identifier = trim($request->getPathInfo(), '/');
        $router     = "request-quote";
        $urlSuffix  = ".html";
        if ($length = strlen($urlSuffix)) {
            if (substr($identifier, -$length) == $urlSuffix) {
                $identifier = substr($identifier, 0, strlen($identifier) - $length);
            }
        }

        if ($identifier == $router) {
            $request->setModuleName('customemail')
                    ->setControllerName('index')
                    ->setActionName('index')
                    ->setPathInfo('/customemail/index/index');
            return $this->actionFactory->create('Magento\Framework\App\Action\Forward');

        } 
    }
}