[[+include file="header.tpl"]][[+foreach $POSTS as $POST]]

            <article>
                <header>
                    <h2>
                        [[+if $POSTS_MODE == $MODE_FRONTPAGE]]<a href="[[+path_for
                            name="post"
                            data=[
                                "id" => "[[+$POST->getId()]]",
                                "alias" => "[[+$POST->getAlias()]]"
                            ]
                        ]]">[[+$POST->getTitle()]]</a>[[+else]][[+$POST->getTitle()]][[+/if]]

                    </h2>
                    <div class="meta">
                        <p>Posted: [[+$POST->getPosted()]][[+if $POST->getEdited() !== null]]<span>|</span>Edited: [[+$POST->getEdited()]][[+/if]]</p>
                    </div>
                </header>
[[+$POST->getContent($POSTS_CONTENT_MODE)]]
            [[+if $POSTS_MODE === $MODE_POST]]    <div class="fb-comments" data-href="https://optimuscrime.net/blog/31-output-filters-for-code-highlighting" data-numposts="5" data-width="100%"></div>
            [[+/if]]</article>[[+/foreach]]
[[+include file="footer.tpl"]]