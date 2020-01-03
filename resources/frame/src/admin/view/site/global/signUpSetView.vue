<template>
  <div class="sign-up-set-box">

    <Card header="新用户注册：">
      <CardRow description="设置是否允许游客注册成为会员">
        <el-checkbox v-model="checked">允许新用户注册</el-checkbox>
      </CardRow>
    </Card>

    <Card header="注册密码最小长度：">
      <CardRow description="新用户注册时密码最小长度，0或不填为不限制">
        <el-input v-model="pwdLength" type="number"  clearable></el-input>
      </CardRow>
    </Card>

    <Card header="密码字符类型：">
      <CardRow description="新用户注册时密码中必须存在所选字符类型，不选则为无限制">
        <el-checkbox-group v-model="checkList">
          <el-checkbox label=0>数字</el-checkbox>
          <el-checkbox label=1>小写字母</el-checkbox>
          <el-checkbox label=2>符号</el-checkbox>
          <el-checkbox label=3>大写字母</el-checkbox>
        </el-checkbox-group>
      </CardRow>
    </Card>

   <!-- <Card header="微信无感注册和登录">
      <CardRow description="使用微信授权信息代替账号密码，适用于主要在微信内使用的场景。请先设置公众号接口和
扫码登陆配置">
        <el-checkbox label="大写字母">开启微信授权无感注册和登录</el-checkbox>
      </CardRow>
    </Card>-->

    <Card class="footer-btn">
      <el-button type="primary" size="medium" @click="submission">提交</el-button>
    </Card>

  </div>
</template>

<script>
import signUpSetCon from '../../../controllers/site/global/signUpSetCon';
import '../../../scss/site/globalStyle.scss';
export default {
    name: "sign-up-set-view",
  ...signUpSetCon
}
</script>
