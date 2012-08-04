<?php

class WBI 
{
    const FILE_PREPEND  = "<?php \n\n";
    const SPACES_FILLER = '_-_';

    static protected $instance = null;

    protected $files = array();

    protected $baseurl   = null;
    protected $files_dir = null;
    protected $get       = null;
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
        $this->get  = $_GET;
        $this->post = $_POST;

        $this->root_dir  = dirname( dirname( __FILE__ ) );
        $this->files_dir = $this->root_dir . '/files';

        if ( ! is_dir( $this->files_dir ) ) {
            mkdir( $this->files_dir );
        }
        
        $this->refreshFiles();
    }

    public function isPost()
    {
        return !! $_POST;
    }

    public function getGetParam( $param, $default = null )
    {
        return isset( $this->get[$param] ) ? $this->get[$param] : $default;
    }

    public function getPostParam( $param, $default = null )
    {
        return isset( $this->post[$param] ) ? $this->post[$param] : $default;
    }

    public function createFile( $name, $code )
    {
        try {
            $filename = $name ? $name : $this->generateFilename();
            $filepath = $this->getFilePath( $filename );

            $content = self::FILE_PREPEND . $code;
            $content = stripslashes( $content );
            $content = str_replace( "\x0D", '', $content ); // removes the ^M characters

            file_put_contents( $filepath, $content );

            $this->refreshFiles();

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
        $name     = substr( str_shuffle( $this->alpha_numeric ), 0, 10 );
        $filename = $this->getFilePath( $name );

        return file_exists( $filename ) ? $this->generateFilename() : $name;
    }

    public function refreshFiles()
    {
        $iterator = new DirectoryIterator( $this->files_dir );

        foreach ( $iterator as $fileinfo ) {
            if ($fileinfo->isFile()) {
                $this->files[$fileinfo->getMTime()] = str_replace( '.php', '', $fileinfo->getFilename() );
            }
        }
        
        krsort( $this->files );
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function hasFiles()
    {
        return !! $this->files;
    }

    public function loadFile ( $filename )
    {
        $filepath = $this->getFilePath( $filename );

        if ( file_exists( $filepath ) ) {
            return array(
                $filename,
                $filepath,
                str_replace( self::FILE_PREPEND, '', file_get_contents( $filepath ) )
            );
        } else {
            return array(
                null,
                null,
                null
            );
        }
    }
    
    public function deleteFile( $filename )
    {
        $filepath = $this->getFilePath( $filename );

        if ( file_exists( $filepath ) ) {
            unlink( $filepath );
        }
    }

    protected function getFilePath( $filename )
    {
        return $this->files_dir . '/'. $filename . '.php';
    }

    public function getBaseurl()
    {
        if ( ! $this->baseurl ) {
            $scriptNameParts = explode( '/', $_SERVER['SCRIPT_NAME'] );

            array_pop( $scriptNameParts );

            $this->baseurl = 'http://' . $_SERVER['HTTP_HOST'] . implode( '/', $scriptNameParts );
        }

        return $this->baseurl;
    }

}
