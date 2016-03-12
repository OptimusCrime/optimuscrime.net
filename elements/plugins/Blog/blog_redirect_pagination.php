<?php
/*
 * This plugin fires on the page not found event, that is used
 * when a page it not found in the web context. The plugin is
 * used to make a "prettier" pagination URL for the blog container.
 * Usually you would have to use ?page=1 or something, but this
 * plugin allows you to use /2 etc. It is mostly based on the
 * same snippet that identifies the current blog page your are
 * displaying, which is used by both the blog container, the
 * frontpage, and the pagination plugin. Reuse!
 */

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