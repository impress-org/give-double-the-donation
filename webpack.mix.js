const mix = require( 'laravel-mix' );
const wpPot = require( 'wp-pot' );

mix
	.setPublicPath( 'public' )
	.sourceMaps( false )
	.browserSync( 'give.test' )

	// admin assets
	.js( 'src/DoubleTheDonation/resources/js/admin/give-double-the-donation-admin.js', 'public/js/' )
	.sass( 'src/DoubleTheDonation/resources/css/admin/give-double-the-donation-admin.scss', 'public/css' )

	// public assets
	.js( 'src/DoubleTheDonation/resources/js/frontend/give-double-the-donation.js', 'public/js/' )
	.sass( 'src/DoubleTheDonation/resources/css/frontend/give-double-the-donation-frontend.scss', 'public/css' )

	// images
	.copy( 'src/DoubleTheDonation/resources/images/*.{jpg,jpeg,png,gif}', 'public/images' );



mix.webpackConfig( {
	externals: {
		$: 'jQuery',
		jquery: 'jQuery',
	},
} );

if ( mix.inProduction() ) {
	wpPot( {
		package: 'Give - Double-the-Donation',
		domain: 'give-double-the-donation',
		destFile: 'languages/give-double-the-donation.pot',
		relativeTo: './',
		bugReport: 'https://github.com/impress-org/give-double-the-donation/issues/new',
		team: 'GiveWP <info@givewp.com>',
	} );
}
