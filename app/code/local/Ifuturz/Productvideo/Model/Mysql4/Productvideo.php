<?php
/**
 * @package Ifuturz_Productvideo
 */
class Ifuturz_Productvideo_Model_Mysql4_Productvideo extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the register_id refers to the key field in your database table.
        $this->_init('productvideo/productvideo', 'video_id');
    }
	
}