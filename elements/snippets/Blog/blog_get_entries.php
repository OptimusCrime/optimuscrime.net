<?php
$ret = '';

// Create query
$c = $modx->newQuery('modResource');
$c->where(array(
    'parent' => 2,
    'published' => 1,
    'deleted' => 0
));
$c->sortby('published', 'DESC');

// Get collection
$col = $modx->getCollection('modResource', $c);

// Loop collection
foreach ($col as $v) {
    $ret .= $modx->getChunk('blog_entry', $v->toArray());
}

return $ret;