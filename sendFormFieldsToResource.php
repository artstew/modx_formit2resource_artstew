<?php
$doc = $modx->getObject('modResource',array('id'=>$hook->getValue('resource_id')));

$allFormFields = $hook->getValues();

foreach ($allFormFields as $field=>$value)
{
    if ($field !== 'spam' && $field !== 'resource_id'){
         $doc->set($field, $value);
    }
    if ($tv = $modx->getObject('modTemplateVar', array ('name'=>$field)))
    {
        /* handles checkboxes & multiple selects elements */
        if (is_array($value)) {
            $featureInsert = array();
            while (list($featureValue, $featureItem) = each($value)) {
                $featureInsert[count($featureInsert)] = $featureItem;
            }
            $value = implode('||',$featureInsert);
        }   
        $tv->setValue($doc->get('id'), $value);
        $tv->save();
    }
}


$migx_form_items = $hook->getValue('MIGX_id');
$migx_array = array();
foreach ($migx_form_items as $item) {
$item = array(
    'MIGX_id' => $item,
    'dev_type' => $hook->getValue('dev_type_' . $item),
    'location' => $hook->getValue('location_' . $item),
    'service_performed' => $hook->getValue('service_performed_' . $item),
    'prior_test' => $hook->getValue('prior_test_' . $item),
    'current_test' => $hook->getValue('current_test_' . $item),
    'test_result' => $hook->getValue('test_result_' . $item),
);
$migx_array[] = $item;
}
  
if (!$doc->setTVValue('equipment_list', $modx->toJson($migx_array))) {
  $modx->log(modX::LOG_LEVEL_ERROR,'There was a problem saving your data!');
  return false;
}

return true;
