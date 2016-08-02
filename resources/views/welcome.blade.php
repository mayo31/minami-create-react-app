<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" href="/css/reset.css" type="text/css" media="all" />
    <link rel="stylesheet" href="/css/common.css" type="text/css" media="all" />
    <link rel="stylesheet" href="/css/screen-max640.css" type="text/css" media="all" />
    <link rel="stylesheet" href="/css/screen-max768.css" type="text/css" media="all" />
    <link rel="stylesheet" href="/css/screen-max960.css" type="text/css" media="all" />
    <link rel="stylesheet" href="/css/screen-max1280.css" type="text/css" media="all" />
    <link rel="stylesheet" href="/css/screen-min1281.css" type="text/css" media="all" />
    <title>タイトル</title>
</head>
<body>
@if (!Auth::guest())
<input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
<input type="hidden" name="user_name" value="{{ Auth::user()->name }}">
@endif
<div id="body"></div>
<button type="button" id="overlay"></button>
<label id="overlay-close" class="overlay-close">×</label>
<div id="spinner"></div>
<script src="http://code.jquery.com/jquery-2.2.0.min.js"></script>
<script type="text/javascript" src="/js/common.js"></script>
<script type="text/javascript" src="/js/bundle.js"></script>
</body>
</html>
