<?php
namespace Magepow\Customemail\Controller\Index;
 
use Zend\Log\Filter\Timestamp;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;

class Post extends \Magento\Framework\App\Action\Action
{
    const XML_PATH_EMAIL_RECIPIENT_NAME = 'trans_email/ident_support/name';
    const XML_PATH_EMAIL_RECIPIENT_EMAIL = 'trans_email/ident_support/email';
    const FOLDER_LOCATION = 'contactattachment';

    protected $_inlineTranslation;
    protected $_transportBuilder;
    protected $_scopeConfig;
    protected $_logLoggerInterface;

    private $fileUploaderFactory;

    private $fileSystem;

    /**
     * Result page factory.
     *
     * @var PageFactory
     */
    protected $resultPageFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magepow\Customemail\Model\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $loggerInterface,
        UploaderFactory $fileUploaderFactory,
        File $file,
        Filesystem $fileSystem,
        PageFactory $resultPageFactory,
        array $data = []

        )
    {
        $this->_inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->_scopeConfig = $scopeConfig;
        $this->_logLoggerInterface = $loggerInterface;
        $this->messageManager = $context->getMessageManager();
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->file = $file;
        $this->fileSystem = $fileSystem;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);


    }

    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $countryName = array_key_exists('country', $post) ? $objectManager->create('\Magento\Directory\Model\Country')->load($post['country'])->getName() : '';
        $address = array_key_exists('address', $post) ? $post['address'] : '';
        $telephone = array_key_exists('telephone', $post) ? $post['telephone'] : '';
        $productcheck = array_key_exists('productcheck', $post) ? $post['productcheck'] : '';
        
        try
        {
            if ( $post['email'] == '') {
                $this->messageManager->addError("You have entered missing data in the field, please check again.");
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                return $resultRedirect;
            }


            /** @see \Magento\Contact\Controller\Index\Post::validatedParams() */
            $replyToName = !empty($variables['data']['name']) ? $variables['data']['name'] : null;
            $this->_inlineTranslation->suspend();
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

            
            $sentToEmail = $this->_scopeConfig->getValue('trans_email/ident_general/email',$storeScope);

            $sentToName = $this->_scopeConfig->getValue('trans_email/ident_general/name',$storeScope);
            $sender = [
                'name' => $post['name'],
                'email' => $sentToEmail,

            ];
            $transport = $this->_transportBuilder
            ->setTemplateIdentifier('customemail_email_template')
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
                )
                ->setTemplateVars([
                    'name'  => $post['name'],
                    'companyname' => $post['companyname'],
                    'email'  => $post['email'],
                    'address'  => $address,
                    'telephone'  => $telephone,
                    'country'  => $countryName,
                    'message' => $post['message_comment'],
                    'productsku'=> $post['productsku'],
                    'manufacturer' =>  $post['manufacturer'],
                    'description' =>  $post['short_description'],
                    'targetprice' =>  $post['targetprice'],
                    'quantity' =>  $post['quantity'],
                    'productcheck' =>  $productcheck,
  

                ])
                ->setFrom($sender)
                ->addTo($sentToEmail, $sentToName)
                ->getTransport();
                $transport->sendMessage();

                $this->_inlineTranslation->resume();
                $this->messageManager->addSuccess('Email sent successfully');
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setUrl('/customemail/index/success');
                return $resultRedirect;

        } catch(\Exception $e){
            $this->messageManager->addError($e->getMessage());
            $this->_logLoggerInterface->debug($e->getMessage());
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
            exit;
        }

    }
}