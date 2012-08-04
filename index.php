<?php

error_reporting( E_ALL );

require_once 'lib/WBI.php';

$wbi = WBI::app();

$wbi->init();

if ( $wbi->isPost() ) {
    $code = $wbi->getPostParam( 'code' );

    list( $filename, $filepath ) = $wbi->createFile( $wbi->getPostParam( 'name' ), $code );
} else {
    $delete = str_replace( WBI::SPACES_FILLER, ' ', $wbi->getGetParam( 'delete' ) );

    if ( $delete ) {
        $wbi->deleteFile( $delete );

        header( 'location: ' . $wbi->getBaseurl() );
    }

    $script = $wbi->getGetParam( 'script' );

    if ( $script ) {
        list( $filename, $filepath, $code ) = $wbi->loadFile( $script );
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>PHP - Web Browser Interface</title>
        <meta charset="utf-8" />

        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/main.css" />
    </head>
    <body>
        <div id="wrapper" class="container">
            <div class="row">
                <div class="span12 offset2">
                    <h1>PHP - Web Browser Interface<br /><small>Execute PHP code in the web browser!</small></h1>
                </div>
            </div>
            <div class="row">
                <div class="span2">
                <?php if ( $wbi->hasFiles() ) : ?>
                    <ul id="files" class="nav nav-pills nav-stacked">
                    <?php foreach ( $wbi->getFiles() as $file ) : ?>
                        <li class="<?php if ( $script == $file ) echo 'active'?>">
                            <a id="<?php echo str_replace( ' ', WBI::SPACES_FILLER, $file ) ?>" href="?script=<?php echo $file ?>"><?php echo $file ?></a>
                        </li>
                    <?php endforeach ?>
                    </ul>
                    <div id="trash">
                        <img src="images/trash.png" title="drag a script on me to delete it" />
                    </div>
                <?php else : ?>
                    &nbsp;
                <?php endif ?>
                </div>
                <div class="span10">
                    <form action="" method="post" class="form-inline">
                        <div class="control-group">
                            <input name="name" type="text" value="<?php if ( isset( $filename ) ) {
                                echo stripslashes( $filename );
                            } ?>" />
                            <span class="help-inline">optional script name</span>
                        </div>
                        <div class="control-group">
                            <textarea class="span7 prettify" name="code" placeholder="enter your code here"><?php if ( isset( $code ) ) {
                                echo stripslashes( $code );
                            } ?></textarea>
                            <span class="help-inline">leave out the &lt;?php tag</span>
                        </div>
                        <div class="control-group">
                            <input class="btn btn-primary btn-large" type="submit" value="make magic!" />
                        </div>
                    </form>

                <?php if ( isset( $filepath ) ) : ?>
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
        <script src="js/jquery.1.7.2.min.js"></script>
        <script src="js/jquery-ui-1.8.22.custom.min.js"></script>
        <script src="js/app.js"></script>
    </body>
</html>
