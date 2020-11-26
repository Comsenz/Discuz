<template>
  <div class="report-manage-box">
    <!-- 举报管理搜索 -->
    <div class="report-manage-header">
      <div class="report-manage-header__section">
        <span class="report-manage-header__section-title">举报人：</span>
        <el-input size="medium" v-model="searchData.userName" clearable></el-input>
      </div>
      <div class="report-manage-header__section">
        <span class="report-manage-header__section-title">举报类型：</span>
        <el-select v-model="searchData.reportType" clearable size="medium">
          <el-option
						v-for="item in reportTypeData"
						:key="item.id"
						:label="item.name"
						:value="item.id">
          </el-option>
        </el-select>
      </div>
      <div class="report-manage-header__section">
        <span class="report-manage-header__section-title time-title">举报时间范围：</span>
        <el-date-picker
          v-model="searchData.reportTime"
          value-format="yyyy-MM-dd"
          type="daterange"
          align="right"
          unlink-panels
          size="medium"
          range-separator="至"
          start-placeholder="开始日期"
          end-placeholder="结束日期"
        ></el-date-picker>
      </div>
      <div class="report-manage-header__section">
        <el-button size="small" type="primary" @click="searchClick">搜索</el-button>
      </div>
    </div>
    <!-- 举报列表 -->
    <div class="report-manage-content">
      <div class="report-manage-content__header">
        <el-checkbox
          :indeterminate="isIndeterminate"
          v-model="checkAll"
          @change="handleCheckAllChange"
        ></el-checkbox>
        <p class="report-manage-content__header-title">举报列表</p>
      </div>
      <ContArrange
				class="report-manage-content__table"
				v-for="items in reportList"
				:key="items._data.id"
				:establish="!items.user ? '该用户被删除' : items.user._data.username"
				:userId="!items.user?'该用户被删除':items.user._data.id"
				:time="formatDate(items._data.created_at)"
				:type="getType(items._data.type)"
			>
        <div class="report-manage-content__table-side" slot="side">
          <el-checkbox
            v-model="checkedReport"
             :label="items._data.id"
            @change="handleCheckedCitiesChange()"
          ></el-checkbox>
        </div>
        <div class="report-manage-content__table-main" slot="main">
          <p>
						页面地址：
						<a :href="getUrl(items.user._data.id, items._data.thread_id, items._data.post_id).href" style="color: #3E4043;" target="_blank">
							{{getUrl(items.user._data.id, items._data.thread_id, items._data.post_id).url}}
						</a>
						</p>
          <p>举报时间：{{formatDate(items._data.updated_at)}}</p>
          <p>举报理由：</p>
          <p>{{items._data.reason}}</p>
        </div>
        <div class="report-manage-content__table-footer" slot="footer">
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
                    deleteOperation(1, items._data.id);
                    closeDelet(`popover-${index}`)"
                  >确定</el-button
                >
              </div>
          <el-button slot="reference" type="text">删除</el-button>
            </el-popover>
        </div>
      </ContArrange>
       <tableNoList v-show="reportList.length < 1"></tableNoList>
			<Page
        v-if="pageData.pageCount > 1"
        @current-change="handleCurrentChange"
        :current-page="pageData.pageNumber"
        :page-size="pageData.pageSize"
        :total="pageData.pageTotal"
      ></Page>
    </div>
    <!-- 举报操作 -->
    <div class="report-manage-footer">
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
                    deleteOperation(2)
                    visible = false"
                  >确定</el-button
                >
              </div>
			<el-button slot="reference" size="small" type="primary" :loading="subLoading">全部删除</el-button>
            </el-popover>
		</div>
  </div>
</template>
<script>
import "@/admin/scss/site/module/contStyle.scss";
import reportProcessedCon from "@/admin/controllers/site/cont/reportManage/reportProcessedCon";
export default {
  name: "report-processed-view",
  ...reportProcessedCon,
};
</script>