"use strict";
require("./check-versions")();

process.env.NODE_ENV = "production";

const ora = require("ora");
const rm = require("rimraf");
const path = require("path");
const chalk = require("chalk");
const webpack = require("webpack");
const webpackConfig = require("./webpack.h5.prd.conf");

const spinner = ora("building for production...");
spinner.start();

rm(path.resolve(__dirname, "../../../public/static"), err => {
  webpack(webpackConfig, (err, stats) => {
    spinner.stop();
    if (err) throw err;
    process.stdout.write(
      stats.toString({
        colors: true,
        modules: false,
        children: false, // If you are using ts-loader, setting this to true will make TypeScript errors show up during build.
        chunks: false,
        chunkModules: false
      }) + "\n\n"
    );

    if (stats.hasErrors()) {
      process.exit(1);
    }

    console.log(
      chalk.yellow(
        "  Tip: built files are meant to be served over an HTTP server.\n" +
          "  Opening index.html over file:// won't work.\n"
      )
    );
  });
});
