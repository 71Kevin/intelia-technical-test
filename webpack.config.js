const path = require('path');
const webpack = require('webpack');
const { VueLoaderPlugin } = require('vue-loader');

module.exports = (env, argv) => {
    const isProduction = argv.mode === 'production';

    return {
        mode: isProduction ? 'production' : 'development',
        entry: './vue_app/main.js',
        output: {
            path: path.resolve(__dirname, './public/vue_build/'),
            publicPath: '/vue_build/',
            filename: 'bundle.js'
        },
        module: {
            rules: [
                {
                    test: /\.vue$/,
                    loader: 'vue-loader'
                },
                {
                    test: /\.css$/,
                    use: ['vue-style-loader', 'css-loader']
                }
            ]
        },
        plugins: [
            new VueLoaderPlugin(),
            new webpack.SourceMapDevToolPlugin({}),
            ...(isProduction ? [new webpack.optimize.AggressiveMergingPlugin()] : [])
        ],
        optimization: {
            minimize: isProduction
        },
        devtool: isProduction ? false : 'source-map'
    };
};
