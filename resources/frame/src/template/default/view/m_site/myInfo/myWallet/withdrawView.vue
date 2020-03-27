<template>
  <div class="withdraw-box">
    <WithdrawHeader title="提现"></WithdrawHeader>
    <main class="withdraw-main">
      <div class="withdraw-form my-info-form">
        <van-cell-group>
          <van-field v-model="payee" label="收款人 " placeholder="请输入收款人" readonly />

          <van-field v-model="canWithdraw" label="可提现金额 " placeholder="可提现金额" readonly />

          <van-field
            v-model="withdrawalAmount"
            type="number"
            clearable
            label="提现金额 "
            placeholder="请输入提现金额"
            :formatter="formatter"
            @touchstart.native.stop="show = true"
          />

          <!--键盘样式-->
          <van-number-keyboard
            v-model="number"
            :show="show"
            theme="custom"
            title="站点金额键盘"
            extra-key="."
            close-button-text="完成"
            type="number"
            @blur="show = false"
            @input="onInput"
            @delete="onDelete"
          />

          <van-field v-model="lingFee" label="手续费" placeholder="手续费" readonly />

          <van-field v-model="actualCashWithdrawal" label="实际提现金额" placeholder="实际提现金额" readonly />

          <van-field v-model="phone" label="手机号" placeholder="手机号" readonly />

          <van-field clearable v-model="sms" label="验证码 " type="number" placeholder="请输入验证码">
            <van-button
              slot="button"
              size="small"
              type="default"
              @click="sendVerificationCode"
              :sendStatus="sendStatus"
            >{{btnContent}}</van-button>
          </van-field>
        </van-cell-group>
      </div>

      <div class="withdraw-operating">
        <van-button type="primary" :loading="loading" loading-text="提交中" @click="withdraw">提交</van-button>
      </div>

      <!--<div class="loadFix" v-if="loading">
        <div class="loadMask"></div>
        <van-loading color="#333333" class="loadIcon" type="spinner" />
      </div>-->
    </main>
  </div>
</template>

<script>
// import '../../../less/m_site/myInfo/myInfo.less';
// import '../../../scss/m_site/myInfo/myInfo.scss';

import "../../../../defaultLess/m_site/common/common.less";
import "../../../../defaultLess/m_site/modules/myInfo.less";
import withdrawCon from "../../../../controllers/m_site/myInfo/myWallet/withdrawCon";
export default {
  name: "withdraw-view",
  ...withdrawCon
};
</script>
