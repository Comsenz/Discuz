/**
 * 自定义搜索目录汇总
 * @type {[type]}
 */

var path = require("path"),
	publicPath = "../../";

module.exports = [
	path.resolve(__dirname, publicPath+'../config/'),
	path.resolve(__dirname, publicPath+'helpers'),
	path.resolve(__dirname, publicPath+'extend'),
	'node_modules'
];