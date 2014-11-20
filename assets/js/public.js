//JavaScripts for the front end of the plugin

(function ($) {
	"use strict";
	$(function () {
            
            $('.wpsimplesharebuttons').simplesharebuttons({
                GooglePlusAPIProvider: wpssb_options.GooglePlusAPIProviderURI
            });
    
	});
}(jQuery));
