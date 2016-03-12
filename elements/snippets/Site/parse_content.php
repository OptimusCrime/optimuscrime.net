<?php
/*
 * Snippet that cleans up the content-content by prepearing for prettyprint.
 * It also removes the [more] tag that can be used to only display parts a
 * blog entry on the frontpage. 
 */
 
return str_replace(['}}', '{{', '<p>[more]</p>'], ['<code>', '</code>', ''], $input);