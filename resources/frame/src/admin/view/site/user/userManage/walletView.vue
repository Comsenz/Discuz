<template>
  <div class="wallet-box">
    <div class="details-wallet-header">
      <p class="details-wallet-header__name">{{walletInfo._data.username}}（UID：{{walletInfo._data.id}}）</p>
      <i class="details-wallet-header__i"></i>
      <span @click="$router.push({path:'/admin/user-details', query: query})" >详情</span>
      <span class="details-wallet-header__wallet">钱包</span>
    </div>

    <Card header="钱包可用余额：">
      <p>{{walletInfo._data.available_amount}}元</p>
    </Card>

    <Card header="钱包冻结金额：">
      <p>{{walletInfo._data.freeze_amount}}元</p>
    </Card>

    <Card header="钱包余额操作：">
      <CardRow description="输入金额数">
        <div class="wallet-set-box">
          <el-select v-model="operateType" placeholder="请选择">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
          <el-input v-model="operateAmount" @input="operaAmountInput"></el-input>
        </div>
      </CardRow>
    </Card>

    <Card header="调整原因：">
      <CardRow>
        <el-input
          type="textarea"
          :rows="5"
          placeholder="请输入调整原因"
          v-model="textarea">
        </el-input>
      </CardRow>
    </Card>

    <Card header="钱包状态：">
      <el-radio v-model="walletInfo._data.wallet_status" :label="0">正常</el-radio>
      <el-radio v-model="walletInfo._data.wallet_status" :label="1">冻结提现</el-radio>
    </Card>

    <Card class="footer-btn">
      <el-button type="primary" size="medium" @click="handleSubmit">提交</el-button>
    </Card>

  </div>
</template>

<script>
import '../../../../scss/module/site/userStyle.scss';
import walletCon from '../../../../controllers/site/user/userMange/walletCon'
export default {
    name: "wallet-view",
  ...walletCon
}
</script>
