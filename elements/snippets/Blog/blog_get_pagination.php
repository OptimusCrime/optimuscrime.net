<?php

// Get number of blog entries
$c = $modx->newQuery('modResource');
$c->where(array(
    'parent' => 2,
    'published' => 1,
    'deleted' => 0
));
$c->sortby('publishedon', 'DESC');

// Run the final query
$entries = $modx->getCount('modResource', $c);

// Get the current page
$current_page = $modx->runSnippet('blog_get_current_page', []);

// Calculate which entries are visible
$display_start = ($current_page - 1) * $limit;
$display_end = $display_start + $limit;

// Check if we should display back and forward buttons
$ret = '';
if ($display_start > 0) {
    $ret .= $modx->getChunk('blog_button_previous', ['page' => (($current_page == 2) ? '' : ($current_page - 1))]);
}
if ($entries > $display_end) {
    $ret .= $modx->getChunk('blog_button_next', ['page' => ($current_page + 1)]);
}

return $current_page . $ret;