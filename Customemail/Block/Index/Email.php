<?php
namespace Magepow\Customemail\Block\Index;

class Email extends \Magento\Framework\View\Element\Template
{

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context, 
        array $data = array()
    ) {
        parent::__construct($context, $data);        
    }        

}