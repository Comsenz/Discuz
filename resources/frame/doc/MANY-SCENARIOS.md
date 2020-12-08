# 多场景开发方案

多场景开发方案是指在在既有的DZQ的样式，布局，交互或功能上提供进一步的修改，用于扩充DZQ的能力，或者是用于自定义样式和交互。

为了可以使DZQ同时可以进行多场景自定义修改，还能支持对于持续更新维护的DZQ做进一步的更新，所以必须遵从以下规范进行开发。
- 对于多场景必须是建立一条长期维护分支，命名规范为（releases-xxx）。
- 如果遇到对于样式重写或交互布局重写等，必须遵从mixins开发方式进行修改。
- 如果多场景下对于原有功能不修改，补充新功能，必须做好判断以及使用多场景下对应自定义的环境变量进行条件编译

# 多场景开发命令

可以根据自己的场景进行命令，并替换下面代码中的pay即可

> package.json
```json
{
    // 以下命令为pay（支付场景下的命令）
    "scripts": {
      "build:pay": "SCENE=pay node --max_old_space_size=4096 build/build.h5.js",
      "dev:pay": "SCENE=pay webpack-dev-server --progress --config build/webpack.h5.dev.conf.js --host 0.0.0.0",
      "build-admin:pay": "SCENE=pay node --max_old_space_size=4096 build/build.admin.js"
    },
}

```

> conditional.loader.config.js

根据条件名称，为插件提供变量。

```javascript
// 条件编译
const conditionalCompiler = {
  loader: 'js-conditional-compile-loader',
  options: {
    pay: process.env.SCENE === 'pay',
    ...
  }
}
```

## mixin开发方式

假如现在有一个A.vue文件，在你的场景下，需要对A.vue进行重写样式，或者大量修改来满足你的场景需要的情况，那么你可以采用这种方式来进行编写。

> home.vue

```javascript

<template>
  <A></A>
</template>

<script>
export default {
    //....
}
</script>
<style lang="scss">
// ....
</style>

```

> A.vue
```javascript

<template>
  <view>A.vue</view>
</template>

<script>
export default {

}
</script>
<style lang="scss">
// ....
</style>

```

那么可以考虑这样编写新的A组件，例如Vue自身的mixin特性，编写一个新的组件，命名规范为（原组件名称-场景名称），然后通过mixin的方式，将原有组件mixin到新的组件上，然后根据场景要求，对组件的样式，dom结构或者组件的生命周期等进行重写，从而达到不修改原有代码的基础上，对于组件进行不同场景的修改，而且不影响正常DZQ的维护更新。

> A-pay.vue

```javascript

<template>
  <view>A-pay.vue</view>
</template>

<script>
export default {

}
</script>
<style lang="scss">
// ....
</style>

```

引用组件时，可以使用自定义的编译条件

> home.vue

```javascript

<template>
    <!-- IFTRUE_pay --> 
    <A-pay></A-pay>
    <!-- FITRUE_pay --> 
    <!-- IFTRUE_default --> 
    <A></A>
    <!-- FITRUE_default --> 
</template>

<script>
export default {
    //....
}
</script>
<style lang="scss">
// ....
</style>

```

# 分支管理
- 场景分支的命名一般为releases-xxx（如：releases-pay）
- 每一个场景原则上是不会同步到releases的，那么每一次DZQ版本发布后，都是需要主动的去同步releases到对应的场景分支当中，

