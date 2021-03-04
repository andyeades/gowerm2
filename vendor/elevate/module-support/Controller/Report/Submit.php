<?php
namespace Elevate\Support\Controller\Report;


class Submit extends \Magento\Framework\App\Action\Action 
{
	protected $_pageFactory;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory)
	{
		$this->_pageFactory = $pageFactory;
		return parent::__construct($context);
	}

	public function execute()
	{
        
        $post = $this->getRequest()->getPostValue();
        
        
        $email =$this->getRequest()->getParam('email');
        $link =$this->getRequest()->getParam('link');
        $description =$this->getRequest()->getParam('description');
	    
        $hash = $this->unique_id();
        $post['ip'] = $_SERVER['REMOTE_ADDR'];
        $data = json_encode($post);        
        
        $model = $this->_objectManager->create('Elevate\Support\Model\Support');
        $model->setData('hash', $hash);
        $model->setData('c_data', $data);
        $model->setData('email', $email);
        
        $model->setData('link', $link);
        $model->setData('description', $description);
        
        $model->save();
        
        $reponse['hash'] = $hash; 
        echo json_encode($reponse);
        
	}
    
    public function unique_id($l = 8) {
        return substr(md5(uniqid(mt_rand(), true)), 0, $l);
    }
    
}