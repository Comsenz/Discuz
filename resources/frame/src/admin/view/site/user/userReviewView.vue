<template>
    <div class="user-review-box">
      <div class="user-review-table">
        <el-table
          ref="multipleTable"
          :data="tableData"
          tooltip-effect="dark"
          style="width: 100%"
          @selection-change="handleSelectionChange">
          <el-table-column
            type="selection"
            width="55">
          </el-table-column>
          <el-table-column
            label="编号"
            prop="_data.id"
            width="100">
          </el-table-column>
          <el-table-column
            prop="_data.username"
            label="用户名"
            width="200">
          </el-table-column>
          <el-table-column
            prop="_data.registerReason"
            label="注册原因"
            show-overflow-tooltip>
          </el-table-column>
          <el-table-column
            label="注册时间">
            <template slot-scope="scope">{{ formatDate(scope.row._data.createdAt) }}</template>
          </el-table-column>
          <el-table-column
            label=""
            width="230">
            <template slot-scope="scope">
              <el-button type="text" @click="singleOperation('pass',scope.row._data.id)" >通过</el-button>
              <el-button type="text" @click="singleOperation('no',scope.row._data.id)" >否决</el-button>

              <el-popover
                width="100"
                placement="top"
                :ref="`popover-${scope.$index}`">
                <p>确定删除该项吗？</p>
                <div style="text-align: right; margin: 10PX 0 0 0 ">
                  <el-button type="text" size="mini" @click="scope._self.$refs[`popover-${scope.$index}`].doClose()">取消</el-button>

                  <el-button type="danger" size="mini" @click="singleOperation('del',scope.row._data.id);scope._self.$refs[`popover-${scope.$index}`].doClose()" >确定</el-button>
                </div>
                <el-button type="text" slot="reference">删除</el-button>
              </el-popover>
            </template>
          </el-table-column>
        </el-table>

        <tableNoList v-show="tableData.length < 1"></tableNoList>

        <Page
          v-if="pageCount > 1"
          @current-change="handleCurrentChange"
          :current-page="currentPaga"
          :page-size="10"
          :total="total">
        </Page>

      </div>

      <Card class="footer-btn">
        <el-button type="primary" :loading="btnLoading" @click="allOperation('pass')">通过</el-button>
        <el-button type="primary" plain @click="allOperation('no')">否决</el-button>
        <el-popover
          placement="top"
          width="160"
          v-model="visible">
          <p>确定删除选中的用户吗？</p>
          <div style="text-align: right; margin: 10PX 0 0 0">
            <el-button size="mini" type="text" @click="visible = false">取消</el-button>
            <el-button type="danger" size="mini" @click="allOperation('del')">确认</el-button>
          </div>
          <!-- <el-button size="medium" slot="reference">删除</el-button> -->
        </el-popover>

      </Card>
    </div>
</template>

<script>
import userReviewCon from '../../../controllers/site/user/userReviewCon';
import '../../../scss/site/module/userStyle.scss';

export default {
    name: "userReview",
  ...userReviewCon
}
</script>
