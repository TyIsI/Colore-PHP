<?php

define( 'DEBUG', true );
define( 'LOGLEVEL', LOG_DEBUG );

define( 'BASEDIR', realpath( dirname( __FILE__ ) ) );

/**
 * If $config (array) does not exist, create it.
 */
if( ! isset( $config ) || ! is_array( $config ) )
	$config = array();

/**
 * Set up the array holding directories with code and configurables.
 */ 
$init_dirs = array();
$init_dirs[] = "etc";
$init_dirs[] = "core";
$init_dirs[] = "code";
$init_dirs[] = "extensions";
$init_dirs[] = "helpers";
$init_dirs[] = "interfaces";
$init_dirs[] = "renderers";
$init_dirs[] = "resolvers";

/**
 * Iterate over the array.
 */
while( list( , $init_dir ) = each( $init_dirs ) ) {
	if( $dh = opendir( $init_dir ) ) {
		while( ( $file = readdir( $dh ) ) !== false ) {
			if( preg_match( '/.*\.php$/', $file ) && ( $file != basename( __FILE__ ) ) ) {
				$required_file = sprintf( "%s/%s", $init_dir, $file );
				if( LOGLEVEL & LOG_DEBUG ) error_log( sprintf( "%s: Loading %s", basename( __FILE__ ), $required_file ) );
				require_once( $required_file );
			}
		}
		closedir( $dh );
	}
}

/**
 * Load custom code that needs to be initialized manually.
 */
require_once( 'custom/init.php' );

if( LOGLEVEL & LOG_DEBUG ) error_log( sprintf( "%s: %s", basename( __FILE__ ), "Instancing ColoreEngine object" ) );
$colore = new ColoreEngine();

if( LOGLEVEL & LOG_DEBUG ) error_log( sprintf( "%s: %s", basename( __FILE__ ), "Instancing ColoreRequest object" ) );
$colore->Service();

$colore->Dispatch( $cro );

if( LOGLEVEL & LOG_DEBUG ) error_log( sprintf( "%s: %s", basename( __FILE__ ), "Dispatching Render" ) );
$colore->Render( $cro );

?>
