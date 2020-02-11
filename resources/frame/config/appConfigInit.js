/**
 * 初始化appConfig
 *
 * 根据环境设置正确的基础url，以便使用
 */

import Vue from "vue";
import appConfig from "./appConfig";

appConfig.port = location.port;
appConfig.domainName = location.hostname;

var baseUrl = location.protocol + "//" + location.hostname + ((location.port == "80" || location.port == '') ? "" : ":" + location.port);
appConfig.baseUrl = baseUrl + (appConfig.siteBasePath != "/" ? appConfig.siteBasePath : "");

if (location.href.indexOf('local.') !== -1) {
  	appConfig.apiBaseUrl = appConfig.devApiUrl;
  	appConfig.uploadBaseUrl = appConfig.devApiUrl + appConfig.uploadPath;
} else {
	appConfig.apiBaseUrl = baseUrl + appConfig.apiBasePath;
	appConfig.uploadBaseUrl = baseUrl + appConfig.uploadPath;
}
appConfig.staticBaseUrl = appConfig.baseUrl + appConfig.staticPath;

Vue.prototype.appConfig = appConfig;
