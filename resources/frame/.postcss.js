module.exports = {
  "plugins": {
    "postcss-import": {},
    "postcss-url": {},
    // to edit target browsers: use "browserslist" field in package.json
    "autoprefixer": {},
    "postcss-pxtorem": { // 此处为添加部分
      rootValue: 32, // 对应16px 适配移动端750px宽度
      unitPrecision: 5,
      propList: ['*'],
      selectorBlackList: [],
      replace: true,
      mediaQuery: false,
      minPixelValue: 0
    }
    // 'autoprefixer': {overrideBrowserslist: [
    //   'Android 4.1',
    //   'iOS 7.1',
    //   'Chrome > 31',
    //   'ff > 31',
    //   'ie >= 8'
    //   ]
    // },
    // 'postcss-pxtorem': {
    //   rootValue: 37.5,
    //   propList: ['*']
    // }

  }
}
