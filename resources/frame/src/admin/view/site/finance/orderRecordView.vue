<template>
  <div class="order-record-box">
    <div class="order-record__search-box">
      <div class="order-record__search-condition">
        <span class="order-record__search-condition__title">订单号：</span>
        <el-input clearable v-model="orderNumber" placeholder="搜索订单号"></el-input>
      </div>

      <div class="order-record__search-condition">
        <span class="order-record__search-condition__title">订单时间：</span>
        <el-date-picker
          v-model="orderTime"
          clearable
          type="daterange"
          value-format="yyyy-MM-dd"
          range-separator="至"
          start-placeholder="开始日期"
          end-placeholder="结束日期"
          :picker-options="pickerOptions">
        </el-date-picker>
      </div>

      <div class="order-record__search-condition">
        <span class="order-record__search-condition__title">发起方：</span>
        <el-input clearable v-model="operationUser" placeholder="搜索发起方"></el-input>
      </div>

      <div class="order-record__search-condition">
        <span class="order-record__search-condition__title">商品：</span>
        <el-input clearable v-model="commodity" placeholder="搜索商品"></el-input>
      </div>

      <div class="order-record__search-condition">
        <el-button  type="primary" size="medium" @click="searchClick">搜索</el-button>
      </div>
    </div>

    <div class="order-record-table">
      <el-table
        :data="tableData"
        style="width: 100%">

        <el-table-column
          prop="_data.order_sn"
          label="订单号"
          min-width="110">
        </el-table-column>

        <el-table-column
          prop="user._data.username"
          label="发起方">
        </el-table-column>

        <el-table-column
          prop="payee._data.username"
          label="收入方">
        </el-table-column>

        <el-table-column
          prop="thread.firstPost._data.content"
          show-overflow-tooltip
          label="商品名称"
          min-width="150">
          <template slot-scope="scope">
            <span :class="scope.row.thread?'cursor-pointer':''" v-if="scope.row.thread && scope.row.thread._data.type === 1" @click="viewClick(scope.row.thread?scope.row.thread._data.id:'')">
              {{scope.row.thread._data.title}}
            </span>
            <span :class="scope.row.thread?'cursor-pointer':''" v-else @click="viewClick(scope.row.thread?scope.row.thread._data.id:'')">
              {{scope.row.thread?scope.row.thread.firstPost._data.content:'注册付费'}}
            </span>
          </template>
        </el-table-column>

        <el-table-column
          prop="_data.amount"
          label="金额"
          width="100">
        </el-table-column>

        <el-table-column
          prop="_data.created_at"
          label="订单时间">
          <template slot-scope="scope">{{ formatDate(scope.row._data.created_at) }}</template>
        </el-table-column>

        <el-table-column
          label="状态"
          width="100">
          <template slot-scope="scope">{{ cashStatus(scope.row._data.status) }}</template>
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
import '../../../scss/site/module/financeStyle.scss';
import orderRecordCon from '../../../controllers/site/finance/orderRecordCon';
export default {
    name: "order-details",
  ...orderRecordCon
}
</script>
