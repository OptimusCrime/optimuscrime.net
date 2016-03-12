<?php
/*
 * Returns the site caption from the Settings resource to avoid having
 * to hack everything. 
 */
 
$obj = $modx->getObject('modResource', 10);
return $obj->getTVValue('site_caption');