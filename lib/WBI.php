<?php

class WBI 
{
    static protected $instance = null;

    protected $files_dir = null;
    protected $post      = null;
    protected $root_dir  = null;
    
    protected $alpha_numeric = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
   
    /**
     *
     * a singleton pattern
     */
    static public function app()
    {
        if ( self::$instance === null ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function init()
    {
        $this->post = $_POST;

        $this->root_dir = dirname( dirname( __FILE__ ) );
        $this->files_dir = $this->root_dir . '/files';

        if ( ! is_dir( $this->files_dir ) ) {
            mkdir( $this->files_dir );
        }
    }

    public function isPost()
    {
        return !! $_POST;
    }

    public function getParam( $param, $default = null )
    {
        return isset( $this->post[$param] ) ? $this->post[$param] : $default;
    }

    public function createFile( $name, $code )
    {
        try {
            $filename = $name ? $name : $this->generateFilename();

            $filepath = $this->files_dir . '/' . $filename . '.php';

            $content = "<?php \n\n" . $code;

            $content = stripslashes( $content );

            $content = str_replace( "\x0D", '', $content ); // removes the ^M characters

            file_put_contents( $filepath, $content );

            return array(
                $filename,
                $filepath
            );
        } catch ( Exception $e ) {
            return null;
        }
    }

    public function generateFilename()
    {
        $name = substr( str_shuffle( $this->alpha_numeric ), 0, 10 );

        $filename = $this->files_dir . '/' . $name . '.php'; 

        return file_exists( $filename ) ? $this->generateFilename() : $name;
    }

}
