/**
 * File customizer-control.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

/* global themeVars */
( function( $, api ) {
	var previousUrl, clearPreviousUrl, setPreviewUrl, previewUrlValue;

	clearPreviousUrl = function() {
		previousUrl = null;
	};

	setPreviewUrl = function( url, isExpanded ) {
		if ( ! url ) {
			return null;
		}

		if ( isExpanded ) {
			previousUrl = previewUrlValue.get();
			previewUrlValue.set( url );
			previewUrlValue.bind( clearPreviousUrl );

		} else {
			previewUrlValue.unbind( clearPreviousUrl );

			if ( previousUrl ) {
				previewUrlValue.set( previousUrl );
			}
		}
	};

	api.section( 'theme_db_error', function( section ) {
		previewUrlValue = api.previewer.previewUrl;

		section.expanded.bind( function( isExpanded ) {
			setPreviewUrl( themeVars.dbErrorlUrl, isExpanded );
		} );
	} );

	api.section( 'theme_email', function( section ) {
		previewUrlValue = api.previewer.previewUrl;

		section.expanded.bind( function( isExpanded ) {
			setPreviewUrl( themeVars.emailUrl, isExpanded );
		} );
	} );

	api.section( 'theme_login', function( section ) {
		previewUrlValue = api.previewer.previewUrl;

		section.expanded.bind( function( isExpanded ) {
			setPreviewUrl( themeVars.loginlUrl, isExpanded );
		} );
	} );

	api.bind( 'ready', function() {
		// Detect when the front page sections section is expanded (or closed) so we can adjust the preview accordingly.
		api.section( 'theme_options', function( section ) {
			section.expanded.bind( function( isExpanding ) {

				// Value of isExpanding will = true if you're entering the section, false if you're leaving it.
				api.previewer.send( 'section-highlight', { expanded: isExpanding });
			} );
		} );
	} );
} )( jQuery, wp.customize );
