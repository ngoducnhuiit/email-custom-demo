<?php
 
namespace Magepow\Customemail\Block\Index;
 

use Magento\Framework\View\Element\Template\Context;
use Magento\Reports\Block\Product\Viewed;
use Magento\Catalog\Model\ProductFactory;

class ReviewQuote extends \Magento\Framework\View\Element\Template
{
    protected $_catalogSession;
    protected $_customerSession;
    protected $_checkoutSession;
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,        
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        array $data = []
    )
    {        
        $this->countryCollectionFactory = $countryCollectionFactory;
        $this->countryFactory = $countryFactory;
        parent::__construct($context, $data);
    }
    
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
        

    public function getCountryName($countryCode){
        $country = $this->countryFactory->create()->loadByCode($countryCode);
        return $country->getName();
    }

    public function getCountryCollection()
    {
        $collection = $this->countryCollectionFactory->create()->loadByStore();
        return $collection;
    }

    public function getCountries()
    {
        $countryCollection = $this->getCountryCollection();
        $countries = [];
        foreach ($countryCollection->getData() as $country) {
            $countries[$country['country_id']] = $this->getCountryName($country['country_id']);
        }
        return $countries;
    }

}