<?php
/**
 * Bootstrap - A basic MediaWiki skin based on Twitter's excellent Bootstrap CSS framework
 *
 * @Version 1.0.0
 * @Author Matthew Batchelder <borkweb@gmail.com>
 * @Copyright Matthew Batchelder 2012 - http://borkweb.com/
 * @License: GPLv2 (http://www.gnu.org/copyleft/gpl.html)
 */

if ( ! defined( 'MEDIAWIKI' ) ) {
	die( -1 );
}//end if

//File removed on new mediawiki versions (1.24.1 at least).
//require_once('includes/SkinTemplate.php');
if(file_exists('includes/SkinTemplate.php')){
    require_once('includes/SkinTemplate.php');
}

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @package MediaWiki
 * @subpackage Skins
 */
class SkinBootstrapMediaWiki extends SkinTemplate {
	/** Using Bootstrap */
	public $skinname = 'bootstrap-mediawiki';
	public $stylename = 'bootstrap-mediawiki';
	public $template = 'BootstrapMediaWikiTemplate';
	public $useHeadElement = true;

	/**
	 * initialize the page
	 */
	public function initPage( OutputPage $out ) {
		global $wgSiteJS;
		parent::initPage( $out );
		$out->addModuleScripts( 'skins.bootstrapmediawiki' );
		$out->addMeta( 'viewport', 'width=device-width, initial-scale=1, maximum-scale=1' );
        $out->addScript( '<script type="text/javascript" async defer src="https://elearning.physik.uni-frankfurt.de/local/po-unterstuetzt/po-unterstuetzt.js"></script>' );
	}//end initPage

	/**
	 * prepares the skin's CSS
	 */
	public function setupSkinUserCss( OutputPage $out ) {
		global $wgSiteCSS;

		parent::setupSkinUserCss( $out );

		$out->addModuleStyles( 'skins.bootstrapmediawiki' );

		// we need to include this here so the file pathing is right
		$out->addStyle( 'RiedbergTV/font-awesome/css/font-awesome.min.css' );

		// webfont
		$out->addStyle('https://fonts.googleapis.com/css?family=Volkhov:700,400');
	}//end setupSkinUserCss
}

/**
 * @package MediaWiki
 * @subpackage Skins
 */
class BootstrapMediaWikiTemplate extends QuickTemplate {
	/**
	 * @var Cached skin object
	 */
	public $skin;

