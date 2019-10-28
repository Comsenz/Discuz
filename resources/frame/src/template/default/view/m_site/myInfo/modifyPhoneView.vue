<template>
    <div class="modify-phone-box">
      <ModifyHeader></ModifyHeader>
      <main class="modify-phone-main">
        <div class="modify-phone-title-box">
          <p class="modify-phone-title-p">修改手机号</p>
        </div>


        <form class="modify-phone-form" action="">
          <van-cell-group>
            <van-field
              v-model="username"
              clearable
              label="验证旧手机"
              placeholder="请输入旧手机号"
              readonly
            />

            <van-field
              v-model="sms"
              clearable
              label="验证码"
              placeholder="请输入验证码"
            >
              <van-button slot="button" color="#336699" size="small" plain type="primary">发送验证码</van-button>
            </van-field>
          </van-cell-group>
        </form>

        <div class="modify-phone-operating">
          <van-button type="primary">下一步</van-button>
        </div>

      </main>
    </div>
</template>

<script>
import '../../../scss/m_site/myInfo/myInfo.scss';
import '../../../less/m_site/myInfo/myInfo.less';
import modifyPhoneCon from '../../../controllers/m_site/myInfo/modifyPhoneCon';
export default {
    name: "modify-phone-view",
  ...modifyPhoneCon
}
</script>
