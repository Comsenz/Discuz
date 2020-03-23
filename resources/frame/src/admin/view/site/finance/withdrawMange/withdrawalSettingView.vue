<template>
    <div class="withdrawal-setting-box">
      <Card header="提现间隔时间（天）">
        <CardRow description="用户每次提现需间隔的时间，1天为24小时，0或不填则不限制">
          <el-input clearable type="number" v-model="withdrawalInterval"></el-input>
        </CardRow>
      </Card>

      <Card header="提现手续费率（范围为：0-1，例如：0.03）">
        <CardRow description="用户每次提现平台收取的手续费，0或不填则不收取手续费">
          <el-input clearable type="number" v-model="withdrawalFee"></el-input>
        </CardRow>
      </Card>

      <Card header="单次提现最小金额（元）">
        <CardRow description="用户每次提现的最小金额（微信支付要求最低不能少于0.3元）">
          <el-input clearable type="number" v-model="minAmount"></el-input>
        </CardRow>
      </Card>

      <Card header="单次提现最大金额（元）">
        <CardRow description="用户每次提现的最大金额">
          <el-input clearable type="number" v-model="maxAmount"></el-input>
        </CardRow>
      </Card>

      <Card header="每日提现总金额上限（元）">
        <CardRow description="所有用户提现的每日上限总金额">
          <el-input clearable type="number" v-model="amountCap"></el-input>
        </CardRow>
      </Card>

      <Card class="footer-btn">
        <el-button type="primary" size="medium" @click="submitClick">提交</el-button>
      </Card>

    </div>
</template>

<script>
import '../../../../scss/site/module/financeStyle.scss';
import withdrawalSettingCon from '../../../../controllers/site/finance/withdrawMange/withdrawalSettingCon';
export default {
    name: "withdrawal-setting-view",
  ...withdrawalSettingCon
}
</script>
