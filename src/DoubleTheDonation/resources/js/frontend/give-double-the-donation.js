if ( window.doublethedonation ) {
	function initializePlugin() {
		document.querySelectorAll( '.dd-company-name-input' ).forEach( ( input ) => {
			if ( ! input.hasAttribute( 'data-doublethedonation-widget-id' ) ) {
				window.doublethedonation.plugin.load_streamlined_input( input );

                input.addEventListener('change', () => {
                    const donationId = input.dataset.donationId;
                    const receiptId = input.dataset.receiptId;
                    // do we have an event from DTD we can use??
                    window.setTimeout(async () => {
                        const companyId = document.querySelector('[name="doublethedonation_company_id"]').value;
                        const companyName = document.querySelector('[name="doublethedonation_company_name"]').value;
                        const enteredText = document.querySelector('[name="doublethedonation_entered_text"]').value;

                        const response = await fetch(DDCONF.GIVE_ENDPOINT + donationId, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                companyId,
                                companyName,
                                enteredText,
                                receiptId
                            }),
                        });

                        // select different company
                        document.querySelectorAll('.wrongcompany a')?.forEach((link) => {
                            link.addEventListener('click', () => {
                                const response = fetch(DDCONF.GIVE_ENDPOINT + donationId + `?receiptId=${receiptId}`, {
                                    method: 'DELETE',
                                });
                            });
                        });
                    }, 500)
                });
			}
		} );
	}

	document.addEventListener( 'give_gateway_loaded', initializePlugin );
    document.addEventListener('DOMContentLoaded', initializePlugin);
}
