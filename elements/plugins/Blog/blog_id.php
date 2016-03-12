<?php
/*
 * This plugin fires on the update/save event for Resources
 * It is used to override the default alias (sanitized pagetitle)
 * so it includes the ID of the post on the form [id]-[pagetitle].
 * The plugin is smart enough to figure out the ID itself if it is
 * not present (the Resource is new), however this is an ugly hack.
 * Also note that this plugin does not cover all cases. It does not
 * handle duplication of resources. That should be added in the future
 * I guess.
 */

// This should be hooked on OnBeforeDocFormSave only
if ($modx->event->name == 'OnBeforeDocFormSave') {
    // Make sure we have a resource
    if ($resource !== null) {
        // Make sure we are saving a blog entry
        if ($resource->parent == 2) {
            // Decide if we should override the current alias
            $new_alias = true;
            $temp_alias = $resource->alias;
            
            // If the alias has the form [id]-[whatever] we don't want to update our alias
            if (strlen($temp_alias) > 0) {
                $temp_alias_split = explode('-', $temp_alias);
                if (count($temp_alias_split) >= 2) {
                    if (is_numeric($temp_alias_split[0])) {
                        $new_alias = false;
                    }
                }
            }
            
            // Check if we should indeed override the alias
            if ($new_alias) {
                $alias = '';
                
                // Check if we have id defined
                if ($resource->id !== null) {
                    $alias = $resource->id . '-';
                }
                else {
                    // No id available, this is a new post. What follows is the uglies hack ever written
                    $c = $modx->newQuery('modResource');
                    $c->sortby('id', 'DESC');
                    $c->limit(1);
                    $obj = $modx->getObject('modResource', $c);
                    
                    if ($obj) {
                        $alias = ($obj->get('id') + 1) . '-';
                    }
                }
                
                // Add the alias value itself to the new alias
                $alias .= $temp_alias;
                
                // Set the new alias, the processor does everything else
                $resource->alias = $alias;
            }
        }
    }
}
?>