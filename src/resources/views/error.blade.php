<!DOCTYPE html>
<html>
<head>
    <meta name="robots" content="noindex,nofollow"/>
    <style>
        /* Copyright (c) 2010, Yahoo! Inc. All rights reserved. Code licensed under the BSD License: http://developer.yahoo.com/yui/license.html */
        html {color: #000;background: #FFF;}

        body, div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6, pre, code, form, fieldset, legend, input, textarea, p, blockquote, th, td {margin: 0;padding: 0;}

        table {border-collapse: collapse;border-spacing: 0;}

        fieldset, img {border: 0;}

        address, caption, cite, code, dfn, em, strong, th, var {font-style: normal;font-weight: normal;}

        li {list-style: none;}

        caption, th {text-align: left;}

        h1, h2, h3, h4, h5, h6 {font-size: 100%;font-weight: normal;}

        q:before, q:after {content: '';}

        abbr, acronym {border: 0;font-variant: normal;}

        sup {vertical-align: text-top;}

        sub {vertical-align: text-bottom;}

        input, textarea, select {font-family: inherit;font-size: inherit;font-weight: inherit;}

        input, textarea, select {*font-size: 100%;}

        legend {color: #000;}

        html { background: #eee; padding: 10px }

        img { border: 0; }

        #sf-resetcontent { width: 970px; margin: 0 auto; }

        .extra-info {
            background-color: #FFFFFF;
            padding: 15px 28px;
            margin-bottom: 20px;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
        }
        {!! $css !!}
    </style>
</head>
<body>

{!! $content !!}

<div class="extra-info">
    <table width="100%">
        <tr>
            <td width="20%">URI</td>
            <td>{{ app('request')->url() }}</td>
        </tr>
        <tr>
            <td>Full URI</td>
            <td>{{ app('request')->fullUrl() }}</td>
        </tr>
        <tr>
            <td width="20%">Client IP</td>
            <td>{{ app('request')->getClientIp() ?? '127.0.0.1' }}</td>
        </tr>
        <tr>
            <td>User ID</td>
            <td>{{ auth()->user()->id ?? 'Guest' }}</td>
        </tr>
    </table>
</div>

<div class="extra-info">
    <table width="100%">
        @foreach(app('request')->all() as $key => $value)
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $value }}</td>
            </tr>
        @endforeach
    </table>
</div>

<div class="extra-info">
    🕐 {{ date('l, jS \of F Y h:i:s a') }} {{ date_default_timezone_get() }}
</div>
</body>
</html>
