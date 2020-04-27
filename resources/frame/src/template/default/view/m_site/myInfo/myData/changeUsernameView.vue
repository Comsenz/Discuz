<template>
  <div>
    <ChangeUsernameHeader title="修改用户名"></ChangeUsernameHeader>
    <main>
      <div class="change-username-main my-info-form">
        <van-cell-group>
          <van-field label="新用户名" v-model="newusername" type="text" placeholder="请输入您希望设置的新用户名" />
        </van-cell-group>
      </div>

      <div class="change-username-operating">
        <van-button :loading="btnLoading" loading-text="提交中" type="primary" @click="subm">提交</van-button>
      </div>

    </main>
  </div>
</template>

<script>
import "../../../../defaultLess/m_site/modules/myInfo.less";
import changeUsernameCon from "../../../../controllers/m_site/myInfo/myData/changeUsernameCon";
export default {
  name: "change-username-view",
  ...changeUsernameCon
};
</script>
