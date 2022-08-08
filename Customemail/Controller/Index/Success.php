<?php
 
namespace Magepow\Customemail\Controller\Index;
 
class Success extends \Magento\Framework\App\Action\Action
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

        $helperTitle = "Request Quote Submitted";
        $resultPage = $this->_pageFactory->create();  
        $resultPage->getConfig()->getTitle()->set((__($helperTitle)));
        return $resultPage; 
    }
}