const mix = require('laravel-mix');

mix.setPublicPath('public')
	.sourceMaps(false)
	.browserSync('give.test')

	// admin assets
	.js('src/DoubleTheDonation/resources/js/admin/give-double-the-donation-admin.js', 'public/js/')
	.sass('src/DoubleTheDonation/resources/css/admin/give-double-the-donation-admin.scss', 'public/css')

	// public assets
	.js('src/DoubleTheDonation/resources/js/frontend/give-double-the-donation.js', 'public/js/')
	.sass('src/DoubleTheDonation/resources/css/frontend/give-double-the-donation-frontend.scss', 'public/css')

	// images
	.copy('src/DoubleTheDonation/resources/images/*.{jpg,jpeg,png,gif}', 'public/images');

mix.webpackConfig({
	externals: {
		$: 'jQuery',
		jquery: 'jQuery',
	},
});
