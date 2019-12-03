<template>
    <div class="modify-data-box">
      <ModifyHeader title="修改资料"></ModifyHeader>
      <main class="modify-data-main content">
        <div class="modify-data-avatar m-site-cell-access">
          <div class="modify-data-avatar-title m-site-cell-access-bd">
            <p>头像</p>
          </div>
          <div class="modify-data-avatar-img">
            <input type="file" accept="image/*" @change="handleFile" class="hiddenInput"/>
             <img  :src="headPortrait" alt="我的头像">
            <i>
              <span class="icon iconfont icon-right m-site-cell-access-ft-icon" style="color: #e5e5e5;"></span>
            </i>
          </div>
        </div>
        <van-cell title="手机号" @click="myModify('modify-phone')" is-link :value="modifyPhone" />
        <van-cell title="密码" @click="myModify('change-pwd')" is-link value="********" />
        <van-cell title="微信" is-link value="小艾" />
      </main>

    </div>
</template>
<style type="text/css" scoped>
 .modify-data-avatar-img{position: relative;display: inline-block;}
  .hiddenInput {position: absolute;left: 0;top: 0;opacity: 0;}
  /* .searchCon { box-shadow: 0px 1px rgba(0,0,0,0.02); margin-bottom: 1px;} */
</style>
<script>
import '../../../scss/m_site/myInfo/myInfo.scss';
import modifyDataCon from '../../../controllers/m_site/myInfo/modifyDataCon';
export default {
    name: "modify-data-view",
  ...modifyDataCon
}
</script>
