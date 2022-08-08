<?php

namespace Magepow\Customemail\Model\Mail\Template;
 
use Magento\Framework\App\TemplateTypesInterface;

class TransportBuilder extends \Magento\Framework\Mail\Template\TransportBuilder
{

    protected $message;
    protected $_attachment;
    protected $_parts = [];

   

    /**
     * Prepare message.
     *
     * @return $this
     * @throws LocalizedException if template type is unknown
     */
    protected function prepareMessage()
     {
        $template = $this->getTemplate();
        $body = $template->processTemplate();
        switch ($template->getType()) {
            case TemplateTypesInterface::TYPE_TEXT:
                $textPart = new \Zend\Mime\Part();
                $textPart->setContent($body)
                    ->setType(\Zend\Mime\Mime::TYPE_TEXT)
                    ->setCharset('utf-8')
                ;
                $this->_parts[] = $textPart;
                break;

            case TemplateTypesInterface::TYPE_HTML:
                $htmlPart = new \Zend\Mime\Part();
                $htmlPart->setContent($body)
                    ->setType(\Zend\Mime\Mime::TYPE_HTML)
                    ->setCharset('utf-8')
                ;
                $this->_parts[] = $htmlPart;
                break;

            default:
                throw new LocalizedException(
                    new Phrase('Unknown template type')
                );
        }
        parent::prepareMessage();
        $this->setPartsToBody();
        return $this;
    }

    public function setPartsToBody() {
        if($this->_attachment) $this->_parts[] = $this->_attachment;
        $mimeMessage = new \Zend\Mime\Message();
        $mimeMessage->setParts($this->_parts);
        $this->message->setBody($mimeMessage);
        return $this;
    }

}