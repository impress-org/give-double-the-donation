if ( window.doublethedonation ) {
	document.addEventListener( 'give_gateway_loaded', () => {
		const input = document.getElementById( 'dd-company-name-input' );

		if ( ! input.hasAttribute( 'data-doublethedonation-widget-id' ) ) {
			window.doublethedonation.plugin.load_streamlined_input();
		}
	} );
}
