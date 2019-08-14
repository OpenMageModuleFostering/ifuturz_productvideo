<?php
class Ifuturz_Productvideo_Model_Observer
{    
    static protected $_singletonFlag = false; 
  
    public function saveProductTabData(Varien_Event_Observer $observer)
    {		
/*$data = $this->_getRequest()->getPost();
	echo $data['product']['product_video']; die;*/
        if (!self::$_singletonFlag) {
            self::$_singletonFlag = true;
 
            $product = $observer->getEvent()->getProduct();			
 			$productid = $product->getId();
			
            try {    
									
					$write = Mage::getSingleton('core/resource')->getConnection('core_write');
					$collection = array();
										
					$maxUplodLimit = Mage::getStoreconfig('productvideo/productvideo_group/productvideo_maxlimit');
					$maxUplodSize = Mage::getStoreconfig('productvideo/productvideo_group/productvideo_maxsize');
					
					$videopath = Mage::getBaseDir('media')."/catalog/product/videos/";
					if ($data = $this->_getRequest()->getPost())
					{																		
						$product->setProductVideo($data['product']['product_video']);
						if(isset($_FILES['upload_video']['name']) && $_FILES['upload_video']['name'] != '') 
						{		
							$data['upload_video'] = $_FILES['upload_video']['name'];
							$front = $data['upload_video'];
							
							if(is_dir($videopath.$productid)) 
							{
								//echo "directory exists!"; 
							}
							else
							{
								mkdir($videopath.$productid); 
								//echo "folder created";
							}
						}
					}						
				
					/*Multiple DELETE Video CODE */	
					
					if(!empty($_POST['chkdelete']))
					{ 	
						foreach($_POST['chkdelete'] as $checkdel)
						{
							$readresult = $write->query("SELECT video_name FROM ifuturz_productvideo WHERE video_id ='$checkdel'");
							$entityid 	= $readresult->fetch();
							$delfilef 	= $entityid['video_name'];
							
							unlink($videopath.$productid."/".$delfilef);
							
							$sql1del = "DELETE FROM `ifuturz_productvideo` WHERE video_id = '$checkdel'";
							$write->query($sql1del);
						}
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productvideo')->__('Video(s) deleted successfully.'));
					}					
					/* ADD Video CODE */	
					
					if($front != "")
					{
						if(file_exists($videopath.$productid."/".$front))
						{
							Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productvideo')->__('Video file is already exists for this product!!'));
						}
						else
						{
							$ext = end(explode(".", $front));
							
							if(($ext == "mp4") || ($ext == "flv") || ($ext == "MP4") || ($ext == "FLV"))
							{									
								$bytes = $_FILES["upload_video"]["size"];															
								$mb = number_format($bytes / 1048576, 2);
								
								$data1 = Mage::getModel('productvideo/productvideo')->getCollection()->addFieldToFilter('product_id',$productid);
								foreach($data1 as $cd)
								{						
									$collection[] = $cd['video_name'];
								}
																				
								if($mb > $maxUplodSize)
								{
									Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productvideo')->__('Sorry, your file size is too large. You can add maximum '.$maxUplodSize.' MB video.'));											
								}
								else if(count($data1) >= $maxUplodLimit)
								{
									Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productvideo')->__('You have exceeded the Video upload limit. You can add maximum '.$maxUplodLimit.' videos per product.'));
								}	
								else
								{
									$tempfile = $_FILES['upload_video']['tmp_name'];								
									move_uploaded_file($tempfile,$videopath.$productid."/".$front);									
									if(!in_array($front,$collection))
									{
										$sql1 = "INSERT INTO `ifuturz_productvideo` (`product_id`,`video_name`) VALUES ('$productid','$front')";
										$write->query($sql1);	
										Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productvideo')->__('Video uploaded successfully.'));
									}
								}
								
							}
							else
							{
								Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productvideo')->__('Only .mp4 and .flv file is allowed.!'));
							}
						}
					}					
        	}            
			catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
    }
 
    /**
     * Retrieve the product model
     *
     * @return Mage_Catalog_Model_Product $product
     */
    public function getProduct()
    {
        return Mage::registry('product');
    }
 
    /**
     * Shortcut to getRequest
     *
     */
    protected function _getRequest()
    {
        return Mage::app()->getRequest();
    }
	
	public function checkInstallation($observer)
    {		
		$read = Mage::getSingleton('core/resource')->getConnection('core_read');
		$sql ="SELECT * FROM `productvideo_lck` WHERE flag='LCK' AND value='1'";
		$data = $read->fetchAll($sql);
		if(count($data)==1)
		{
		
			$admindata = $read->fetchAll("SELECT email FROM admin_user WHERE username='admin'");
	
			$storename = Mage::getStoreConfig('general/store_information/name');
			$storephone = Mage::getStoreConfig('general/store_information/phone');
			$store_address = Mage::getStoreConfig('general/store_information/address');
			$secureurl = Mage::getStoreConfig('web/unsecure/base_url');
			$unsecureurl = Mage::getStoreConfig('web/secure/base_url');			
			$sendername = Mage::getStoreConfig('trans_email/ident_general/name');
			$general_email = Mage::getStoreConfig('trans_email/ident_general/email');
			$admin_email = $admindata[0]['email'];
			
			$body = "Extension <b>'Productvideo'</b> is installed to the following detail: <br/><br/> STORE NAME: ".$storename."<br/>STORE PHONE: ".$storephone."<br/>STORE ADDRESS: ".$store_address."<br/>SECURE URL: ".$secureurl."<br/>UNSECURE URL: ".$unsecureurl."<br/>ADMIN EMAIL ADDRESS: ".$admin_email."<br/>GENERAL EMAIL ADDRESS: ".$general_email."";
			
			$mail = Mage::getModel('core/email');
			$mail->setToName('Extension Geek');
			$mail->setToEmail('extension.geek@ifuturz.com');			
			$mail->setBody($body);
			$mail->setSubject('Productvideo Extension is installed!!!');
			$mail->setFromEmail($general_email);
			$mail->setFromName($sendername);
			$mail->setType('html');
			try{
				$mail->send();
				$write = Mage::getSingleton('core/resource')->getConnection('core_write');			
				$write->query("update productvideo_lck set value='0' where flag='LCK'");
			}
			catch(Exception $e)
			{		
			}
		} 
    }
}