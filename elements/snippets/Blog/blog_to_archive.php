<?php
/*
 * Simple snippet for reuse of chunks.
 */

$archive = $modx->getChunk('blog_button_archive', []);
return $modx->getChunk('blog_button_container', ['next' => $archive]);