<?php
/**
 * Bootstrap MediaWiki
 *
 * @bootstrap-mediawiki.php
 * @ingroup Skins
 * @author Matthew Batchelder (http://borkweb.com)
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

if ( ! defined( 'MEDIAWIKI' ) ) die( "This is an extension to the MediaWiki package and cannot be run standalone." );

$wgExtensionCredits['skin'][] = array(
	'path'        => __FILE__,
	'name'        => 'Bootstrap Mediawiki',
	'url'         => 'http://borkweb.com',
	'author'      => '[http://borkweb.com Matthew Batchelder]',
	'description' => 'MediaWiki skin using Bootstrap 3',
);

$wgValidSkinNames['bootstrapmediawiki'] = 'BootstrapMediaWiki';
$wgAutoloadClasses['SkinBootstrapMediaWiki'] = __DIR__ . '/BootstrapMediaWiki.skin.php';


$skinDirParts = explode( DIRECTORY_SEPARATOR, __DIR__ );
$skinDir = array_pop( $skinDirParts );

$wgResourceModules['skins.bootstrapmediawiki'] = array(
	'styles' => array(
		$skinDir . '/bootstrap/css/bootstrap.min.css'            => array( 'media' => 'all' ),
		$skinDir . '/google-code-prettify/prettify.css'          => array( 'media' => 'all' ),
		$skinDir . '/style.css'                                  => array( 'media' => 'all' ),
		$skinDir . '/slidejs/slidejs.css'                        => array( 'media' => 'all' ),
		$skinDir . '/videojs-5.8.0/video-js.min.css'                 => array( 'media' => 'screen' ),
		$skinDir . '/videojs-resolution-switcher-0.4.1/lib/videojs-resolution-switcher.css' => array( 'media' => 'screen' ),
		$skinDir . '/riederbergTV-custom.css'                    => array( 'media' => 'all' ),
	),
	'scripts' => array(
		$skinDir . '/bootstrap/js/bootstrap.min.js',
		$skinDir . '/google-code-prettify/prettify.js',
		$skinDir . '/js/jquery.ba-dotimeout.min.js',
		$skinDir . '/slidejs/jquery.slides.min.js',
		$skinDir . '/js/behavior.js',
		$skinDir . '/js/html5shiv.js',
		$skinDir . '/videojs-5.8.0/video.min.js',
		$skinDir . '/videojs-resolution-switcher-0.4.1/lib/videojs-resolution-switcher.js',
		$skinDir . '/js/pwp_sitefunctions_randomvideo_only.js',
		$skinDir . '/riederbergTV-custom.js',
	),
	'dependencies' => array(
		'jquery',
		'jquery.mwExtension',
		'jquery.client',
		'jquery.cookie',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
);

/* Funktioniert eh nicht, also raus. PA 21.10.2015
if ( isset( $wgSiteJS ) ) {
	$wgResourceModules['skins.bootstrapmediawiki']['scripts'][] = $skinDir . '/' . $wgSiteJS;
}//end if

if ( isset( $wgSiteCSS ) ) {
	$wgResourceModules['skins.bootstrapmediawiki']['styles'][] = $skinDir . '/' . $wgSiteCSS;
}//end if
*/