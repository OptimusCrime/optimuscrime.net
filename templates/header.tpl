<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <base href="[[+base_url]]" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
    <title>[[+$SITE_TITLE]]</title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.6.0/styles/default.min.css">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Oxygen:400,700,300|Crimson+Text:400,400italic&amp;subset=latin,latin-ext" >
    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body[[+if $POSTS_MODE === $MODE_FRONTPAGE]] class="frontpage"[[+elseif $POSTS_MODE === $MODE_POST]] class="post"[[+/if]]>
<div id="fb-root"></div>
    <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=536536956507787";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    <header class="top">
        <div class="container">
            <h1>
                <a href="[[+base_url]]">OptimusCrime.net</a>
            </h1>
            <h2>Computers &amp; Programming</h2>
        </div>
    </header>
    <div class="container">
        <main>
