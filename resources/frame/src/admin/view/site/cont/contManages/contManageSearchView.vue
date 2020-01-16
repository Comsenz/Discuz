<template>
    <div class="cont-manage-search-box">
      <Card header="主题分类：">
        <el-select v-model="categoryId" placeholder="请选择">
          <el-option
            v-for="item in categoriesList"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
      </Card>

      <Card header="每页显示数：">
        <el-select v-model="pageSelect" placeholder="请选择">
          <el-option
            v-for="item in pageOptions"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </Card>

      <Card header="主题作者：">
        <CardRow description="多用户名中间请用半角逗号“,”隔开">
            <el-input placeholder="主题作者名称" clearable v-model="themeAuthor"></el-input>
        </CardRow>
      </Card>

      <Card header="主题关键词：">
        <CardRow description="多关键词中间请用半角逗号“,”隔开">
          <el-input placeholder="主题关键词" clearable v-model="themeKeyWords"></el-input>
        </CardRow>
      </Card>

      <el-collapse-transition>
        <div v-show="checkedStatus"  class="cont-manage-search-more">

            <Card header="发表时间范围：">
              <CardRow description="格式yyyy-mm-dd，留空则不限制">
                <el-date-picker
                  v-model="dataValue"
                  type="daterange"
                  value-format="yyyy-MM-dd"
                  align="center"
                  unlink-panels
                  range-separator="至"
                  start-placeholder="开始日期"
                  end-placeholder="结束日期"
                  :picker-options="pickerOptions">
                </el-date-picker>
              </CardRow>
            </Card>

            <Card class="range-box" header="被浏览次数介于：">
              <el-input v-model="viewedTimesMin" ></el-input>
              <i></i>
              <el-input v-model="viewedTimesMax" ></el-input>
            </Card>

            <Card class="range-box" header="被回复数介于：">
              <el-input v-model="numberOfRepliesMin" ></el-input>
              <i></i>
              <el-input v-model="numberOfRepliesMax" ></el-input>
            </Card>

            <Card header="精华主题：">
              <el-radio-group v-model="essentialTheme">
                <el-radio label="">包含</el-radio>
                <el-radio label="yes">仅搜索</el-radio>
                <el-radio label="no">排除</el-radio>
              </el-radio-group>
            </Card>

            <Card header="置顶主题：">
              <el-radio-group v-model="topType">
                <el-radio label="">包含</el-radio>
                <el-radio label="yes">仅搜索</el-radio>
                <el-radio label="no">排除</el-radio>
              </el-radio-group>
            </Card>

        </div>
       </el-collapse-transition>

      <Card class="footer-btn">
        <el-button type="primary" @click="submitClick">提交</el-button>
        <el-checkbox v-model="checkedStatus" @change="checkboxChange">更多</el-checkbox>
      </Card>

    </div>
</template>

<script>
import '../../../../scss/module/site/contStyle.scss'
import contManageSearch from '../../../../controllers/site/cont/contManage/contManageSearchCon'
export default {
    name: "cont-manage-search-view",
  ...contManageSearch
}
</script>
