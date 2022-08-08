<?php


namespace Magepow\Customemail\Block\Index;

class Categories extends \Magento\Framework\View\Element\Template 
{

    protected $categoryFactory;
    protected $_storeManager;
    

     /**
      * @param \Magento\Framework\Registry $registry,
     */
    protected $_registry;
    protected $categoryRepository;
    
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        array $data = []
    ) {    
        $this->categoryFactory = $categoryFactory;
        $this->_storeManager = $storeManager;
        $this->_registry = $registry;
        $this->categoryRepository = $categoryRepository;
        parent::__construct($context, $data);
    }


    public function getCategoryCollection()
    {
        $storeId = $this->getStoreIdCaterory();
        $rootId = $this->getRootCategoryId();
        $collection = $this->categoryFactory->create()->getCollection()
            ->addAttributeToSelect('*')
            ->setStoreId($storeId)
            ->addFieldToFilter('path', ['like' => '1/' . $rootId . '/%'])
            ->addAttributeToFilter('is_active','1');
        $collection->addOrder('level', 'ASC');
        $collection->addOrder('position', 'ASC');
        $collection->addOrder('parent_id', 'ASC');
        $collection->addOrder('entity_id', 'ASC');
       return $collection;
    }

    public function getStoreIdCaterory()
    {
        return $this->_storeManager->getStore()->getStoreId();
    }

    public function getRootCategoryId()
    {
        return $this->_storeManager->getStore()->getRootCategoryId();
    }

    public function getCategoryName($categoryId){
        $category = $this->categoryRepository->get($categoryId);
        return $category->getName();
    }

    public function getCategoryUrl($categoryId){
        $category = $this->categoryRepository->get($categoryId);
        return $category->getUrl();
    }


  
}