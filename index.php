<?php

if ( $_POST ) {
    // TODO: create a file in files directory and include it above the textarea
    //       if the file is named, use that name, if possible
}

?>

<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
    
        <title>PHP - Web Browser Interface</title>
    </head>
    <body>
        <form action="" method="post">
            &lt;php
            <input type="text" name="filename" />
            <textarea name="code" rows="8" cols="40">
            </textarea>
            <input type="submit" value="execute code">
        </form>
    </body>
</html>
