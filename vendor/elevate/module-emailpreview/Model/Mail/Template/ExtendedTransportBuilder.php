<?php

namespace Elevate\EmailPreview\Model\Mail\Template;


use Magento\Framework\App\TemplateTypesInterface;
use Magento\Framework\Mail\Template\FactoryInterface;
use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Mail\Template\SenderResolverInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Mail\TransportInterfaceFactory;
use Magento\Framework\Mail\MessageInterfaceFactory;


class ExtendedTransportBuilder extends \Magento\Framework\Mail\Template\TransportBuilder
{

    /**
     * @param FactoryInterface $templateFactory
     * @param MessageInterface $message
     * @param SenderResolverInterface $senderResolver
     * @param ObjectManagerInterface $objectManager
     * @param TransportInterfaceFactory $mailTransportFactory
     * @param MessageInterfaceFactory $messageFactory
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        FactoryInterface $templateFactory,
        MessageInterface $message,
        SenderResolverInterface $senderResolver,
        ObjectManagerInterface $objectManager,
        TransportInterfaceFactory $mailTransportFactory,
        MessageInterfaceFactory $messageFactory = null
    ) {

        parent::__construct($templateFactory, $message, $senderResolver, $objectManager, $mailTransportFactory, $messageFactory);
    }


    /**
     * Get template
     *
     * @return \Magento\Framework\Mail\TemplateInterface
     */
    protected function getTemplate()
    {
        return $this->templateFactory->get($this->templateIdentifier, $this->templateModel)
                                     ->setVars($this->templateVars)
                                     ->setOptions($this->templateOptions);
    }

    /**
     * Prepare message.
     *
     * @return $this
     * @throws LocalizedException if template type is unknown
     */
    public function getTransportHtml()
    {
        //magento build the html as part of the prepare function - but its not split in a good enough way for us to grab the content

        $template = $this->getTemplate();
        $body = $template->processTemplate();

        return $body;
    }
}
