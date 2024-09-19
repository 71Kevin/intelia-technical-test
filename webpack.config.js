var path = require('path');
var webpack = require('webpack');
var { VueLoaderPlugin } = require('vue-loader');

module.exports = {
    mode: 'development',
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
                use: [
                    'vue-style-loader',
                    {
                        loader: 'css-loader',
                        options: {
                            sourceMap: true
                        }
                    }
                ]
            }
        ]
    },
    plugins: [
        new VueLoaderPlugin(),
        new webpack.SourceMapDevToolPlugin({})
    ]
};
