<template>
    <div class="modify-data-box">
      <ModifyHeader></ModifyHeader>
      <main class="modify-data-main">
        <div class="modify-data-avatar m-site-cell-access">
          <div class="modify-data-avatar-title m-site-cell-access-bd">
            <p>头像</p>
          </div>
          <div class="modify-data-avatar-img m-site-cell-access-ft">
            <!-- <img  src="../../../../../../static/images/mytx.png" alt="我的头像"> -->
            <img  src="" alt="我的头像">
            <i>
              <span class="icon iconfont icon-right m-site-cell-access-ft-icon" style="color: #e5e5e5;"></span>
            </i>
          </div>
        </div>

        <div class="modify-data-phone m-site-cell-access">
          <div class="modify-data-phone-title m-site-cell-access-bd">
            <p>手机号</p>
          </div>

          <div class="modify-data-phone-num m-site-cell-access-ft">
            <div>18645****23</div>
            <i>
              <span class="icon iconfont icon-right m-site-cell-access-ft-icon" style="color: #e5e5e5;"></span>
            </i>

          </div>
        </div>

        <div class="modify-data-pwd m-site-cell-access">
          <div class="modify-data-pwd-title m-site-cell-access-bd">
            <p>密码</p>
          </div>
          <div class="modify-data-pwd-num m-site-cell-access-ft">
            <div>*******</div>
            <i>
              <span class="icon iconfont icon-right m-site-cell-access-ft-icon" style="color: #e5e5e5;"></span>
            </i>
          </div>
        </div>

        <div class="modify-data-wx m-site-cell-access">
          <div class="modify-data-wx-title m-site-cell-access-bd">
            <p>微信</p>
          </div>
          <div class="modify-data-wx-string m-site-cell-access-ft">
            <div>小艾</div>
            <i>
              <span class="icon iconfont icon-right m-site-cell-access-ft-icon" style="color: #e5e5e5;"></span>
            </i>
          </div>
        </div>

      </main>

    </div>
</template>

<script>
import '../../../scss/m_site/myInfo/myInfo.scss';
import modifyDataCon from '../../../controllers/m_site/myInfo/modifyDataCon';
export default {
    name: "modify-data-view",
  ...modifyDataCon
}
</script>
