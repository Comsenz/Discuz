<template>
    <div class="recycle-bin-box">
      <div class="recycle-bin-header">
        <div class="recycle-bin-header__section">
          <div class="section-top">
            <span class="cont-review-header__lf-title">作者：</span>
            <el-input size="medium" v-model="searchUserName" clearable placeholder="搜索作者"></el-input>
          </div>
          <div>
            <span class="cont-review-header__lf-title">搜索范围：</span>
            <el-select v-model="categoriesListSelect" clearable  size="medium" placeholder="选择主题分类">
              <el-option
                v-for="item in categoriesList"
                :key="item.id"
                :label="item.name"
                :value="item.id">
              </el-option>
            </el-select>
          </div>
        </div>

        <div class="recycle-bin-header__section">
          <div class="section-top">
            <span class="cont-review-header__lf-title">内容包含：</span>
            <el-input size="medium" v-model="keyWords" clearable placeholder="搜索内容包含"></el-input>
          </div>
          <div>
            <span class="cont-review-header__lf-title">操作人：</span>
            <el-input size="medium" v-model="operator" clearable placeholder="搜索操作人"></el-input>
          </div>
        </div>

        <div class="recycle-bin-header__section">
          <div class="section-top">
            <span class="cont-review-header__lf-title time-title">发布时间范围：</span>
            <el-date-picker
              v-model="releaseTime"
              value-format="yyyy-MM-dd"
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
              v-model="deleteTime"
              value-format="yyyy-MM-dd"
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
          <el-button size="small" type="primary" @click="searchClick">搜索</el-button>
        </div>
      </div>

      <div class="recycle-bin-table">
        <ContArrange
          v-for="(items,index) in themeList"
          :author="!items.user?'该用户被删除':items.user._data.username"
          :theme="items.category._data.name"
          :finalPost="formatDate(items._data.createdAt)"
          :deleTime="formatDate(items._data.deletedAt)"
          :userId="!items.user?'该用户被删除':items.user._data.id"
          :key="items._data.id"
        >
          <div class="recycle-bin-table__side" slot="side">
            <el-radio-group @change="radioChange($event,index)" v-model="submitForm[index].radio">
              <el-radio label="还原"></el-radio>
              <el-radio label="删除"></el-radio>
            </el-radio-group>
          </div>

          <div class="recycle-bin-table__main" slot="main">
            <a class="recycle-bin-table__main__cont-text" :href="'/details/' + items._data.id" target="_blank" v-html="items.firstPost._data.contentHtml"></a>
            <div class="recycle-bin-table__main__cont-imgs">
              <p class="recycle-bin-table__main__cont-imgs-p" v-for="(item,index) in items.firstPost.images" :key="item._data.thumbUrl">
                <img  v-lazy="item._data.thumbUrl" @click="imgShowClick(items.firstPost.images,index)" :alt="item._data.fileName">
              </p>
            </div>
            <div class="recycle-bin-table__main__cont-annex" v-show="items.firstPost.attachments.length > 0">
              <span>附件：</span>
              <p v-for="(item,index) in items.firstPost.attachments" :key="index">
                <a :href="item._data.url" target="_blank">{{item._data.fileName}}</a>
              </p>
            </div>
          </div>

          <div class="recycle-bin-table__footer" slot="footer">
            <div class="recycle-bin-table__footer-operator">
              <span>操作者：</span>
              <span>{{!items.user?'操作者被禁止或删除':items.deletedUser._data.username}}</span>
            </div>

            <div class="recycle-bin-table__footer-reason" v-if="items.lastDeletedLog._data.message.length > 0">
              <span>原因：</span>
              <span>{{!items.user?'操作者被禁止或删除':items.lastDeletedLog._data.message}}</span>
            </div>

          </div>

        </ContArrange>

        <el-image-viewer
          v-if="showViewer"
          :on-close="closeViewer"
          :url-list="url" />

        <tableNoList v-show="themeList.length < 1"></tableNoList>

        <Page
          v-if="pageCount > 1"
          @current-change="handleCurrentChange"
          :current-page="currentPaga"
          :page-size="10"
          :total="total">
        </Page>
      </div>

      <div class="recycle-bin-footer footer-btn">
        <el-button size="small" type="primary" @click="submitClick">提交</el-button>
        <el-button type="text" @click="allOperationsSubmit(1)">全部还原</el-button>
        <el-button type="text" @click="allOperationsSubmit(2)">全部删除</el-button>
        <!-- <el-checkbox v-model="appleAll">将操作应用到其他所有页面</el-checkbox> -->
      </div>

    </div>
</template>

<script>
import '../../../../scss/site/contStyle.scss';
import recycleBinCon from '../../../../controllers/site/cont/recycleBin/recycleBinCon'
import ElRadio from "element-ui/packages/radio/src/radio";
export default {
  components: {ElRadio},
  name: "recycle-bin-view",
  ...recycleBinCon
}
</script>
