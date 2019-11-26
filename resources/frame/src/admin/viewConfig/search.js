/**
 * 默认模板下搜索目录
 * @type {[type]}
 */
var path = require("path"),
	publicPath = "../";

module.exports = [
	// control搜索配置
	path.resolve(__dirname, publicPath+'controllers/cloud'),
	path.resolve(__dirname, publicPath+'controllers/content'),
	path.resolve(__dirname, publicPath+'controllers/finance'),
	path.resolve(__dirname, publicPath+'controllers/global'),
	path.resolve(__dirname, publicPath+'controllers/login'),
	path.resolve(__dirname, publicPath+'controllers/user'),

	//样式
	path.resolve(__dirname, publicPath+'scss/'),
  path.resolve(__dirname, publicPath+'../helpers'),

	...require("../../extend/viewBase/baseSearch"),
];
