<template>
    <div class="withdraw-box">
      <WithdrawHeader title="提现"></WithdrawHeader>
      <main class="withdraw-main">
        <div class="withdraw-form my-info-form">
          <van-cell-group>

            <van-field
              v-model="payee"
              label="收款人 "
              placeholder="请输入收款人"
              readonly
            />

            <van-field
              v-model="canWithdraw"
              label="可提现金额 "
              placeholder="可提现金额"
              readonly
            />

            <van-field
              v-model="withdrawalAmount"
              clearable
              label="提现金额 "
              placeholder="请输入提现金额"
              
              @touchstart.native.stop="show = true"
            />

            <!--键盘样式-->
            <van-number-keyboard
              v-model="withdrawalAmount"
              :show="show"
              theme="custom"
              title="圈子金额键盘"
              extra-key="."
              close-button-text="完成"
              @blur="show = false"
              @input="onInput"
              @delete="onDelete"
            />

            <van-field
              v-model="handlingFee"
              label="手续费"
              placeholder="手续费"
              readonly
            />

            <van-field
              v-model="actualCashWithdrawal"
              label="实际提现金额"
              placeholder="实际提现金额"
            />

            <van-field
              v-model="phone"
              label="手机号"
              placeholder="手机号"
              readonly
            />

            <van-field
              clearable
              v-model="sms"
              label="验证码 "
              placeholder="请输入验证码"
            >
              <van-button slot="button" size="small" type="default" @click="sendVerificationCode">发送验证码</van-button>
            </van-field>

          </van-cell-group>
        </div>

        <div class="withdraw-operating">
          <van-button type="primary" @click="withdraw">提交</van-button>
        </div>

      </main>
    </div>
</template>

<script>
import '../../../less/m_site/myInfo/myInfo.less';
import withdrawCon from '../../../controllers/m_site/myInfo/withdrawCon';
import '../../../scss/m_site/myInfo/myInfo.scss';
export default {
    name: "withdraw-view",
  ...withdrawCon
}
</script>
