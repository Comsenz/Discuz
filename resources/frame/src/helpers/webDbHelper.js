/**
 * 本地浏览器数据库
 */

	var browserDb = function(params) {
		this.config = {
			DbName: "BrowerDb", //数据库默认名称
			expires: 0 //默认为永久保存
		};

		this.initDb(params);
	};

	/**
	 * 初始化数据库方法
	 * @param  {[object]} params [参数设置]
	 * @return {[type]}        [description]
	 */
	browserDb.prototype.initDb = function(params) {
		for(var key in params) {
			this.config[key] = this.config[key] !== undefined ? params[key] : this.config[key];
		}

		this.config.DbTime = this.config.DbName+"_time";
	};

	/* 非有效期存储时，获取对应键的key */
	browserDb.prototype.getKey = function(key) {
		return this.config.DbName+"_"+key
	},

	/**
	 * 设置localStorage数据
	 * @param {[string]} key     [数据的key]
	 * @param {[string]} val     [数据]
	 * @param {[int]} expires [有效期时间（毫秒）]
	 */
	browserDb.prototype.setLItem = function(key, val, expires) {
		this.clearOverData();
		expires = expires === undefined ? this.config.expires : expires;

		//写入之前先删除已经存在的相同key数据
		this.removeLItem(key);
		if(expires) {
			var DbData = localStorage.getItem(this.config.DbName) != null ? JSON.parse(localStorage.getItem(this.config.DbName)) : {},
				DbTime = localStorage.getItem(this.config.DbTime) != null ? JSON.parse(localStorage.getItem(this.config.DbTime)) : {};

			DbData[key] = val;
			DbTime[key] = this.getSelfTime(expires);

			localStorage.setItem(this.config.DbName, JSON.stringify(DbData));
			localStorage.setItem(this.config.DbTime, JSON.stringify(DbTime));
		} else {
			localStorage.setItem(this.getKey(key), JSON.stringify(val));
		}
	};

	/**
	 * 获取指定localStorage数据，先获取永久存储数据，再获取有效期存储数据
	 * @param  {[string]} key [数据的key]
	 * @param  {[string]} type [存储的类型]
	 * @return {[type]}     [description]
	 */
	browserDb.prototype.getLItem = function(key) {
		this.clearOverData();

		var localDbData = localStorage.getItem(this.config.DbName) != null ? JSON.parse(localStorage.getItem(this.config.DbName)) : {},
			everData = JSON.parse(localStorage.getItem(this.getKey(key)));

		return everData !== null ? everData : (localDbData[key] !== undefined ? localDbData[key] : null);
	};

	/**
	 * 删除指定localStorage数据
	 * @param  {[type]} key [description]
	 * @return {[type]}     [description]
	 */
	browserDb.prototype.removeLItem = function(key) {
		this.clearOverData();

		var localDbData = localStorage.getItem(this.config.DbName) != null ? JSON.parse(localStorage.getItem(this.config.DbName)) : {},
			DbTime = localStorage.getItem(this.config.DbTime) != null ? JSON.parse(localStorage.getItem(this.config.DbTime)) : {},
			newData = {},
			newTime = {};

		if(localDbData[key] !== undefined) {
			for(var localk in localDbData) {
				if(key !== localk) {
					newData[localk] = localDbData[localk];
					newTime[localk] = DbTime[localk];
				}
			}

			localStorage.setItem(this.config.DbName, JSON.stringify(newData));
			localStorage.setItem(this.config.DbTime, JSON.stringify(newTime));
		}

		localStorage.removeItem(this.getKey(key));
	};

	/**
	 * 清空整个有效期数据库
	 * @return {[type]} [description]
	 */
	browserDb.prototype.clearLAll = function() {
		localStorage.removeItem(this.config.DbName);
		localStorage.removeItem(this.config.DbTime);
	};

	/**
	 * 清除过期的数据
	 * @return {[type]} [description]
	 */
	browserDb.prototype.clearOverData = function() {
		var localDbData = localStorage.getItem(this.config.DbName) != null ? JSON.parse(localStorage.getItem(this.config.DbName)) : {},
			DbTime = localStorage.getItem(this.config.DbTime) != null ? JSON.parse(localStorage.getItem(this.config.DbTime)) : {},
			nowTimeStr = this.getSelfTime(0),
			newData = {},
			newTime = {};

		for(var key in DbTime) {
			if(DbTime[key] > nowTimeStr && localDbData[key] !== undefined) {
				newData[key] = localDbData[key];
				newTime[key] = DbTime[key];
			}
		}

		localStorage.setItem(this.config.DbName, JSON.stringify(newData));
		localStorage.setItem(this.config.DbTime, JSON.stringify(newTime));
	};

	/**
	 * 获取指定秒数后的毫秒级时间戳
	 * @param  {[type]} time [description]
	 * @return {[type]}      [description]
	 */
	browserDb.prototype.getSelfTime = function(time) {
		var nowDate = new Date();
		nowDate.setTime(nowDate.getTime() + parseInt(time));

		return nowDate.getTime();
	};

	/**
	 * 设置指定的sessionStorage数据
	 * @param {[type]} key [description]
	 * @param {[type]} val [description]
	 */
	browserDb.prototype.setSItem = function(key, val) {
		sessionStorage.setItem(this.getKey(key), JSON.stringify(val));
	};

	/**
	 * 获取指定的sessionStorage数据
	 * @param  {[type]} key [description]
	 * @return {[type]}     [description]
	 */
	browserDb.prototype.getSItem = function(key) {
		return JSON.parse(sessionStorage.getItem(this.getKey(key)));
	};

	/**
	 * 删除指定的sessionStorage数据
	 * @param  {[type]} key [description]
	 * @return {[type]}     [description]
	 */
	browserDb.prototype.removeSItem = function(key) {
		sessionStorage.removeItem(this.getKey(key));
	};

	export default (new ({
		DbName: "officeDb",
		expires: 0 //默认为永久保存
	}));
