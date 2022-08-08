<?php
 
namespace Magepow\Customemail\Block\Index;
 
 
class Index extends \Magento\Framework\View\Element\Template {
    /**
     * @var \Magento\Framework\Registry
     */

    protected $_registry;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */

    protected $_storeManager;

    /**
     * @var \Magento\Customer\Model\Session
     */

    protected $_customerSession;
    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */

    protected $_productRepository;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context, 
        \Magento\Store\Model\StoreManagerInterface $_storeManager, 
        \Magento\Framework\Registry $_registry,
        \Magento\Catalog\Model\ProductRepository $_productRepository,
        array $data = []) {
        $this->_registry = $_registry;
        $this->_storeManager  = $_storeManager; 
        $this->_productRepository  = $_productRepository; 
        parent::__construct($context, $data);
 
    }
    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
    
    public function getCurrencySymbol() {
        return $this ->_storeManager->getStore()->getBaseCurrency()->getCurrencySymbol();
    }
    public function getRegistry(){
      return $this->_registry->registry('current_product');
    }
    public function getAttribute($product){
      $product = $this->_productRepository->get($product->getSku());
      return $product;
    }


}