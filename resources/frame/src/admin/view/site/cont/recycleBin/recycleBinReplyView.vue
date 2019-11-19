<template>
    <div class="recycle-bin-reply-box">
      <Card header="搜索"></Card>
      <div class="recycle-bin-reply-header">

        <div class="recycle-bin-reply-header__lf">
          <div>
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

        <div class="recycle-bin-reply-header__rt">
          <div class="recycle-bin-reply-header__rt-top">
            <span class="cont-review-header__lf-title">内容包含：</span>
            <el-input size="medium"></el-input>
            <span class="cont-review-header__lf-title">操作人：</span>
            <el-input size="medium"></el-input>
          </div>

          <div class="recycle-bin-reply-header__rt-bottom">
            <span class="recycle-bin-reply-header__rt-bottom__time-title">发布时间范围：</span>

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

      <div class="recycle-bin-reply-table">
        <ContArrange>
          <div class="recycle-bin-reply-table__side" slot="side">
            <el-checkbox-group v-model="checkList">
              <el-checkbox label="还原"></el-checkbox>
              <el-checkbox label="删除"></el-checkbox>
            </el-checkbox-group>
          </div>

          <div class="recycle-bin-reply-table__header" slot="header">
            <p>
              <span>默认分类 > </span>
              <span>12312312323</span>
            </p>
            <div class="recycle-bin-reply-table__header-bottom">
              <p>
                <span>作者：</span>
                <span>xy60cn</span>
              </p>
              <p>
                <span>时间：</span>
                <span>2019-10-10</span>
              </p>
              <p>
                <span>IP：</span>
                <span>111.111.111.111</span>
              </p>
              <p>
                <span>操作人：</span>
                <span>小虫</span>
              </p>
              <p>
                <span>原因：</span>
                <span>文不对题</span>
              </p>
            </div>
          </div>

          <div class="recycle-bin-reply-table__main" slot="main">
            就是肯定就是开绿灯解放了撒可见度分厘卡世界的士大夫胜多负少士大夫胜多负少分厘卡即使到了附件商店困了就睡了发商店了
          </div>

        </ContArrange>
      </div>

      <div class="recycle-bin-reply-footer">
        <el-button size="small" type="primary">提交</el-button>
        <el-button type="text">全部通过</el-button>
        <el-button type="text">全部删除</el-button>
      </div>

    </div>
</template>

<script>
import '../../../../scss/site/contStyle.scss';
import recycleBinReplyCon from '../../../../controllers/site/cont/recycleBinReplyCon'
export default {
    name: "recycle-bin-reply-view",
  ...recycleBinReplyCon
}
</script>
