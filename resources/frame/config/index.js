'use strict'
// Template version: 1.3.1
// see http://vuejs-templates.github.io/webpack for documentation.

const path = require('path');
const appConfig = require("./appConfig.js");

module.exports = {
  dev: {

    // Paths
    assetsSubDirectory: 'static',
    assetsPublicPath: appConfig.siteBasePath,
    proxyTable: {
        '/api': {
            target: appConfig.devApiUrl,
            changeOrigin: true,
            secure: false,
            pathRewrite: {
               // '^/api': '/api'
            },
            bypass: function(req, res, proxyOptions) {
              if (req.headers.accept.indexOf("html") !== -1) {
                return "/index.html";
              }
            }
        },
    },

    // Various Dev Server settings
    host: appConfig.devHostName,
    // can be overwritten by process.env.PORT, if port is in use, a free one will be determined
    port: appConfig.port,
    autoOpenBrowser: true,
    errorOverlay: true,
    notifyOnErrors: true,
    poll: false, // https://webpack.js.org/configuration/dev-server/#devserver-watchoptions-
    devtool: 'cheap-module-eval-source-map',
    cacheBusting: true,
    cssSourceMap: true,
  },

  build: {
    // Template for index.html
    // index: path.resolve(__dirname, '../dist/index.html'),
    index: path.resolve(__dirname, '../../../public/index.html'), //打包路径修改成：public

    // Paths
    // assetsRoot: path.resolve(__dirname, '../dist'),
    assetsRoot: path.resolve(__dirname, '../../../public'), //打包路径修改成：public
    assetsSubDirectory: 'static',
    assetsPublicPath: appConfig.siteBasePath,

    productionSourceMap: false,
    devtool: '#source-map',
    productionGzip: false,
    productionGzipExtensions: ['js', 'css'],
    bundleAnalyzerReport: process.env.npm_config_report
  }
}
