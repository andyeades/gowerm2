<?php

namespace Elevate\Microsite\Plugin;

class LoadMicrosite
{
    /**
     * @var \Elevate\Microsite\Model\MicrositeFactory
     */
    private $micrositeFactory;


    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * Restrict constructor.
     *
     * @param \Elevate\Microsite\Model\MicrositeFactory $micrositeFactory
     */
    public function __construct(
        \Elevate\Microsite\Model\MicrositeFactory $micrositeFactory,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->micrositeFactory = $micrositeFactory;
        $this->coreRegistry = $coreRegistry;
    }

    public function beforeDispatch()
    {
      // echo "TESTING 123";

        $id = 1;

       // $contact = $this->micrositeFactory->create()->load($id);
      //  print_r($contact->getData());
        //$contact = $this->contactFactory->create()->load($id);
       // print_r($contact->getData());
       //lets load the microsite here and store it in a session


    }


}