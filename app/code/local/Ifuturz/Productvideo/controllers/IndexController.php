<?php
/**
 * @package Ifuturz_Productvideo
 */
class Ifuturz_Productvideo_IndexController extends Mage_Core_Controller_Front_Action
{

	public function indexAction()
	{
		$this->loadLayout()->renderLayout();
	}
}