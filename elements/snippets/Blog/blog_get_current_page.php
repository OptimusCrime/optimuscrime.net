<?php
/*
 * This plugin identifies the "prettified" pagination page the user
 * is currently browsing. Used to populate the blog container, the
 * redirect plugin and the pagination snippet. It finds the page
 * by taking the URL and splitting on the alias it knows is the 
 * blog container. If any numeric ids are found directly after this
 * alias, then we know we have a valid page to return, otherwise it
 * will simply return 1.
 */

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
