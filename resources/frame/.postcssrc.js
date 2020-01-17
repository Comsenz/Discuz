// https://github.com/michael-ciniawsky/postcss-load-config

module.exports = {
  "plugins": {
    'autoprefixer': {
      overrideBrowserslist: [
        'Android 4.1',
        'iOS 7.1',
        'Chrome > 31',
        'ff > 31',
        'ie >= 8'
      ]
    },
    'postcss-pxtorem': {
      rootValue: 37.5, //需要跟rem.js文件基准大小统一
      propList: ['*'],
      selectorBlackList:['.el-','.ad-']
    }
  }
}
