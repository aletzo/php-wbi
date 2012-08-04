<?php

require_once 'lib/WBI.php';

$wbi = WBI::app();

$wbi->init();

if ( $wbi->isPost() ) {
    list( $filename, $filepath ) = $wbi->createFile( $wbi->getParam( 'name' ), $wbi->getParam( 'code' ) );
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>PHP - Web Browser Interface</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <style type="text/css">
            #wrapper {
                margin: 30px auto 0;
            }
            textarea {
                height: 200px;
            }
            input[type="submit"] {
                width: 100px;
            }
        </style>
    </head>
    <body>
        <div id="wrapper" class="container">
            <div class="row">
                <div class="span12 offset2">
                    <h1>PHP - Web Browser Interface<br /><small>Execute PHP code in the web browser!</small></h1>
                    <form action="" method="post" class="form-inline">
                        <div class="control-group">
                            <input name="name" type="text" value="<?php if ( $wbi->isPost() ) {
                                echo stripslashes( $filename );
                            } ?>" />
                            <span class="help-inline">optional script name</span>
                        </div>
                        <div class="control-group">
                            <textarea class="span7" name="code" placeholder="enter your code here"><?php if ( $wbi->isPost() ) {
                                echo stripslashes( $wbi->getParam( 'code' ) );
                            } ?></textarea>
                            <span class="help-inline">leave out the &lt;?php tag</span>
                        </div>
                        <div class="control-group">
                            <input class="btn btn-primary btn-large" type="submit" value="go" />
                        </div>
                    </form>

                <?php if ( $wbi->isPost() ) : ?>
                    <div class="well span6">
                        <?php 
                            try {
                                require_once $filepath;
                            } catch ( Exception $e ) {
                                echo $e->getMessage();
                            }
                        ?>
                    </div>
                <?php endif ?>
                </div>
            </div>
        </div>
    </body>
</html>
