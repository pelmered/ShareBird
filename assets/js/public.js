//JavaScripts for the front end of the plugin
(function ($) {
	"use strict";
	$(function () {

            $('.sharebird').socialjs({
                GooglePlusAPIProvider: sharebird_options.GooglePlusAPIProviderURI
            });
            /*
            $('.sharebird').simplesharebuttons({
                fetchCounts: sharebird_options.fetchCounts,
                GooglePlusAPIProvider: sharebird_options.GooglePlusAPIProviderURI
                
                //Send AJAX call back to WordPress to store the counts
                /*
                onLoad : function( el ) {
                    
                    var $el = $(el);
                    
                    var $counts = {
                        facebook    : $el.simplesharebuttons('getFacebookCount'),
                        twitter     : $el.simplesharebuttons('getTwitterCount'),
                        linkedin    : $el.simplesharebuttons('getLinkedinCount'),
                        googleplus  : $el.simplesharebuttons('getGooglePlusCount')
                    };
                    
                    console.log($counts);
                    
                    $.ajax({
                        url: sharebird_options.ajaxURL,
                        data: {
                            action: 'sharebird_set_count',
                            nonce: sharebird_options.setCountNonce,
                            postid: $el.data('post-id'),
                            counts: JSON.stringify($counts)
                        }
                    }); 
                    
                }

            });
             */
    
	});
}(jQuery));
