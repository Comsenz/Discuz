<template>
  <div class="order-record-box">
    <Card header="搜索"></Card>

    <div class="order-record__search-box">
      <div class="order-record__search-condition">
        <span class="order-record__search-condition__title">订单号：</span>
        <el-input></el-input>
      </div>

      <div class="order-record__search-condition">
        <span class="order-record__search-condition__title">订单时间：</span>
        <el-date-picker
          v-model="value1"
          type="daterange"
          range-separator="至"
          start-placeholder="开始日期"
          end-placeholder="结束日期">
        </el-date-picker>
      </div>

      <div class="order-record__search-condition">
        <span class="order-record__search-condition__title">操作用户：</span>
        <el-input></el-input>
      </div>

      <div class="order-record__search-condition">
        <span class="order-record__search-condition__title">商品：</span>
        <el-input></el-input>
      </div>

      <div class="order-record__search-condition">
        <el-button  type="primary" size="medium">搜索</el-button>
      </div>
    </div>

    <div class="order-record-table">
      <el-table
        :data="tableData"
        style="width: 100%">

        <el-table-column
          prop="orderNumber"
          label="订单号">
        </el-table-column>

        <el-table-column
          prop="operatingUser"
          label="操作用户">
        </el-table-column>

        <el-table-column
          prop="productName"
          label="商品名称">
        </el-table-column>

        <el-table-column
          prop="amount"
          label="金额">
        </el-table-column>

        <el-table-column
          prop="orderTime"
          label="订单时间">
        </el-table-column>

        <el-table-column
          prop="status"
          label="状态">
        </el-table-column>

      </el-table>
    </div>

  </div>
</template>

<script>
import '../../../scss/site/financeStyle.scss';
import orderRecordCon from '../../../controllers/site/finance/orderRecordCon';
export default {
    name: "order-details",
  ...orderRecordCon
}
</script>
