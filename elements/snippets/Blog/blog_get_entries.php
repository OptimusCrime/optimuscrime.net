<?php
$ret = '';

// Create query
$c = $modx->newQuery('modResource');
$c->where(array(
    'parent' => 2,
    'published' => 1,
    'deleted' => 0
));
$c->sortby('publishedon', 'DESC');

// Calculate the offset
$offset_base = 1;
if (isset($page) and $page != null and is_numeric($page)) {
    $offset_base = $page;
}
else {
    $offset_base = $modx->runSnippet('blog_get_current_page', []);
}

// Apply the offset
$c->limit($limit, ($offset_base - 1) * $limit);

// Get collection
$col = $modx->getCollection('modResource', $c);

// Loop collection
foreach ($col as $v) {
    $data = $v->toArray();

    // Find correct content to use
    $blog_intro = $v->getTVValue('blog_intro');

    if ($blog_intro != null and $blog_intro != '' and strlen($blog_intro) > 2) {
        $data['content'] = $blog_intro;
    }
    else {
        // Check if we should split the content
        $content_split = explode('<p>[more]</p>', $data['content']);

        if (count($content_split) > 1) {
            $data['content'] = $content_split[0];
        }
    }

    $ret .= $modx->getChunk('blog_entry', $data);
}

return $ret;