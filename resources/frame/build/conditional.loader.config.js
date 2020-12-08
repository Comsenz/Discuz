// 条件编译
const conditionalCompiler = {
  loader: 'js-conditional-compile-loader',
  options: {
    default: process.env.SCENE === 'default',
    pay: process.env.SCENE === 'pay'
  }
}

module.exports = () => {
  return {
    enforce: 'pre',
    test: /\.(js|vue|css|scss)$/,
    loader: conditionalCompiler,
    exclude: /(node_modules)/
  };
}
