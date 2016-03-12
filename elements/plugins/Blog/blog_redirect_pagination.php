<?php
// This should be hooked on OnPageNotFound only
if ($modx->event->name == 'OnPageNotFound') {
    // Try to fetch the current page
    $current_page = $modx->runSnippet('blog_get_current_page', []);
    
    // Check what the current page is. If it is 1 then something is wrong, because that should never cause a 404. Any leagal number except 1 is OK
    if ($current_page != 1) {
        // Woho, redirect the user
        $modx->sendForward(2);
    }
}