	/**
	 * Template filter callback for Bootstrap skin.
	 * Takes an associative array of data set from a SkinTemplate-based
	 * class, and a wrapper for MediaWiki's localization database, and
	 * outputs a formatted page.
	 *
	 * @access private
	 */
	public function execute() {
		global $wgRequest, $wgUser, $wgSitename, $wgSitenameshort, $wgCopyrightLink, $wgCopyright, $wgBootstrap, $wgArticlePath, $wgGoogleAnalyticsID, $wgSiteCSS;
		global $wgEnableUploads;
		global $wgLogo;
		global $wgTOCLocation;
		global $wgNavBarClasses;
		global $wgSubnavBarClasses;

		$this->skin = $this->data['skin'];
		$action = $wgRequest->getText( 'action' );
		$url_prefix = str_replace( '$1', '', $wgArticlePath );

		// Suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();

		$this->html('headelement');
		?>
		<nav>
			<div class="navbar navbar-default navbar-fixed-top navbar-inverse <?php echo $wgNavBarClasses; ?>" role="navigation">
					<div class="container">
						<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
						<div class="navbar-header">
							<div class="navbar-app-brand">
								<a class="navbar-brand" href="<?php echo $this->data['nav_urls']['mainpage']['href'] ?>" title="<?php echo $wgSitename ?>">
									<?php echo isset( $wgLogo ) && $wgLogo ? "<img src='{$wgLogo}' alt='RiedbergTV'/> " : ''; ?>
									<?php echo $wgSitename; ?>
								</a>
							</div>
							<button class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<!--<a class="navbar-brand" href="<?php echo $this->data['nav_urls']['mainpage']['href'] ?>" title="<?php echo $wgSitename ?>"><?php echo $wgSitenameshort ?: $wgSitename; ?></a>-->
						</div>

						<div class="collapse navbar-collapse">
							<ul id="dropdown-RTV-menu" class="nav navbar-nav navbar-right">
								<?php echo $this->nav( $this->get_page_links( 'RiedbergTV:NavLinks' ) ); ?>
							</ul>
						<?php
						if ( $wgUser->isLoggedIn() ) {
							if ( count( $this->data['personal_urls'] ) > 0 ) {
								$user_icon = '';//'<span class="user-icon"><img src="https://secure.gravatar.com/avatar/'.md5(strtolower( $wgUser->getEmail())).'.jpg?s=20&r=g"/></span>';
								$user_nav = $this->get_array_links( $this->data['personal_urls'], $user_icon . $wgUser->getName(), 'user' );
								?>
								<ul<?php $this->html('userlangattributes') ?> class="nav navbar-nav navbar-right">
									<?php echo $user_nav; ?>
								</ul>
								<?php
							}//end if

							if ( count( $this->data['content_actions']) > 0 ) {
								$content_nav = $this->get_array_links( $this->data['content_actions'], 'Seite', 'page' );
								?>
								<ul class="nav navbar-nav navbar-right content-actions"><?php echo $content_nav; ?></ul>
								<?php
							}//end if
						} else {  // else if is logged in
							?>
							<ul class="nav navbar-nav navbar-right">
								<li>
								<?php echo Linker::linkKnown( SpecialPage::getTitleFor( 'Userlogin' ), wfMessage( 'login' )->text() ); ?>
								</li>
							</ul>
							<?php
						}
						?>
							<ul class="nav navbar-nav navbar-right">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i></span></a>
									<ul class="dropdown-menu">
										<li><a href="<?php echo $url_prefix; ?>Special:RecentChanges" class="recent-changes"><i class="fa fa-edit"></i> Letzte Änderungen</a></li>
										<li><a href="<?php echo $url_prefix; ?>Special:SpecialPages" class="special-pages"><i class="fa fa-star-o"></i> Spezialseiten</a></li>
										<?php if ( $wgEnableUploads ) { ?>
										<li><a href="<?php echo $url_prefix; ?>Special:Upload" class="upload-a-file"><i class="fa fa-upload"></i> Datei hochladen</a></li>
										<?php } ?>
										<li><a href="<?php echo $url_prefix; ?>Special:VideoUpload" class="upload-a-file"><i class="fa fa-video-camera"></i> Video veröffentlichen</a></li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
			</div><!-- topbar -->
			<div class="subnav subnav-NOT-fixed">
				<div class="container">
					<div class="--collapse --navbar-collapse">
						<div class="row" id="subnav-top">
							<div class="col-xs-8 col-md-7" id="subnav-top-left">
								<div class="navbar-header">
									<a class="navbar-brand" href="<?php echo $this->data['nav_urls']['mainpage']['href'] ?>" title="<?php echo $wgSitename ?>">
									<?php echo isset( $wgLogo ) && $wgLogo ? "<img src='{$wgLogo}' alt='RiedbergTV'/> " : ''; echo $wgSitename;?>
									</a>
								</div>
							</div>

							<div class="col-xs-4 col-md-5 text-right" id="subnav-top-right">
								<form class="navbar-search navbar-form" action="<?php $this->text( 'wgScript' ) ?>" id="searchform" role="search">
									<div class="input-group">
										<input class="form-control" type="search" name="search" placeholder="Suche" title="Suche in <?php echo $wgSitename; ?> [ctrl-option-f]" accesskey="f" id="searchInput" autocomplete="off">
										<label for="searchInput" class="input-group-addon"><i class="fa fa-search"></i></label>
									</div>
									<input type="hidden" name="title" value="Special:Search">
								</form>

							</div>
						</div>

						<div class="row" id="subnav-bottom">
							<div class="col-xs-12" id="subnav-bottom-catlinks">
								<ul class="nav navbar-nav">
									<?php echo $this->nav( $this->get_page_links( 'RiedbergTV:NavLinks' ) ); ?>
								</ul>
							</div>
						</div>

					</div>
				</div>
			</div>
		</nav>
		<div id="wiki-outer-body" class="<?php if($wgUser->isLoggedIn()){echo 'userIsLoggedIn';}else{echo 'userIsNotLoggedIn';} ?>">
			<div id="wiki-body" class="container">
				<?php
					if ( 'sidebar' == $wgTOCLocation ) {
						?>
						<div class="row">
							<section class="col-md-3 toc-sidebar"></section>
							<section class="col-md-9 wiki-body-section">
						<?php
					}//end if
				?>
				<?php if( $this->data['sitenotice'] ) { ?><div id="siteNotice" class="alert-message warning"><?php $this->html('sitenotice') ?></div><?php } ?>
				<?php if ( $this->data['undelete'] ): ?>
				<!-- undelete -->
				<div id="contentSub2"><?php $this->html( 'undelete' ) ?></div>
				<!-- /undelete -->
				<?php endif; ?>
				<?php if($this->data['newtalk'] ): ?>
				<!-- newtalk -->
				<div class="usermessage"><?php $this->html( 'newtalk' )  ?></div>
				<!-- /newtalk -->
				<?php endif; ?>

				<div class="pagetitle page-header">
					<h1><?php $this->html( 'title' ) ?> <small><?php $this->html('subtitle') ?></small></h1>
				</div>

				<div class="body">
				<?php $this->html( 'bodytext' ) ?>
				</div>

				<?php if ( $this->data['catlinks'] ): ?>
				<div class="category-links">
				<!-- catlinks -->
				<?php $this->html( 'catlinks' ); ?>
				<!-- /catlinks -->
				</div>
				<?php endif; ?>
				<?php if ( $this->data['dataAfterContent'] ): ?>
				<div class="data-after-content">
				<!-- dataAfterContent -->
				<?php $this->html( 'dataAfterContent' ); ?>
				<!-- /dataAfterContent -->
				</div>
				<?php endif; ?>
				<?php
					if ( 'sidebar' == $wgTOCLocation ) {
						?>
						</section></section>
						<?php
					}//end if
				?>
			</div><!-- container -->
		</div>
		<footer class="bottom">
			<div class="container">
				<div id="footer-gfx-container" class="visible-md visible-lg visible-md-block visible-lg-block">
					<img id="footer-gfx" src="/w/images/5/5b/Footer-gfx.png" />
				</div>

				<?php $this->includePage('RiedbergTV:Footer'); ?>

				<p>&copy; <?php echo date('Y'); ?> by <a href="<?php echo (isset($wgCopyrightLink) ? $wgCopyrightLink : 'http://borkweb.com'); ?>"><?php echo (isset($wgCopyright) ? $wgCopyright : 'BorkWeb'); ?></a>
					&bull; Powered by <a href="http://mediawiki.org">MediaWiki</a> &bull; <span class="poweredByPO">Build with <span style="color: #FF8E8E;font-size: 140%;">&hearts;</span> by <a href="https://physikonline.uni-frankfurt.de">PhysikOnline</a></span>
				</p>
			</div><!-- container -->
		</footer><!-- bottom -->
		
		<?php
		$this->html('bottomscripts'); /* JS call to runBodyOnloadHook */
		$this->html('reporttime');

			if ( $this->data['debug'] ) {
				?>
				<!-- Debug output:
				<?php $this->text( 'debug' ); ?>
				-->
				<?php
			}//end if
		?>
		</body>
		</html>
		<?php
	}//end execute

