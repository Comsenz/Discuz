/**
 * 默认模板下搜索目录
 * @type {[type]}
 */

var path = require("path"),
	publicPath = "../";

module.exports = [
	// control搜索配置
	path.resolve(__dirname, publicPath+'controllers/site'),
	path.resolve(__dirname, publicPath+'controllers/m_site'),

	//样式
	path.resolve(__dirname, publicPath+'scss/'),
	path.resolve(__dirname, publicPath+'scss/mobile'),
	path.resolve(__dirname, publicPath+'scss/pc'),

	...require("../../../extend/viewBase/baseSearch"),
];
