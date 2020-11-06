if ( window.doublethedonation ) {
	function initializePlugin() {
		document.querySelectorAll( '.dd-company-name-input' ).forEach( ( input ) => {
			if ( ! input.hasAttribute( 'data-doublethedonation-widget-id' ) ) {
				window.doublethedonation.plugin.load_streamlined_input( input );
			}
		} );
	}

	document.addEventListener( 'give_gateway_loaded', initializePlugin );

	initializePlugin();
}
