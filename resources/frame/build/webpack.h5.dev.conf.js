const path = require("path");
const webpack = require("webpack");
const { VueLoaderPlugin } = require("vue-loader");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const CopyWebpackPlugin = require("copy-webpack-plugin");

function resolve(dir) {
  return path.resolve(__dirname, "../" + dir);
}

const VERSION = new Date().getTime();

module.exports = {
  devtool: "#source-map",
  entry: {
    app: resolve("src/h5-main.js"),
    admin: resolve("src/admin-main.js")
  },
  output: {
    filename: "static/js/[name].js?v=" + VERSION,
    chunkFilename: "static/js/[id].[chunkhash].js?v=" + VERSION,
    publicPath: "/"
  },
  optimization: {
    noEmitOnErrors: true,
    namedModules: true
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
              modifyVars: {
                hack: `true; @import "${resolve("src/template/default/defaultLess/m_site/common/theme.less")}";`
              }
            }
          }
        ]
      },
      {
        test: /\.scss$/,
        loader: [
          "vue-style-loader",
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
        NODE_ENV: '"development"'
      }
    }),
    new VueLoaderPlugin(),
    new webpack.HotModuleReplacementPlugin(),
    new HtmlWebpackPlugin({
      filename: "index.html",
      template: "index.html",
      inject: false,
      templateParameters: {
        version: VERSION
      }
    }),
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
  },
  devServer: {
    clientLogLevel: "info",
    historyApiFallback: {
      rewrites: [
        { from: /.*/, to: "/index.html" },
      ]
    },
    contentBase: false,
    proxy: {
      "/api": {
        // target: "https://discuz.chat",
        target: "http://discuz.com:8001",
        changeOrigin: true,
        secure: false,
      }
    }
  }
};
