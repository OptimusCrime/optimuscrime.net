<?php
// Get the full URL
$full_url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Check if we should remove GET stuff
$full_url_query_split = explode('?', $full_url);
if (count($full_url_query_split) > 1) {
    $full_url = $full_url_query_split[0];
}

// Get to fetch the object
$obj = $modx->getObject('modResource', 2);
if (!$obj) {
    return 1;
}


// Try to split (this is dirty I know) on the resource alias we got provided
$resource_alias = $obj->get('alias');
$url_split = explode($resource_alias, $full_url);
if (count($url_split) > 1) {
    $alias_endfix = $url_split[1];
    $alias_endfix_split = explode('/', $alias_endfix);
    
    // Loop the remaining url and find the first with some actual content
    $alias_to_match = null;
    foreach ($alias_endfix_split as $v) {
        if (strlen($v) > 0 and is_numeric($v)) {
            return (int) $v;
        }
    }
}

// Something went wrong somewhere
return 1;
