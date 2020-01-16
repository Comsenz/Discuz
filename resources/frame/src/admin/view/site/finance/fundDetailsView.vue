<template>
    <div class="fund-details-box">
      <div class="fund-details__search-box">
        <div class="fund-details__search-condition">
          <span class="fund-details__search-condition__title">用户名：</span>
          <el-input v-model="userName" clearable placeholder="搜索用户名"></el-input>
        </div>

        <div class="fund-details__search-condition">
          <span  class="fund-details__search-condition__title">变动时间：</span>
          <el-date-picker
            v-model="changeTime"
            type="daterange"
            clearable
            value-format="yyyy-MM-dd"
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
            :picker-options="pickerOptions">
          </el-date-picker>
        </div>

        <div class="fund-details__search-condition">
          <span  class="fund-details__search-condition__title">变动描述：</span>
          <el-input v-model="changeDescription" clearable placeholder="搜索变动描述"></el-input>
        </div>

        <div class="fund-details__search-condition">
          <el-button  type="primary" size="medium" @click="searchClick">搜索</el-button>
        </div>

      </div>

      <div class="fund-details-table">
        <el-table
          :data="tableData"
          style="width: 100%">

          <el-table-column
            prop="user._data.username"
            label="用户名"
            width="120">
          </el-table-column>

          <el-table-column
            label="变动时间"
            width="190">
            <template slot-scope="scope">{{ formatDate(scope.row._data.created_at) }}</template>
          </el-table-column>

          <el-table-column
            prop="_data.change_available_amount"
            label="可用金额"
            width="100">
          </el-table-column>

          <el-table-column
            prop="_data.change_freeze_amount"
            label="冻结金额"
            width="100">
          </el-table-column>

          <el-table-column
            prop="_data.change_desc"
            label="变动描述">
          </el-table-column>

        </el-table>

        <Page
          v-if="pageCount > 1"
          @current-change="handleCurrentChange"
          :current-page="currentPaga"
          :page-size="10"
          :total="total">
        </Page>
      </div>

    </div>
</template>

<script>
import '../../../scss/module/site/financeStyle.scss';
import fundDetailsCon from '../../../controllers/site/finance/fundDetailsCon';
export default {
    name: "fund-details-view",
  ...fundDetailsCon
}
</script>