	/**
	 * Render one or more navigations elements by name, automatically reveresed
	 * when UI is in RTL mode
	 */
	private function nav( $nav ) {
		$output = '';
		foreach ( $nav as $topItem ) {
			$pageTitle = Title::newFromText( $topItem['link'] ?: $topItem['title'] );
			if ( array_key_exists( 'sublinks', $topItem ) ) {
				$output .= '<li class="dropdown">';
				$output .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $topItem['title'] . ' <b class="caret"></b></a>';
				$output .= '<ul class="dropdown-menu">';

				foreach ( $topItem['sublinks'] as $subLink ) {
					if ( 'divider' == $subLink ) {
						$output .= "<li class='divider'></li>\n";
					} elseif ( $subLink['textonly'] ) {
						$output .= "<li class='nav-header'>{$subLink['title']}</li>\n";
					} else {
						if( $subLink['local'] && $pageTitle = Title::newFromText( $subLink['link'] ) ) {
							$href = $pageTitle->getLocalURL();
						} else {
							$href = $subLink['link'];
						}//end else

						$slug = strtolower( str_replace(' ', '-', preg_replace( '/[^a-zA-Z0-9 ]/', '', trim( strip_tags( $subLink['title'] ) ) ) ) );
						$output .= "<li {$subLink['attributes']}><a href='{$href}' class='{$subLink['class']} {$slug}'>{$subLink['title']}</a>";
					}//end else
				}
				$output .= '</ul>';
				$output .= '</li>';
			} else {
				if( $pageTitle ) {
					$output .= '<li' . ($this->data['title'] == $topItem['title'] ? ' class="active"' : '') . '><a href="' . ( $topItem['external'] ? $topItem['link'] : $pageTitle->getLocalURL() ) . '">' . $topItem['title'] . '</a></li>';
				}//end if
			}//end else
		}//end foreach
		return $output;
	}//end nav

