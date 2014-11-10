<?php 
class Webpagefx_Customerattribute_Model_Observer extends Varien_Event_Observer
{
    /**
     * Adds column to admin customers grid
     *
     * @param Varien_Event_Observer $observer
     * @return Webpagefx_Customerattribute_Model_Observer
     */
    public function appendCustomColumn(Varien_Event_Observer $observer)
    {
        $block = $observer->getBlock();
        if (!isset($block)) {
            return $this;
        }
 
        if ($block->getType() == 'adminhtml/customer_grid') {
            $block->addColumnAfter('customer_number', array(
                'header'    => 'Customer Number',
                'type'      => 'text',
                'index'     => 'customer_number'                
            ), 'name');
        }
    }
    
    public function beforeCollectionLoad(Varien_Event_Observer $observer)
    {
        $collection = $observer->getCollection();
        if (!isset($collection)) {
            return;
        }

        /**
         * Mage_Customer_Model_Resource_Customer_Collection
         */
        if ($collection instanceof Mage_Customer_Model_Resource_Customer_Collection) {
            /* @var $collection Mage_Customer_Model_Resource_Customer_Collection */
            $collection->addAttributeToSelect('customer_number');
        }
    }
    
	public function checkAttribute(Varien_Event_Observer $observer)
	{
		$customer = $observer->getEvent()->getCustomer();		
		$sample = Mage::getModel('customer/customer')->getCollection()		
		->addAttributeToFilter('customer_number', array('in' => $customer->getCustomerNumber()))
		->addAttributeToFilter('entity_id', array('nin' => array($customer->getId())))
		->addAttributeToSelect('*')
		->load();

        if($presentIds = $sample->getData())
        {
			Mage::throwException(Mage::helper('adminhtml')->__('This customer Number already exists.'));			
		}
	}
}
