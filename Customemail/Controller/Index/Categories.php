<?php
 
namespace Magepow\Customemail\Controller\Index;
 
class Categories extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $_pageFactory,
        array $data = []
    )
    {
        $this->_pageFactory = $_pageFactory;
        parent::__construct($context);
    }

    public function execute()
    {

        $helperTitle = "All Categories";
        $resultPage = $this->_pageFactory->create();  
        $resultPage->getConfig()->getTitle()->set((__($helperTitle)));
        return $resultPage; 
    }
}