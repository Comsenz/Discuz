<template>
    <div class="cont-manage-search-box">
      <Card header="主题分类：">
        <el-select v-model="value" placeholder="请选择">
          <el-option
            v-for="item in options"
            :key="item.value"
            :label="item.label"
            :value="item.value">
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
            <el-input></el-input>
        </CardRow>
      </Card>

      <Card header="主题关键词：">
        <CardRow description="多关键词中间请用半角逗号“,”隔开">
          <el-input></el-input>
        </CardRow>
      </Card>

      <el-collapse-transition>
        <div v-show="checkedStatus"  class="cont-manage-search-more">

            <Card header="发表时间范围：">
              <CardRow description="格式yyyy-mm-dd，留空则不限制">
                <el-date-picker
                  v-model="dataValue"
                  type="daterange"
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
              <el-input></el-input>
              <i></i>
              <el-input></el-input>
            </Card>

            <Card class="range-box" header="被回复数介于：">
              <el-input></el-input>
              <i></i>
              <el-input></el-input>
            </Card>

            <Card header="被回复数介于：">
              <el-radio-group v-model="replyType">
                <el-radio :label="3">包含</el-radio>
                <el-radio :label="6">仅搜索</el-radio>
                <el-radio :label="9">排除</el-radio>
              </el-radio-group>
            </Card>

            <Card header="置顶主题：">
              <el-radio-group v-model="topType">
                <el-radio :label="3">包含</el-radio>
                <el-radio :label="6">仅搜索</el-radio>
                <el-radio :label="9">排除</el-radio>
              </el-radio-group>
            </Card>

        </div>
       </el-collapse-transition>

      <Card>
        <el-button type="primary">提交</el-button>
        <el-checkbox v-model="checkedStatus" @change="checkboxChange">更多</el-checkbox>
      </Card>



    </div>
</template>

<script>
import '../../../scss/site/contStyle.scss'
import contManageSearch from '../../../controllers/site/cont/contManageSearchCon'
export default {
    name: "cont-manage-search-view",
  ...contManageSearch
}
</script>
