const path = require('path');
const config = {
    entry: {
        app: path.resolve(process.cwd(), 'resources/js/app.js')
    },
    output:{
      path: path.resolve(process.cwd(), 'public/assets/js'),
      library: 'discuz.core',
      libraryTarget: 'assign',
      devtoolNamespace: require(path.resolve(process.cwd(), 'package.json')).name
    },
    module:{
        rules:[
            {
                test: /\.(js)$/,
                exclude: /node_modules/,
                use: {
                  loader: 'babel-loader',
                  options: {
                    presets: [
                      ['@babel/preset-env', {modules: false, loose: true}],
                    ],
                    plugins: [
                      ['@vue/babel-plugin-jsx'],
                    ],
                  }
                },
              },
        ]
    },
    resolve: {
      alias: {
        vue$: 'vue/dist/vue.esm-bundler.js',
      },
      extensions: ['.js', '.jsx', '.vue', '.md'],
    },
    devtool: 'source-map',
};

module.exports = config;
