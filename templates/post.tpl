[[+include file="header.tpl"]]
            <article>
                <header>
                    <h2>
                        <a href="[[+path_for
                            name="post"
                            data=[
                                "id" => "[[+$ID]]",
                                "alias" => "[[+$ALIAS]]"
                            ]
                        ]]">[[+$TITLE]]</a>
                    </h2>
                    <div class="meta">
                        <p>Posted: [[+$POSTED]][[+if $EDITED !== null]]<span>|</span>Edited: [[+$EDITED]][[+/if]]</p>
                    </div>
                </header>
[[+$CONTENT]]
            </article>
[[+include file="footer.tpl"]]