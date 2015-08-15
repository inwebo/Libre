<?php
namespace Libre\Traits {

    trait Modifiable {

        public function isWritable( $filename ) {
            if( is_file( $filename ) && is_writable( dirname( $filename ) ) ) {
                $f = fopen( $filename , "r+" );
                $result = ( is_writable( $filename ) && $f );
                fclose($f);
                return $result;
            }
            if( is_dir( $filename ) ) {
                return is_writable($filename);
            }
            if( is_dir( dirname( $filename ) ) ) {
                return is_writable( dirname( $filename ) );
            }
        }

        public function write( $filename, $content ) {
            if( $this->isWritable( $filename ) ) {
                $handle = fopen( $filename, 'w+' );
                if ( flock($handle, \LOCK_EX ) ) {
                    fwrite( $handle, $content );
                    flock( $handle, \LOCK_UN );
                    fclose( $handle );
                    return true;
                }
                else {
                    return false;
                }
            }
        }

        public function append( $filename, $content ) {
            if( $this->isWritable( $filename ) ) {
                $handle = fopen( $filename, 'a' );
                if ( flock($handle, \LOCK_EX ) ) {
                    fwrite( $handle, $content );
                    flock( $handle, \LOCK_UN );
                    fclose( $handle );
                    return true;
                }
                else {
                    return false;
                }
            }
        }

        public function unlink( $filename ) {
            if( $this->isWritable( $filename ) ) {
                return unlink($filename);
            }
        }

    }

}