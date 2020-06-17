<template>
  <div class="cont-manage-box">
    <div class="cont-manage-header">
      <div class="cont-manage-header_top condition-box">
        <div class="cont-manage-header_condition cont-manage-header_condition-lf">
          <span class="cont-manage-header_condition-title">作者：</span>
          <el-input size="medium" placeholder="搜索作者" v-model="searchData.topicAuthor" clearable></el-input>
        </div>
        <div class="cont-manage-header_condition cont-manage-header_condition-rh">
          <span class="cont-manage-header_condition-title">话题：</span>
          <el-input size="medium" placeholder="搜索话题" v-model="searchData.topicContent" clearable></el-input>
        </div>

        <div class="cont-manage-header_condition cont-manage-header_condition-mid">
          <span class="cont-manage-header_condition-titles">创建时间范围：</span>
          <el-input size="medium" placeholder="大于" v-model="searchData.viewedTimesMin" clearable></el-input>
          <div class="spacing">-</div>
          <el-input size="medium" placeholder="小于" v-model="searchData.viewedTimesMax" clearable></el-input>
        </div>
      </div>

      <div class="cont-manage-header_bottom condition-box">
        <div class="cont-manage-header_condition">
          <span class="cont-manage-header_condition-titles">主题数介于：</span>
          <el-input
            size="medium"
            placeholder="大于"
            v-model="searchData.numberOfRepliesMin"
            clearable
          ></el-input>
          <div class="spacing">-</div>
          <el-input
            size="medium"
            placeholder="小于"
            v-model="searchData.numberOfRepliesMax"
            clearable
          ></el-input>
        </div>

        <div class="cont-manage-header_condition">
          <span class="cont-manage-header_condition-titles">热度数介于：</span>
          <el-input
            size="medium"
            placeholder="大于"
            v-model="searchData.numberOfRepliesMin"
            clearable
          ></el-input>
          <div class="spacing">-</div>
          <el-input
            size="medium"
            placeholder="小于"
            v-model="searchData.numberOfRepliesMax"
            clearable
          ></el-input>
          <el-button size="small" type="primary" @click="searchClick">搜索</el-button>
        </div>
      </div>
    </div>

    <div class="cont-manage-theme">
      <div class="cont-manage-theme__table">
        <div class="cont-manage-theme__table-header">
          <el-checkbox
            :indeterminate="isIndeterminate"
            v-model="checkAll"
            @change="handleCheckAllChange"
          ></el-checkbox>
          <p class="cont-manage-theme__table-header__title">主题列表</p>
        </div>

        <ContArrange
          v-for="(items,index) in  themeList"
          :establish="!items.user?'该用户被删除':items.user._data.username"
          :theme="formatDate(items._data.created_at)"
          :numbertopic="items._data.thread_count"
          :heatNumber="items._data.view_count"
          :key="items._data.id"
        >
          <div class="cont-manage-theme__table-side" slot="side">
            <el-checkbox
              v-model="checkedTheme"
              :label="items._data.id"
              @change="handleCheckedCitiesChange()"
            ></el-checkbox>
          </div>

          <a
            slot="longText"
            class="cont-manage-theme__table-long-text"
            :href="'/details/' + items._data.id"
            target="_blank"
          >
            {{`#${items._data.content}#`}}
          </a>
        </ContArrange>

        <el-image-viewer v-if="showViewer" :on-close="closeViewer" :url-list="url" />

        <tableNoList v-show="themeList.length < 1"></tableNoList>

        <div class="cont-manage-theme__table-footer" v-if="pageCount > 1">
          <Page
            @current-change="handleCurrentChange"
            :current-page="currentPag"
            :page-size="10"
            :total="total"
          ></Page>
        </div>
      </div>
    </div>

    <div class="cont-manage-operating">
      <Card class="footer-btn">
        <el-button @click="deleteClick" :loading="subLoading" type="primary">全部删除</el-button>
      </Card>
    </div>
  </div>
</template>

<script>
import "../../../../scss/site/module/contStyle.scss";
import topicManagementCon from "../../../../controllers/site/cont/topicManagement/topicManagementCon";
export default {
  name: "topic-management-view",
  ...topicManagementCon
};
</script>