//JavaScripts for the front end of the plugin

(function ($) {
	"use strict";
	$(function () {
            
            $('.sharebird').simplesharebuttons({
                fetchCounts: sharebird_options.fetchCounts,
                GooglePlusAPIProvider: sharebird_options.GooglePlusAPIProviderURI
            });
    
	});
}(jQuery));