	/**
	 * Render one or more navigations elements by name, automatically reveresed
	 * when UI is in RTL mode
	 */
	private function nav_select( $nav ) {
		$output = '';
		foreach ( $nav as $topItem ) {
			$pageTitle = Title::newFromText( $topItem['link'] ?: $topItem['title'] );
			$output .= '<optgroup label="'.strip_tags( $topItem['title'] ).'">';
			if ( array_key_exists( 'sublinks', $topItem ) ) {
				foreach ( $topItem['sublinks'] as $subLink ) {
					if ( 'divider' == $subLink ) {
						$output .= "<option value='' disabled='disabled' class='unclickable'>----</option>\n";
					} elseif ( $subLink['textonly'] ) {
						$output .= "<option value='' disabled='disabled' class='unclickable'>{$subLink['title']}</option>\n";
					} else {
						if( $subLink['local'] && $pageTitle = Title::newFromText( $subLink['link'] ) ) {
							$href = $pageTitle->getLocalURL();
						} else {
							$href = $subLink['link'];
						}//end else

						$output .= "<option value='{$href}'>{$subLink['title']}</option>";
					}//end else
				}//end foreach
			} elseif ( $pageTitle ) {
				$output .= '<option value="' . $pageTitle->getLocalURL() . '">' . $topItem['title'] . '</option>';
			}//end else
			$output .= '</optgroup>';
		}//end foreach

		return $output;
	}//end nav_select

	private function get_page_links( $source ) {
		$titleBar = $this->getPageRawText( $source );
		$nav = array();
		foreach(explode("\n", $titleBar) as $line) {
			if(trim($line) == '') continue;
			if( preg_match('/^\*\*\s*divider/', $line ) ) {
				$nav[ count( $nav ) - 1]['sublinks'][] = 'divider';
				continue;
			}//end if

			$sub = false;
			$link = false;
			$external = false;

			if(preg_match('/^\*\s*([^\*]*)\[\[:?(.+)\]\]/', $line, $match)) {
				$sub = false;
				$link = true;
			}elseif(preg_match('/^\*\s*([^\*\[]*)\[([^\[ ]+) (.+)\]/', $line, $match)) {
				$sub = false;
				$link = true;
				$external = true;
			}elseif(preg_match('/^\*\*\s*([^\*\[]*)\[([^\[ ]+) (.+)\]/', $line, $match)) {
				$sub = true;
				$link = true;
				$external = true;
			}elseif(preg_match('/\*\*\s*([^\*]*)\[\[:?(.+)\]\]/', $line, $match)) {
				$sub = true;
				$link = true;
			}elseif(preg_match('/\*\*\s*([^\* ]*)(.+)/', $line, $match)) {
				$sub = true;
				$link = false;
			}elseif(preg_match('/^\*\s*(.+)/', $line, $match)) {
				$sub = false;
				$link = false;
			}

			if( strpos( $match[2], '|' ) !== false ) {
				$item = explode( '|', $match[2] );
				$item = array(
					'title' => $match[1] . $item[1],
					'link' => $item[0],
					'local' => true,
				);
			} else {
				if( $external ) {
					$item = $match[2];
					$title = $match[1] . $match[3];
				} else {
					$item = $match[1] . $match[2];
					$title = $item;
				}//end else

				if( $link ) {
					$item = array('title'=> $title, 'link' => $item, 'local' => ! $external , 'external' => $external );
				} else {
					$item = array('title'=> $title, 'link' => $item, 'textonly' => true, 'external' => $external );
				}//end else
			}//end else

			if( $sub ) {
				$nav[count( $nav ) - 1]['sublinks'][] = $item;
			} else {
				$nav[] = $item;
			}//end else
		}

		return $nav;
	}//end get_page_links

