jQuery(function() { 
  // Setup the player to autoplay the next track

		var a = audiojs.createAll({
          trackEnded: function() {
            var next = $('.player li.playing').next();
            if (!next.length) next = jQuery('.player .playlink').first();
            next.addClass('playing').siblings().removeClass('playing');
            audio.load(jQuery('.taglink', next).attr('data-src'));
            audio.play();

          }
        });

        // Load in the first track
        var audio = a[0];
        first = jQuery('.player .taglink').attr('data-src');
        jQuery('.player .playlink').first().addClass('playing');
        audio.load(first);

        // Load in a track on click
        jQuery('.player .playlink').click(function(e) {
          e.preventDefault();
          jQuery(this).addClass('playing').siblings().removeClass('playing');
          audio.load(jQuery('.taglink', this).attr('data-src'));
          audio.play();
        });

});