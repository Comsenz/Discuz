<template>
    <div class="recycle-bin-box">
      <Card header="搜索"></Card>
      <div class="recycle-bin-header">

        <div class="recycle-bin-header__lf">
          <div>
            <span class="cont-review-header__lf-title">作者：</span>
            <el-input size="medium"></el-input>
          </div>
          <div>
            <span class="cont-review-header__lf-title">搜索范围：</span>
            <!--<el-input size="medium"></el-input>-->
            <el-select v-model="value" size="medium" placeholder="请选择">
              <el-option
                v-for="item in options"
                :key="item.value"
                :label="item.label"
                :value="item.value">
              </el-option>
            </el-select>
          </div>
        </div>

        <div class="recycle-bin-header__rt">
          <div class="recycle-bin-header__rt-top">
            <span class="cont-review-header__lf-title">内容包含：</span>
            <el-input size="medium"></el-input>
            <span class="cont-review-header__lf-title">操作人：</span>
            <el-input size="medium"></el-input>
          </div>

          <div class="recycle-bin-header__rt-bottom">
            <span class="recycle-bin-header__rt-bottom__time-title">发布时间范围：</span>

            <el-date-picker
              v-model="value2"
              type="daterange"
              align="right"
              unlink-panels
              size="medium"
              range-separator="至"
              start-placeholder="开始日期"
              end-placeholder="结束日期"
              :picker-options="pickerOptions">
            </el-date-picker>

            <el-button size="small" type="primary">提交</el-button>

          </div>
        </div>

      </div>

      <div class="recycle-bin-table">
        <el-table
          :data="tableData"
          style="width: 100%">
          <el-table-column
            label="主题"
            width="180">
            <template slot-scope="scope">
              <el-checkbox-group class="recycle-bin-table-checkbox" v-model="scope.row.checkList">
                <el-checkbox label="还原"></el-checkbox>
                <el-checkbox label="删除"></el-checkbox>
              </el-checkbox-group>
              <span>{{scope.row.theme}}</span>
            </template>
          </el-table-column>

          <el-table-column
            label="分类"
            prop="classification">
          </el-table-column>
          <el-table-column
            label="作者"
            prop="author">

          </el-table-column>
          <el-table-column
            label="回复/查看"
            prop="replyView">

          </el-table-column>
          <el-table-column
            prop="lastReply"
            label="最后回复">

          </el-table-column>
          <el-table-column
            prop="operator"
            label="操作人">

          </el-table-column>
          <el-table-column
            prop="theReason"
            label="原因">

          </el-table-column>

        </el-table>
      </div>

      <div class="recycle-bin-footer">

      </div>

    </div>
</template>

<script>
import '../../../scss/site/contStyle.scss';
import recycleBinCon from '../../../controllers/site/cont/recycleBinCon'
export default {
    name: "recycle-bin-view",
  ...recycleBinCon
}
</script>