	private function get_array_links( $array, $title, $which ) {
		$nav = array();
		$nav[] = array('title' => $title );
		foreach( $array as $key => $item ) {
			$link = array(
				'id' => Sanitizer::escapeId( $key ),
				'attributes' => $item['attributes'],
				'link' => htmlspecialchars( $item['href'] ),
				'key' => $item['key'],
				'class' => htmlspecialchars( $item['class'] ),
				'title' => htmlspecialchars( $item['text'] ),
			);

			if( 'page' == $which ) {
				switch( $link['title'] ) {
				case 'Seite': $icon = 'file'; break;
				case 'Diskussion': $icon = 'comments'; break;
				case 'Bearbeiten': $icon = 'pencil'; break;
				case 'Versionsgeschichte': $icon = 'clock-o'; break;
				case 'Löschen': $icon = 'trash'; break;
				case 'Verschieben': $icon = 'arrows'; break;
				case 'Schützen': $icon = 'lock'; break;
				case 'Beobachten': $icon = 'eye-slash'; break;
				case 'Neu laden': $icon = 'refresh'; break;
				}//end switch

				$link['title'] = '<i class="fa fa-' . $icon . '" style="width: 18px"></i> ' . $link['title'];
			} elseif( 'user' == $which ) {
				switch( $link['title'] ) {
				case 'Diskussion': $icon = 'comments'; break;
				case 'Einstellungen': $icon = 'cog'; break;
				case 'Beobachtungsliste': $icon = 'eye-slash'; break;
				case 'Beiträge': $icon = 'list-alt'; break;
				case 'Abmelden': $icon = 'signout'; break;
				default: $icon = 'user'; break;
				}//end switch

				$link['title'] = '<i class="fa fa-' . $icon . '" style="width: 18px"></i> ' . $link['title'];
			}//end elseif

			$nav[0]['sublinks'][] = $link;
			$icon = '';
		}//end foreach

		return $this->nav( $nav );
	}//end get_array_links

	function getPageRawText($title) {
		global $wgParser, $wgUser;
		$pageTitle = Title::newFromText($title);
		if(!$pageTitle->exists()) {
			return 'Create the page [[Bootstrap:TitleBar]]';
		} else {
			$article = new Article($pageTitle);
			$wgParserOptions = new ParserOptions($wgUser);
			// get the text as static wiki text, but with already expanded templates,
			// which also e.g. to use {{#dpl}} (DPL third party extension) for dynamic menus.
			$parserOutput = $wgParser->preprocess($article->getContent(), $pageTitle, $wgParserOptions );
			return $parserOutput;
		}
	}

	function includePage($title) {
		global $wgParser, $wgUser;
		$pageTitle = Title::newFromText($title);
		if(!$pageTitle->exists()) {
			echo 'The page [[' . $title . ']] was not found.';
		} else {
			$article = new Article($pageTitle);
			$wgParserOptions = new ParserOptions($wgUser);
			$parserOutput = $wgParser->parse($article->getContent(), $pageTitle, $wgParserOptions);
			echo $parserOutput->getText();
		}
	}

	public static function link() { }
}
