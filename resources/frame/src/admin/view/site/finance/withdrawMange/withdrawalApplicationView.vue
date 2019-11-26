<template>
    <div class="withdrawal-application-box">
      <div class="withdrawal-application__search-box">
        <div class="withdrawal-application__search-condition">
          <span class="withdrawal-application__search-condition__title">流水号：</span>
          <el-input></el-input>
        </div>

        <div class="withdrawal-application__search-condition">
          <span class="withdrawal-application__search-condition__title">申请时间：</span>
          <el-date-picker
            v-model="value1"
            type="daterange"
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期">
          </el-date-picker>
        </div>

        <div class="withdrawal-application__search-condition">
          <span class="withdrawal-application__search-condition__title">操作用户：</span>
          <el-input></el-input>
        </div>

        <div class="withdrawal-application__search-condition">
          <span class="withdrawal-application__search-condition__title">状态：</span>
          <el-select v-model="value" placeholder="请选择">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </div>

        <div class="withdrawal-application__search-condition">
          <el-button  type="primary" size="medium">搜索</el-button>
        </div>
      </div>

      <div class="withdrawal-application-table">
        <el-table
          :data="tableData"
          style="width: 100%">

          <el-table-column
            prop="serialNumber"
            label="流水号"
            width="130">
          </el-table-column>

          <el-table-column
            prop="operatingUser"
            label="操作用户"
            width="110">
          </el-table-column>

          <el-table-column
            prop="withdrawalAmount"
            label="提现金额（元）"
            width="150">
          </el-table-column>

          <el-table-column
            prop="receivingBankAccount"
            label="收款银行账号"
            width="250">
          </el-table-column>

          <el-table-column
            prop="applicationTime"
            label="申请时间"
            width="120">
          </el-table-column>

          <el-table-column
            prop="status"
            label="状态"
            show-overflow-tooltip
            width="100">
          </el-table-column>

          <el-table-column
            label="操作">
            <template slot-scope="scope">
              <el-button v-if="scope.row.operating" type="text" size="small">审核</el-button>
            </template>
          </el-table-column>

        </el-table>
      </div>

    </div>
</template>

<script>
import '../../../../scss/site/financeStyle.scss';
import withdrawalApplicationCon from '../../../../controllers/site/finance/withdrawMange/withdrawalApplicationCon';
export default {
    name: "withdrawal-application-view",
  ...withdrawalApplicationCon
}
</script>
