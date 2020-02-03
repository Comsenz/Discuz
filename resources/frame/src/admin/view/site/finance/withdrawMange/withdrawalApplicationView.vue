<template>
    <div class="withdrawal-application-box">
      <div class="withdrawal-application__search-box">
        <div class="withdrawal-application__search-condition">
          <span class="withdrawal-application__search-condition__title">流水号：</span>
          <el-input v-model="cashSn" clearable placeholder="搜索流水号"></el-input>
        </div>

        <div class="withdrawal-application__search-condition">
          <span class="withdrawal-application__search-condition__title">申请时间：</span>
          <el-date-picker
            v-model="applicationTime"
            clearable
            type="daterange"
            value-format="yyyy-MM-dd"
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
            :picker-options="pickerOptions">
          </el-date-picker>
        </div>

        <div class="withdrawal-application__search-condition">
          <span class="withdrawal-application__search-condition__title">操作用户：</span>
          <el-input v-model="operationUser" clearable placeholder="搜索操作用户"></el-input>
        </div>

        <div class="withdrawal-application__search-condition">
          <span class="withdrawal-application__search-condition__title">状态：</span>
          <el-select v-model="statusSelect" placeholder="请选择">
            <el-option
              v-for="item in statusOptions"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </div>

        <div class="withdrawal-application__search-condition">
          <el-button  type="primary" size="medium" @click="searchClick">搜索</el-button>
        </div>
      </div>

      <div class="withdrawal-application-table">
        <el-table
          :data="tableData"
          style="width: 100%">

          <el-table-column
            prop="_data.cash_sn"
            label="流水号"
            min-width="160">
          </el-table-column>

          <el-table-column
            prop="user._data.username"
            label="操作用户"
            width="110">
          </el-table-column>

          <el-table-column
            prop="_data.cash_apply_amount"
            label="提现金额（元）"
            width="150">
          </el-table-column>

          <el-table-column
            prop="_data.created_at"
            label="申请时间"
            min-width="160">
            <template slot-scope="scope">{{formatDate(scope.row._data.created_at)}}</template>
          </el-table-column>

          <el-table-column
            label="状态"
            show-overflow-tooltip>
            <template slot-scope="scope">{{cashStatus(scope.row._data.cash_status,scope.row._data)}}</template>
          </el-table-column>

          <el-table-column
            label="操作"
            show-overflow-tooltip>
            <template slot-scope="scope">
              <el-popover
                width="100"
                placement="top"
                v-if="scope.row._data.cash_status === 1"
                :ref="`popover-${scope.$index}`">
                <p>确定通过该提现吗？</p>
                <div style="text-align: right; margin: 10PX 0 0 0 ">
                  <el-button type="danger" size="mini" @click="noReviewClick(scope.row._data.id);scope._self.$refs[`popover-${scope.$index}`].doClose()">
                    不通过
                  </el-button>
                  <el-button type="primary" size="mini" @click="reviewClick(scope.row._data.id);scope._self.$refs[`popover-${scope.$index}`].doClose()" >通过</el-button>
                </div>
                <el-button v-if="scope.row._data.cash_status === 1" type="text" size="small" slot="reference">审核</el-button>
              </el-popover>

              <!--<el-button v-if="scope.row._data.cash_status !== '1'" type="text" size="small">审核</el-button>-->
            </template>
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
import '../../../../scss/site/module/financeStyle.scss';
import withdrawalApplicationCon from '../../../../controllers/site/finance/withdrawMange/withdrawalApplicationCon';
export default {
    name: "withdrawal-application-view",
  ...withdrawalApplicationCon
}
</script>
