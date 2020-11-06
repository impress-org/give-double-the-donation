if ( window.doublethedonation ) {
	document.addEventListener( 'give_gateway_loaded', () => {
		window.doublethedonation.plugin.load_streamlined_input();
	} );
}
