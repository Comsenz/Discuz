一、所需环境
	1、node，自行安装
	2、切换npm为淘宝镜像 npm install -g cnpm --registry=https://registry.npm.taobao.org
	3、本地调试和打包
		第一次本地开发：npm run install 安装node包
		本地开发：npm run dev
		打包上线：npm run build

1.1 weui文档
	https://github.com/Tencent/weui/wiki/getting-started

二、交互框架目录介绍
	--config（打包和详细应用相关配置）
		--appConfig.js（应用配置文件）

	--src（应用代码主要目录）
		--template（模板文件夹）
			--default（默认风格模板文件夹）
				--controllers（控制器根目录）
					--site（默认模块，其他模块开发者自行定义）
					...

				--view（模板根目录）
					--common（公共模板组件目录，如网站公共头，公共导航等）
					--site（其他模板目录，开发者自行定义）
						.....

				--scss（css文件）
					--手机css文件
					--pc端css文件
					--systemCommon.scss（系统基础css文件）
					--var.scss（scss 变量文件）

				--viewConfig（模板配置文件根目录）
					--search.js (node 检索目录配置)
					--tpl.js (框架核心入口文件，可在这里添加每个模块加载前的逻辑)
					--tplConfig.js (路由配置文件，由模块名称+页面模块名称拼接成路由地址，默认加载第一个模块的第一个页面)			
		
		--store（vuex设置根目录)
			--site（vuex模块配置文件）
			--index.js（vuex入口文件）
			--mutationTypes.js（动作统一配置文件）

		--extend（第三方插件改造成module模块加载插件存储目录）
			--viewBase（框架内部使用文件）
				--baseSearch.js（框架自定义检索路径，一般不用修改）
				--baseTpl.js (框架核心文件，一般不用修改)
				--elementuiLnit.js (element-ui 组件统一引入处，暂时放在这里，后面计划拆分)
			
		--helper（自定义helper组件目录）
		    --axiosHelper.js (ajax 请求helper)
		    --commonHelper.js (公共方法助手)
			--webDbHelper.js（web本地存储助手，基于localStorage）

		--main.js（入口文件）

	--static（静态资源目录）
		--css（外部css目录）
		--imgase（图片目录）
		--js (内部js 使用外部加载方式加载，可在路由配置文件中配置)

三、整体开发流程
	1、在config/appConfig.js 中配置全局设置信息，具体参考该文件注释。
	2、首先划分当前项目模块，并在controllers和view中分别建立不同的模块文件夹，请保持controllers和view文件下的模块目录一致，controller文件命名一般是模块名+页面名+Con，模板名称一般是页面名称+View；
	3、从接口获取数据，处理数据等纯js放置到controllers中，如果移动端和手机端指定页面逻辑基本一致，可以共用该controller文件，以减少开发量。
	4、html代码放置到view中，导入对应的controller合并后使用代码逻辑。
	5、css代码放在scss中，每个页面可以引入scss变量文件，保证网站整体风格和css参数的一致。
	6、在viewConfig/tplConfig.js中配置页面路由，和meta信息，具体配置参数请参考该文件的注释。
	7、如果需要使用vuex做数据处理，请在store目录中设置。
	8、axios ajax使用方式请参考 site/siteIndexCon.js，和helper/axiosHelper.js 的注释。
	9、将公共方法放在commonHelper.js中。
	10、如需使用localStorage或者sessionStorage请参看webDBHelper.js，该助手支持设置本次存储的有效期。
	11、如需自定义js助手，请在helper放到文件夹中。
	

四、关键配置文件详解
	1、config/appConfig.js 在改页面中可以设置本地调试域名、端口、远程调试接口、接口key=》value配置、手机页面和pc页面对应链接等，具体参考该文件注释
	2、viewConfig/search.js 在该文件中配置相关目录后，可以在全局非常方便的引用该目录下的文件，减少路径输入，但是要保证各目录下文件名不一致。
	3、viewConfig/tpl.js 在该文件中可以拦截各页面路由，进行登录和非登录判断等等。
	4、viewConfig/tplConfig.js 在该中可以配置各页面路由，和页面需要加载什么资源。

五、文件命名方式
	文件和文件夹请采用小驼峰式命名方式。

六、代码编辑器
	请全部使用sublime编辑器，以保持缩进等空间的一致性。

七、支持各搜索引擎seo搜索
	1、在当前服务器上启动prerender服务。
	2、配置服务器本地访问业务域名。
	3、在apache或者是nginx配置中配置，如果是爬虫访问则重定向到prerender服务。


八、模块名称规划说明
	前台手机端：m_site
	前台pc端：p_site
	后台：admin_site

九、细节规范
	1、后台页面和前台页面引用的公共样式都在一个文件里面，导致前后台冲突。
	1）前后台分别定义各自的common.less，将前后台需要公共设置的样式在这面引入。
	2）前后台应该定义各自的风格变量文件，前后台不可共用该文件。
	3）浏览器默认css清除样式，应该放到前后台各自的common中。
	4）* 等全局影响的样式在前后台各自的common中写。

	2、模版中路径没有使用预定义的变量。
		1）模板中的静态文件引入应该用全局变量中的路径拼接
		:src="appConfig.staticBaseUrl+'/images/logo.png'"
		2）后台文件的引入用search配置

	3、css注意抽象。
		1）不要直接复制代码蓝湖里面的样式。
		2）各种统一参数找设计要。

	4、引入第三方文件改成我们这边合理的名称。
		1）外部引入第三方资源，改成一个合理的名称。

	5、前台公共模板命名规则和加入search，不要在main.js 注册全局组件
		模板文件夹名称+Common+模板名称：defaultCommonHeader

	6、公共方法放到commonHelper中

	7、函数、属性、文件要加注释说明用途

十、问题解决1
	1、get 请求 include 传参封装
		已处理
	2、get 请求 filter、page、filed，需要传对象。
		已处理

	3、model数据解析
		在返回的readdata中

	1）将所有的数据都放到included中，data如果是对象就将其push到included中，如果不是将其合并到included中；
	2）定义一个函数，将当前要获取的data和included传入其中；
	3）判断data是数组还是对象，如果是数组说明这一级是多条数据，如果是对象，说明这一级只有一条数据；
	4）初始化一个返回的data，初始值为数组。
	5）循环遍历data，并在included中找到对应的数据，将数据返回到data中存储
	6）如果是一条数据则返回data的下标0，如果是多条直接返回data；

	4、>=200 && < 300，就是正确，其他是错误返回，按照错误返回处理
		已处理
	5、错误状态码列表由于雷提供，
		Code信息已处理，于雷提供信息后补全到config中的languageConfig中

	接口返回的字段：
	data：正常或者部分正常时返回
	meta: [
	        {
	            "id": "1",
	            "message": "model_not_found"
	        },
	        {
	            "id": "2",
	            "message": "model_not_found"
	        },
	        {
	            "id": "3",
	            "message": "permission_denied"
	        } ],//部分正常
	error: [
		{
			code: "permission_denied" 		status: "401"
		}
	]//全部错误

	6、后台路由跳转：/admin    不重定向到home
		默认加载后台的第一个页面
		
	7、前后台区分路由守卫
		路由守卫在tplConfig中
		
	8、前后台公共js和css的加载
		js和css加载在tplConfig中











