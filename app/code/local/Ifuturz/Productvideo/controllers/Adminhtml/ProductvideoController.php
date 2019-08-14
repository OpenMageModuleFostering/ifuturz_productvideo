<?php
/**
 * @package Ifuturz_Productvideo
 */
class Ifuturz_Productvideo_Adminhtml_ProductvideoController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction()
	{
		$this->loadLayout()
			->_setActiveMenu('productvideo')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Add productvideo Category Management'), Mage::helper('adminhtml')->__('Add productvideo category Management'));
		
		return $this;
	}
	
	public function indexAction() 
	{
		$this->_initAction()
			->renderLayout();
	}
	
	
	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('productvideo/productvideo')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}
/*			if($id!=0)
			{
				Mage::register('region_reload', $model);	
			}
*/
			Mage::register('productvideo_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('productvideo');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Add productvideo Management'), Mage::helper('adminhtml')->__('Add productvideo Management'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Rule News'), Mage::helper('adminhtml')->__('Rule News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('productvideo/adminhtml_productvideo_edit'))
				->_addLeft($this->getLayout()->createBlock('productvideo/adminhtml_productvideo_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productvideo')->__('productvideo does not exist'));
			$this->_redirect('*/*/');
		}
	}
	
	public function newAction() 
	{
		$this->_forward('edit');
	}
		
		public function saveAction() 
	{
		
	}
 
	
}