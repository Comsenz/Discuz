const path = require("path");
const webpack = require("webpack");
const { VueLoaderPlugin } = require("vue-loader");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const CopyWebpackPlugin = require("copy-webpack-plugin");
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const TerserPlugin = require('terser-webpack-plugin')
const OptimizeCSSPlugin = require('optimize-css-assets-webpack-plugin')

function resolve(dir) {
  return path.resolve(__dirname, "../" + dir);
}

function resolvePublic(dir) {
  if (dir == undefined) {
    dir = "";
  }
  return resolve("../../public/" + dir);
}

const VERSION = new Date().getTime();

module.exports = {
  devtool: false,
  entry: {
    app: resolve("src/main.js")
  },
  output: {
    path: resolvePublic(),
    filename: "static/js/[name].[chunkhash].js?v=" + VERSION,
    chunkFilename: "static/js/[id].[chunkhash].js?v=" + VERSION
  },
  optimization: {
    concatenateModules: true,
    runtimeChunk: {
      name: "manifest"
    },
    minimizer: [
      new TerserPlugin({
        cache: true,
        parallel: true,
        sourceMap: true,
        terserOptions: {
          ecma: undefined,
          warnings: false,
          parse: {},
          compress: {
            drop_console: true,
            drop_debugger: false,
            pure_funcs: ["console.log"] // 移除console
          }
        }
      }),
      new OptimizeCSSPlugin()
    ],
    splitChunks: {
      cacheGroups: {}
    }
  },
  performance: {
    hints: false
  },
  resolve: {
    modules: [resolve("src/helpers"), resolve("node_modules"), resolve("src/admin/scss")],
    extensions: [".js", ".vue", ".json", ".css", ".less", ".scss"],
    alias: {
      vue: "vue/dist/vue.esm.js",
      "@": resolve("src")
    }
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: "vue-loader",
        include: resolve("src"),
        exclude: [/node_modules/, resolve("src/admin/")]
      },
      {
        test: /\.js$/,
        loader: "babel-loader",
        include: resolve("src"),
        exclude: [/node_modules/, resolve("src/admin/")]
      },
      {
        test: /\.css$/,
        loader: ["vue-style-loader", "css-loader", "postcss-loader"]
      },
      {
        test: /\.less$/,
        loader: [
          "vue-style-loader",
          "css-loader",
          "postcss-loader",
          {
            loader: "less-loader",
            options: {
              hack: `true; @import "${resolve(
                "src/template/default/defaultLess/m_site/common/theme.less"
              )}";`
            }
          }
        ]
      },
      {
        test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
        loader: "url-loader",
        options: {
          limit: 10000,
          name: "static/fonts/[name].[hash:7].[ext]"
        }
      },
      {
        test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
        loader: "url-loader",
        options: {
          limit: 10000,
          name: "static/img/[name].[hash:7].[ext]"
        }
      }
    ]
  },
  plugins: [
    new webpack.DefinePlugin({
      "process.env": {
        NODE_ENV: '"production"'
      }
    }),
    new VueLoaderPlugin(),
    new MiniCssExtractPlugin({
      filename: 'static/css/[name].[contenthash].css',
      allChunks: true,
    }),
    new HtmlWebpackPlugin({
      filename: "index.html",
      template: "index.html",
      inject: true
    }),
    new webpack.HashedModuleIdsPlugin(),
    new CopyWebpackPlugin([
      {
        from: resolve("static"),
        to: "static",
        ignore: [".*"]
      }
    ])
  ],
  node: {
    setImmediate: false,
    dgram: "empty",
    fs: "empty",
    net: "empty",
    tls: "empty",
    child_process: "empty"
  }
};
