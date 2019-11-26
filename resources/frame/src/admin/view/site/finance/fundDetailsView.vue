<template>
    <div class="fund-details-box">
      <Card header="搜索"></Card>

      <div class="fund-details__search-box">
        <div class="fund-details__search-condition">
          <span class="fund-details__search-condition__title">用户名：</span>
          <el-input></el-input>
        </div>

        <div class="fund-details__search-condition">
          <span  class="fund-details__search-condition__title">变动时间：</span>
          <el-date-picker
            v-model="value1"
            type="daterange"
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期">
          </el-date-picker>
        </div>

        <div class="fund-details__search-condition">
          <span  class="fund-details__search-condition__title">变动描述：</span>
          <el-input></el-input>
        </div>

        <div class="fund-details__search-condition">
          <el-button  type="primary" size="medium" >搜索</el-button>
        </div>

      </div>

      <div class="fund-details-table">
        <el-table
          :data="tableData"
          style="width: 100%">

          <el-table-column
            prop="user"
            label="用户名"
            width="120">
          </el-table-column>

          <el-table-column
            prop="changeTime"
            label="变动时间"
            width="150">
          </el-table-column>

          <el-table-column
            prop="amountAvailable"
            label="可用金额"
            width="100">
          </el-table-column>

          <el-table-column
            prop="frozenAmount"
            label="冻结金额"
            width="100">
          </el-table-column>

          <el-table-column
            prop="changeDescription"
            label="变动描述">
          </el-table-column>

        </el-table>
      </div>

    </div>
</template>

<script>
import '../../../scss/site/financeStyle.scss';
import fundDetailsCon from '../../../controllers/site/finance/fundDetailsCon';
export default {
    name: "fund-details-view",
  ...fundDetailsCon
}
</script>
