<?



function page_top(String $title)
{
    require_once '../config.php';
    global $base_url;

    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="' . $base_url . '/static/style.css">
        <title>' . $title . '</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body>';
}

function page_bottom()
{
    echo '
    </body>
    </html>';
}
