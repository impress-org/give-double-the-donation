const CopyPlugin = require('copy-webpack-plugin');
const defaultConfig = require('@wordpress/scripts/config/webpack.config.js');

module.exports = {
    ...defaultConfig,
    entry: {
        'backend': './src/DoubleTheDonation/resources/js/admin/give-double-the-donation-admin.js',
        'frontend': './src/DoubleTheDonation/resources/js/frontend/give-double-the-donation.js',
        'template': './src/DoubleTheDonation/FormExtension/Template/index.tsx',
        'block': './src/DoubleTheDonation/FormExtension/Block/index.tsx',
    },
    plugins: [
        ...defaultConfig.plugins,
        new CopyPlugin({
            patterns: [
                {
                    from: './src/DoubleTheDonation/resources/images/*.{jpg,jpeg,png,gif}',
                    to() {
                        return 'images/[name][ext]';
                    },
                },
            ],
        }),
    ],
    externals: {
        $: 'jQuery',
        jquery: 'jQuery',
    },
};
