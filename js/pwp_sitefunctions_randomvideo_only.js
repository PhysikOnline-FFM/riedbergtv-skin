/*
  podcastwiki design specific functions
*/
pwp = {};

// For old javascript implementations
Object.keys = Object.keys || (function () {
    var hasOwnProperty = Object.prototype.hasOwnProperty,
        hasDontEnumBug = !{toString:null}.propertyIsEnumerable("toString"),
        DontEnums = [ 
            'toString', 'toLocaleString', 'valueOf', 'hasOwnProperty',
            'isPrototypeOf', 'propertyIsEnumerable', 'constructor'
        ],
        DontEnumsLength = DontEnums.length;

    return function (o) {
        if (typeof o != "object" && typeof o != "function" || o === null)
            throw new TypeError("Object.keys called on a non-object");

        var result = [];
        for (var name in o) {
            if (hasOwnProperty.call(o, name))
                result.push(name);
        }

        if (hasDontEnumBug) {
            for (var i = 0; i < DontEnumsLength; i++) {
                if (hasOwnProperty.call(o, DontEnums[i]))
                    result.push(DontEnums[i]);
            }   
        }

        return result;
    };
})();

pwp.loadpage = function(pagename, callback) {
	mw.loader.using("mediawiki.api", function(){
		var api = new mw.Api();
		api.get({ action:'parse', prop:'text', page:pagename}).done(function(){
			try {
				var html = arguments[0].parse.text['*'];
				callback(html);
			} catch(e){}
		});
	});
};

pwp.loadmsg = function(msgname, callback) {
	mw.loader.using("mediawiki.api", function(){
		var api = new mw.Api();
		api.get({ action:'query', meta:'allmessages', ammessages: msgname }).done(function(c){
			try{ callback( c.query.allmessages[0]['*']); }
			catch(e){ callback('Could not get text for '+msgname); }
		});
	});
};

pwp.random_video_setup = function(){
	pwp.random_videolinks = $(".videolink.random");

	if(!pwp.random_videolinks.length)
		return; // no random videolinks present.
	
	// prepare the random boxes, remove the contents
	pwp.random_videolinks.each(function(){
		$(this).find("a").removeData("wikitarget");
		pwp.random_video_set($(this), {
			href: 'http://riedberg.tv/',
			// Transparentes 1x1 GIF (Loesung fuer #681, Ersatz Placeholder.it)
			poster: 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==',
			label: '',
			subtitle: '',
			hideElement: true
		});
	});
	
	// get a list of already present links
	pwp.present_videos = $(".videolink").not(".random")
		.map(function(){ return $(this).find("a").data("wikitarget"); });
	// and add the current visible video (if applicable)
	pwp.present_videos.push(mw.config.get("wgTitle"));
	
	// load video data
	mw.loader.using("mediawiki.api", function(){
		var api = new mw.Api();
		api
			.get({
				action: 'ask',
				query: "[[Kategorie:Video]]|?Vorschaubild|?Videotitel|?Untertitel|?Kategorie"
			})
			.done(function(data){
				try {
					pwp.video_data = data.query.results; // for debugging in global namespace
					pwp.random_video_construct(data.query.results);
				} catch(e){
					pwp.random_video_set(pwp.random_videolinks, {error: "Bad AJAX initial data" });
				}
			});
	});
};

pwp.random_video_set = function($videolink, values) {
	if(values.href)      $videolink.find("a").attr("href", values.href);
	if(values.poster)    $videolink.find("img").attr("src", values.poster);
	if(values.label)     $videolink.find("h4 a").text(values.label);
	if(values.subtitle)  $videolink.find(".subtitle").html(values.subtitle); 
	if(values.error)     $videolink.find(".subtitle").append("<strong style='color:red'>"+values.error+'</strong>');
	
	if(values.hideElement)
		$videolink.css('opacity', 0);
	else {
		// fade in video entries when they are set (POTT #681).
		// Actually it would be most nice if fading would start when the image has been loaded.
		// Unfortunately, when the image has been cached, the onLoad event won't even be fired.
		$videolink.fadeTo("slow", 1);
	}
};

pwp.random_video_construct = function(videos) {
	// strip out all already included videos on the page out of the list
	$.each(pwp.present_videos, function(i,pagename){
		if(videos[pagename]) delete videos[pagename];
	});

	pwp.random_videolinks.each(function(){
		var pagenames = Object.keys(videos);
		// only take videos of the requested category
		if(wanted_cat = $(this).data("info")) {
			pagenames = $.grep(pagenames, function(index){
				// jquery grep ist komisch ^--- kann nich ueber objekte greppen!
				try {
					video = videos[index];
					var kategorien = $.map(video.printouts.Kategorie, function(n){ return n.fulltext; });
					return ($.inArray(wanted_cat, kategorien) >= 0);
				} catch(e) {
					t.error = "Schlecht formatierte Daten beim Catgrep";
					return true; // Besser als wenn nichts ankommt.
				}
			});
		}

		t = {}; // values for random_video_set
		if(!pagenames.length) {
			t.label = "Keine Videos mehr vorhanden";
			t.subtitle = "Alle bereits eingebunden";
		} else {
			var randomindex = Math.floor(Math.random() * pagenames.length);
			try{
				var video = videos[pagenames[randomindex]]; // shorthand
				t.href = video.fullurl;
				t.poster = video.printouts.Vorschaubild[0];
				t.label = video.printouts.Videotitel[0];
				t.subtitle = video.printouts.Untertitel[0];
				delete videos[pagenames[randomindex]]; // remove that just inserted video.
			} catch(e){
				t.error = "Invalid data";
			}
		}
		pwp.random_video_set($(this), t);
	});
};

jQuery( function( $ ) {
	pwp.random_video_setup();
});
