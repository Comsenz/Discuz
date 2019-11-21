<template>
    <div class="recycle-bin-box">
      <Card header="搜索"></Card>
      <div class="recycle-bin-header">
        <div class="recycle-bin-header__section">
          <div class="section-top">
            <span class="cont-review-header__lf-title">作者：</span>
            <el-input size="medium"></el-input>
          </div>
          <div>
            <span class="cont-review-header__lf-title">搜索范围：</span>
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

        <div class="recycle-bin-header__section">
          <div class="section-top">
            <span class="cont-review-header__lf-title">内容包含：</span>
            <el-input size="medium"></el-input>
          </div>
          <div>
            <span class="cont-review-header__lf-title">操作人：</span>
            <el-input size="medium"></el-input>
          </div>
        </div>

        <div class="recycle-bin-header__section">
          <div class="section-top">
            <span class="cont-review-header__lf-title time-title">发布时间范围：</span>

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
          </div>
          <div>
            <span class="cont-review-header__lf-title time-title">删除时间范围：</span>

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
          </div>
        </div>

        <div class="recycle-bin-header__section">
          <el-button size="small" type="primary">搜索</el-button>
        </div>
      </div>

      <div class="recycle-bin-table">
        <!--<el-table
          :data="tableData"
          style="width: 100%">
          <el-table-column
            label="主题"
            width="230">
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

        </el-table>-->

        <ContArrange
          author="小虫"
          theme="站长圈"
          finalPost="2019-1-1 12:00"
          deleTime="2019-12-12 13:00"
        >

          <div class="recycle-bin-table__side" slot="side">
            <el-checkbox-group v-model="checkList">
              <el-checkbox label="还原"></el-checkbox>
              <el-checkbox label="删除"></el-checkbox>
            </el-checkbox-group>
          </div>

          <div class="recycle-bin-table__main" slot="main">
            主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
          </div>

          <div class="recycle-bin-table__footer" slot="footer">
            <div class="recycle-bin-table__footer-operator">
              <span>操作者：</span>
              <span>小虫</span>
            </div>

            <div class="recycle-bin-table__footer-reason">
              <span>原因：</span>
              <span>文不对题</span>
            </div>

          </div>

        </ContArrange>

      </div>

      <div class="recycle-bin-footer">
        <el-button size="small" type="primary">提交</el-button>
        <el-button type="text">全部还原</el-button>
        <el-button type="text">全部删除</el-button>
        <el-checkbox v-model="checked">将操作应用到其他所有页面</el-checkbox>
      </div>

    </div>
</template>

<script>
import '../../../../scss/site/contStyle.scss';
import recycleBinCon from '../../../../controllers/site/cont/recycleBin/recycleBinCon'
export default {
    name: "recycle-bin-view",
  ...recycleBinCon
}
</script>
