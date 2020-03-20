<template>
  <div class="cont-manage-box">
    <div class="cont-manage-header">
      <div class="cont-manage-header_top condition-box">
        <div class="cont-manage-header_condition cont-manage-header_condition-lf">
          <span class="cont-manage-header_condition-title">作者：</span>
          <el-input size="medium" placeholder="搜索作者" v-model="searchData.themeAuthor" clearable></el-input>
        </div>
        <div class="cont-manage-header_condition cont-manage-header_condition-rh">
          <span class="cont-manage-header_condition-title">内容包含：</span>
          <el-input size="medium" placeholder="搜索内容" v-model="searchData.themeKeyWords" clearable></el-input>
        </div>
      </div>

      <div class="cont-manage-header_middle condition-box">
        <div class="cont-manage-header_condition cont-manage-header_condition-lf">
          <span class="cont-manage-header_condition-title">搜索范围：</span>
          <el-select v-model="searchData.categoryId" placeholder="选择搜索范围">
            <el-option
              v-for="item in categoriesList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            ></el-option>
          </el-select>
        </div>
        <div class="cont-manage-header_condition">
          <span class="cont-manage-header_condition-title">主题类型：</span>
          <el-select v-model="searchData.topicTypeId" placeholder="选择主题类型">
            <el-option v-for="item in topicType" :key="item.id" :label="item.name" :value="item.id"></el-option>
          </el-select>
        </div>
        <div class="cont-manage-header_condition">
          <span class="cont-manage-header_condition-title">发布时间:</span>
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
            :picker-options="pickerOptions"
          ></el-date-picker>
        </div>
      </div>

      <div class="cont-manage-header_bottom condition-box">
        <!--<div class="cont-manage-header_condition cont-manage-header_condition-lf">
            <span class="cont-manage-header_condition-title">发布时间:</span>
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
        </div>-->
        <div class="cont-manage-header_condition cont-manage-header_condition-mid">
          <span class="cont-manage-header_condition-title">浏览次数：</span>
          <el-input size="medium" placeholder="大于" v-model="searchData.viewedTimesMin" clearable></el-input>
          <div class="spacing">-</div>
          <el-input size="medium" placeholder="小于" v-model="searchData.viewedTimesMax" clearable></el-input>
        </div>
        <div class="cont-manage-header_condition">
          <span class="cont-manage-header_condition-title">被回复数：</span>
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
          :author="!items.user?'该用户被删除':items.user._data.username"
          :theme="items.category._data.name"
          :prply="items._data.postCount - 1"
          :browse="items._data.viewCount"
          :last="!items.lastPostedUser?'该用户被删除':items.lastPostedUser._data.username"
          :releaseTime="formatDate(items._data.createdAt)"
          :userId="!items.user?'该用户被删除':items.user._data.id"
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
            v-if="items._data.type === 1"
            :href="'/details/' + items._data.id"
            target="_blank"
          >
            {{items._data.title}}
            <span
              class="iconfont"
              :class="parseInt(items._data.price) > 0?'iconmoney':'iconchangwen'"
            ></span>
          </a>

          <div class="cont-manage-theme__table-main" slot="main">
            <a
              class="cont-manage-theme__table-main__cont-text"
              :href="'/details/' + items._data.id"
              target="_blank"
              :style="{'display':(items.threadVideo ? 'inline':'block')}"
              v-html="items.firstPost._data.contentHtml"
            ></a>
            <span class="iconfont iconvideo" v-if="items.threadVideo"></span>
            <div class="cont-manage-theme__table-main__cont-imgs">
              <p
                class="cont-manage-theme__table-main__cont-imgs-p"
                v-for="(item,index) in items.firstPost.images"
                :key="index"
              >
                <img
                  v-lazy="item._data.thumbUrl"
                  @click="imgShowClick(items.firstPost.images,index)"
                  :alt="item._data.fileName"
                />
              </p>
            </div>
            <div
              class="cont-manage-theme__table-main__cont-annex"
              v-show="items.firstPost.attachments.length > 0"
            >
              <span>附件：</span>
              <p v-for="(item,index) in items.firstPost.attachments" :key="index">
                <a :href="item._data.url" target="_blank">{{item._data.fileName}}</a>
              </p>
            </div>
          </div>
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
      <p>操作</p>
      <el-table :data="operatingList" tooltip-effect="dark" style="width: 100%">
        <el-table-column
          label-class-name="cont-manage-operating__table-label"
          label="操作"
          prop="theme"
          width="250"
        >
          <template slot-scope="scope">
            <el-radio v-model="operatingSelect" :label="scope.row.label">{{scope.row.name}}</el-radio>
          </template>
        </el-table-column>

        <el-table-column label="选项" min-width="250">
          <template slot-scope="scope">
            <el-select v-if="scope.row.name === '批量移动到分类'" v-model="categoryId" placeholder="选择分类">
              <el-option
                v-for="item in categoriesList"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              ></el-option>
            </el-select>

            <el-radio-group
              class="cont-manage__option-select"
              v-if="scope.row.name === '批量置顶'"
              v-model="toppingRadio"
            >
              <el-radio :label="1">置顶</el-radio>
              <el-radio :label="2">解除置顶</el-radio>
            </el-radio-group>

            <el-radio-group
              class="cont-manage__option-select"
              v-if="scope.row.name === '批量设置精华'"
              v-model="essenceRadio"
            >
              <el-radio :label="1">精华</el-radio>
              <el-radio :label="2">取消精华</el-radio>
            </el-radio-group>
          </template>
        </el-table-column>
      </el-table>

      <Card class="footer-btn">
        <el-button @click="submitClick" type="primary">提交</el-button>
      </Card>
    </div>
  </div>
</template>

<script>
import "../../../../scss/site/module/contStyle.scss";
import contManageCon from "../../../../controllers/site/cont/contManage/contManageCon";
export default {
  name: "cont-manage-view",
  ...contManageCon
};
</script>
