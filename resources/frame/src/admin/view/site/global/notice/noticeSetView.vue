<template>
    <div class="notice-list-box">
      <div class="notice-list-table marT15">
        <el-table
          :data="tableData"
          style="width: 100%">

          <el-table-column
            prop=""
            label="编号"
            min-width="100">
            <template slot-scope="scope">
              <span v-text="getIndex(scope.$index)"> </span>
            </template>
          </el-table-column>

          <el-table-column
            prop="_data.title"
            label="通知类型"
            width="200">
          </el-table-column>
          <el-table-column
            label="状态"
            show-overflow-tooltip>
            <!-- <template slot-scope="scope">{{cashStatus(scope.row._data.cash_status,scope.row._data)}}</template> -->
          </el-table-column>

          <el-table-column
            label="操作"
            show-overflow-tooltip>
            <template slot-scope="scope">
             <!-- <el-popover
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
              </el-popover> -->

              <!--<el-button v-if="scope.row._data.cash_status !== '1'" type="text" size="small">审核</el-button>-->
            </template>
          </el-table-column>

        </el-table>
        <Page :total="total" :pageSize="pageLimit" :currentPage="pageNum" @current-change="handleCurrentChange" />
      </div>

    </div>
</template>

<script>
import '../../../../scss/site/module/globalStyle.scss';
import noticeSetCon from '../../../../controllers/site/global/notice/noticeSetCon';
export default {
    name: "withdrawal-application-view",
  ...noticeSetCon
}
</script>
