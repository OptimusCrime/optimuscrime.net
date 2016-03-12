<?php
// Get the full URL
$full_url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Check if we should remove GET stuff
$full_url_query_split = explode('?', $full_url);
if (count($full_url_query_split) > 1) {
    $full_url = $full_url_query_split[0];
}

// Make sure we have everything we need to continue
if (!$resource) {
    return 1;
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
    
    $alias_to_match = $alias_endfix_split[0];
    if (is_numeric($alias_to_match)) {
        return (int) $alias_to_match;
    }
}

// Something went wrong somewhere
return 1;
