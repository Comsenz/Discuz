<template>
    <div class="latest-reply-box">
      <div class="latest-reply-header">
        <div class="latest-reply-header_top condition-box">
          <div class="latest-reply-header_condition latest-reply-header_condition-lf">
            <span class="latest-reply-header_condition-title">作者：</span>
            <el-input size="medium" placeholder="搜索作者" v-model="searchData.themeAuthor"  clearable ></el-input>
          </div>
          <div class="latest-reply-header_condition">
            <span class="latest-reply-header_condition-title">内容包含：</span>
            <el-input size="medium" placeholder="搜索内容包含" v-model="searchData.themeKeyWords" clearable ></el-input>
          </div>
        </div>

        <div class="latest-reply-header_bottom condition-box">
          <div class="latest-reply-header_condition condition-time">
            <span class="latest-reply-header_condition-title">发布时间：</span>
            <el-date-picker
              v-model="searchData.dataValue"
              type="daterange"
              align="right"
              unlink-panels
              range-separator="至"
              start-placeholder="开始日期"
              end-placeholder="结束日期"
              value-format="yyyy-MM-dd HH:mm:ss"
              :default-time="['00:00:00', '23:59:59']"
              :picker-options="pickerOptions">
            </el-date-picker>
            <el-button size="small" type="primary" @click="searchClick">搜索</el-button>
          </div>
        </div>
      </div>

      <div class="latest-reply-theme">
        <div class="latest-reply-theme__table">
          <div class="latest-reply-theme__table-header">
            <el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange"></el-checkbox>
            <p class="latest-reply-theme__table-header__title">回复列表</p>
          </div>

          <ContArrange
            v-for="(items,index) in  themeList"
            :replyBy="!items.user?'该用户被删除':items.user._data.username"
            :themeName="items.thread._data.type === 1?items.thread._data.title:items.thread.firstPost._data.content"
            :titleIcon="titleIcon(items.thread._data)"
            :finalPost="formatDate(items._data.updatedAt)"
            :userId="!items.user?'该用户被删除':items.user._data.id"
            :key="items._data.id"
          >
            <div class="latest-reply-theme__table-side" slot="side">
              <el-checkbox v-model="checkedTheme" :label="items._data.id" @change="handleCheckedCitiesChange()"></el-checkbox>
            </div>

            <!-- <a slot="longText" class="latest-reply-theme__table-long-text" v-if="items.thread._data.isLongArticle" :href="'/details/' + items._data.id" target="_blank">
              {{items.thread._data.title}}
              <span  class="iconfont" :class="parseInt(items.thread._data.price) > 0?'iconmoney':'iconchangwen'" ></span>
            </a> -->

            <div class="latest-reply-theme__table-main" slot="main">
              <a class="latest-reply-theme__table-main__cont-text" :href="'/topic/index?id=' + items.thread._data.id" target="_blank" v-html="items._data.contentHtml"></a>
              <div class="latest-reply-theme__table-main__cont-imgs">
                <p class="latest-reply-theme__table-main__cont-imgs-p"  v-for="(item,index) in items.images" :key="index">
                  <img  v-lazy="item._data.thumbUrl" @click="imgShowClick(items.images,index)" :alt="item._data.fileName">
                </p>
              </div>
            </div>

          <div class="latest-reply-theme__table-footer" slot="footer">
          <div class="latest-reply-theme__table-footer__lf">
            <el-popover
              width="100"
              placement="top"
              :ref="`popover-${index}`"
            >
              <p>确定删除该项吗？</p>
              <div style="text-align: right; margin: 10PX 0 0 0 ">
                <el-button
                  type="danger"
                  size="mini"
                  @click="closeDelet(`popover-${index}`)"
                >
                  取消
                </el-button>
                <el-button
                  type="primary"
                  size="mini"
                  @click="
                    singleOperationSubmit(1,items._data.id);
                    closeDelet(`popover-${index}`)"
                  >确定</el-button
                >
              </div>
                <el-button slot="reference" type="text">删除</el-button>
            </el-popover>
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
            :current-page="currentPag"
            :page-size="parseInt(searchData.pageSelect)"
            :total="total">
          </Page>
        </div>
      </div>

      <div class="latest-reply-footer">
            <el-popover
              width="100"
              placement="top"
              v-model="visible"
            >
              <p>确定删除该项吗？</p>
              <div style="text-align: right; margin: 10PX 0 0 0 ">
                <el-button
                  type="danger"
                  size="mini"
                  @click="visible = false"
                >
                  取消
                </el-button>
                <el-button
                  type="primary"
                  size="mini"
                  @click="
                    deleteAllClick()
                    visible = false"
                  >确定</el-button
                >
              </div>
            <el-button slot="reference" size="small" type="primary" :loading="subLoading">删除</el-button>
            </el-popover>
      </div>
    </div>
</template>

<script>
  import latestReplyCon from '../../../../controllers/site/cont/contManage/latestReplyCon';
  import '../../../../scss/site/module/contStyle.scss';

  export default {
    name: "latestReplyView",
    ...latestReplyCon
  }
</script>
