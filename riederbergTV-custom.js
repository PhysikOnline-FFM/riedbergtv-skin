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
	
	// hauptseite videolist
	/* Das floating Layout von BS hat ein Problem, wenn die "Videokacheln" unterschiedliche Höhen haben, daher nutze ich jetzt JS um die Höhen anzugleichen. Schmutzig, aber so arbeitet PO. 07.12. */
	if ($('.pagetitle h1').text().search('Hauptseite') > -1) {
		$( window ).resize(function() {
			/* Funktioniert auch 
			var heights = $("div.videoliste").map(function (){ return $(this).height();}).get();
			$("div.videoliste").each(function(e){ $(this).css('height', Math.max.apply(null, heights)); }); 
			*/
			
			var heights = $("div.videolink img").map(function (){ return $(this).height();}).get();
			// var widths  = $("div.videolink img").map(function (){ return $(this).width(); }).get();
			$("div.videolink img").height( Math.max.apply(null, heights));
			// $("div.videolink img").width(  Math.max.apply(null, widths) );
			// $("div.videolink h3" ).width(  Math.max.apply(null, widths) -12 );
			
			// refresh
			$("div.videolink img").width('100%');
		}).resize();
	}
});