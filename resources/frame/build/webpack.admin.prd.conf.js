const path = require("path");
const webpack = require("webpack");
const { VueLoaderPlugin } = require("vue-loader");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const CopyWebpackPlugin = require("copy-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const OptimizeCSSPlugin = require("optimize-css-assets-webpack-plugin");
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

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
  mode: "production",
  devtool: false,
  entry: {
    admin: resolve("src/admin-main.js")
  },
  externals: {
    'vue': 'Vue',
    'vuex': 'Vuex',
    'vant': 'vant'
  },
  output: {
    path: resolvePublic(),
    filename: "static-admin/js/[name].js?v=" + VERSION,
    chunkFilename: "static-admin/js/[id].[chunkhash].js",
    publicPath: "/"
  },
  optimization: {
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
    modules: [
      resolve("src/helpers"),
      resolve("node_modules"),
      resolve("src/admin/scss")
    ],
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
        include: [resolve("src"), resolve("node_modules/element-ui")]
      },
      {
        test: /\.js$/,
        loader: "babel-loader",
        include: resolve("src"),
        exclude: [/node_modules/]
      },
      {
        test: /\.css$/,
        loader: [MiniCssExtractPlugin.loader, "css-loader", "postcss-loader"]
      },
      {
        test: /\.less$/,
        loader: [
          MiniCssExtractPlugin.loader,
          "css-loader",
          "postcss-loader",
          {
            loader: "less-loader",
            options: {
              modifyVars: {
                hack: `true; @import "${resolve(
                  "src/template/default/defaultLess/m_site/common/theme.less"
                )}";`
              }
            }
          }
        ]
      },
      {
        test: /\.scss$/,
        loader: [
          MiniCssExtractPlugin.loader,
          "css-loader",
          "postcss-loader",
          "sass-loader"
        ]
      },
      {
        test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
        loader: "url-loader",
        options: {
          limit: 10000,
          name: "static-admin/fonts/[name].[hash:7].[ext]"
        }
      },
      {
        test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
        loader: "url-loader",
        options: {
          limit: 10000,
          name: "static-admin/img/[name].[hash:7].[ext]"
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
      filename: "static-admin/css/[name].css?v=" + VERSION,
      chunkFilename: "static-admin/css/[id].[contenthash].css"
    }),
    new HtmlWebpackPlugin({
      filename: "admin.html",
      template: "admin.html",
      inject: false,
      templateParameters: {
        version: VERSION
      },
      minify: {
        removeComments: true,
        collapseWhitespace: true,
        removeAttributeQuotes: true
      }
    }),
    new webpack.HashedModuleIdsPlugin(),
    new CopyWebpackPlugin([
      {
        from: resolve("static"),
        to: "static-admin",
        ignore: [".*"]
      }
    ]),
    // new BundleAnalyzerPlugin()
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
