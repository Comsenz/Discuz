/**
 * 全局配置
 */
let apiConfig = require("./apiConfig.js");

var appConfig = {
 	  port: '8883',                          //本地调试端口
    devHostName: 'local.test.discuz.com',  //本地调试域名
  	devApiUrl: 'http://api.itv.cctv.com/api',   //本地调试接口域名
  	
  	baseUrl: '',                           //网站根目录
    apiBaseUrl: '',                        //网站根目录
  	staticBaseUrl: '',                     //静态文件根目录
    uploadBaseUrl: '',                     //上传文件地址

  	siteBasePath: '/',                     //网站所属目录
    apiBasePath: "/",                      //接口所属目录
    staticPath: "/static",                 //静态文件所属目录
    uploadPath: "/upload",                 //上传文件所属目录
    sourceV: new Date().getTime(),         //资源版本，打包时使用    

  	//接口列表
  	apis: apiConfig
}

module.exports = appConfig
