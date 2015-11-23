/*
 * Kopie von Steffens PhysikOnline TV
 *
 */
$(function() {
		// slider w jQuery
		$('#the-slider').slidesjs({
			width: '100%',
			height: 360,
			navigation: false,
			pagination: {
				active: true,
				effect: "fade"
			},
			play: {
				active: true,
				// [boolean] Generate the play and stop buttons.
				// You cannot use your own buttons. Sorry.
				effect: "fade",
				// [string] Can be either "slide" or "fade".
				interval: 8000,
				// [number] Time spent on each slide in milliseconds.
				auto: true,
				// [boolean] Start playing the slideshow on load.
				swap: true,
				// [boolean] show/hide stop and play buttons
				pauseOnHover: true,
				// [boolean] pause a playing slideshow on hover
				restartDelay: 5000
				// [number] restart delay on inactive slideshow
			},
			effect: {
				slide: {
					// Slide effect settings.
					speed: 950
					// [number] Speed in milliseconds of the slide animation.
				},
				fade: {
					speed: 950,
					// [number] Speed in milliseconds of the fade animation.
					crossfade: true
					// [boolean] Cross-fade the transition.
				}
			}
		});
	});


/**
 * Some stuff of bootstrap-mediawiki skin, maybe not needed. 21.10.2015
 * Vector-specific scripts
 */
jQuery( function ( $ ) {
	// custom stuff
	var $dirs = $('.mud-dir');

	$dirs.find('.toggle').click( function( e ) {
		$(this).closest('.mud-dir').toggleClass('show-long');
	});

	if ( false && ! $dirs.find('.reverse-short').length ) {
		var opposites = {
			n: 's',
			s: 'n',
			e: 'w',
			w: 'e',
			u: 'd',
			d: 'u',
			nw: 'se',
			ne: 'sw',
			se: 'nw',
			sw: 'ne',
			in: 'out',
			out: 'in',
			climb: 'd',
			enter: 'leave',
			xmen: 'eternal'
		};

		var dirs = $dirs.find('.short').html();
		var short_dirs = [];
		var long_dirs = [];

		if ( /^From/.test( dirs ) ) {
			console.log( 'here' );
			dirs = $.trim( dirs.replace( /^From [^:]/, '' ) );
		}//end if

		dirs = dirs.split( ',' );

		for ( var i in dirs ) {
			dirs[ i ] = $.trim( dirs[ i ] );
			matches = dirs[ i ].match(/([0-9]+)?(.+)/);
			if ( typeof opposites[ matches[2] ] != 'undefined' ) {
				short_dirs.push( ( matches[1] || '' ) + matches[2] );
			} else {
				short_dirs.push( ( matches[1] || '' ) + '?' );
			}//end else

			matches = dirs[ i ].match( /^([0-9]+)(n|s|e|w|u|d|nw|ne|sw|se|out|in|climb|jump|enter|leave)$/ );

			if ( matches[1] ) {
				for ( var index = 0; index < parseInt( matches[1], 10 ); index++ ) {
					long_dirs.push( typeof opposites[ matches[2] ] != 'undefined' ? opposites[ matches[2] ] : '?' );
				}//end for
			} else {
				long_dirs.push( ( typeof opposites[ dirs[ i ] ] != 'undefined' ? opposites[ dirs[ i ] ] : '?' ) );
			}//end else
		}//end for

		$dirs.append( '<div class="reverse-short">' + short_dirs.join( ', ' ) + '</div>' );
		$dirs.append( '<div class="reverse-long">' + long_dirs.join( ', ' ) + '</div>' );
	}//end if
	else {
		$dirs.find('.reverse').hide();
	}

	$dirs.find('.reverse').click( function( e ) {
		e.stopPropagation();

		$(this).closest('.mud-dir').toggleClass('show-reverse');
	});
} );
