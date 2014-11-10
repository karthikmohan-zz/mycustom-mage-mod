<?php
$installer = $this;
$installer->startSetup();

$custom_attribute_label = "Customer Number";
$custom_attribute_text = "customer_number";

$entityTypeId     = $installer->getEntityTypeId('customer');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$installer->addAttribute('customer', $custom_attribute_text, array(
    'input'         => 'text',
    'type'          => 'varchar',
    'label'         => $custom_attribute_label,
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
));

$installer->addAttributeToGroup(
 $entityTypeId,
 $attributeSetId,
 $attributeGroupId,
 $custom_attribute_text,
 '999'  //sort_order
);

$oAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', $custom_attribute_text);
$oAttribute->setData('used_in_forms', array('adminhtml_customer'));
$oAttribute->save();

$installer->endSetup();

?>
