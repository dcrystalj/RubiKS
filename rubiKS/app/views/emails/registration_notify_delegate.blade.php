<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <div>
            Na tekmo <a href="{{{ URL::to('competitions/' . $competition->short_name) }}}">{{ $competition->name }}</a> se je prijavil {{ $name }}.
        </div>
    </body>
</html>
