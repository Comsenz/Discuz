<template>
  <div class="cont-review-box">
    <Card header="搜索"></Card>

    <div class="cont-review-header">
      <div class="cont-review-header__lf">
        <div >
          <span class="cont-review-header__lf-title">用户名：</span>
          <el-input size="medium" v-model="searchUserName"></el-input>
        </div>
        <div >
          <span  class="cont-review-header__lf-title">每页显示：</span>
          <el-select v-model="pageSelect" size="medium" placeholder="选择每页显示">
            <el-option
              v-for="item in pageOptions"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </div>
      </div>

      <div class="cont-review-header__rt">
        <div>
          <span  class="cont-review-header__lf-title">内容包含：</span>
          <el-input size="medium" class="content-contains-input" v-model="keyWords" ></el-input>
          <el-checkbox v-model="showSensitiveWords">显示敏感词</el-checkbox>
        </div>

        <div class="cont-review-header__rt-search">
          <span  class="cont-review-header__lf-title">搜索范围：</span>
          <el-select v-model="searchReviewSelect" size="medium" placeholder="选择审核状态">
            <el-option
              v-for="item in searchReview"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
          <el-select v-model="searchCategorySelect" size="medium" placeholder="选择搜索分类">
            <el-option
              v-for="item in searchCategory"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
          <el-select v-model="searchTimeSelect" size="medium" placeholder="选择搜索时间">
            <el-option
              v-for="item in searchTime"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
          <el-button size="small" type="primary">搜索</el-button>
        </div>

      </div>
    </div>

    <div class="cont-review-table">
      <ContArrange

        v-for="(items,index) in  themeList"
        :author="items.user().username()"
        :theme="items.category().name()"
        :finalPost="formatDate(items.createdAt())"
        :ip="items.firstPost().ip()"
        :key="items.id()"
      >
        <div class="cont-review-table__side" slot="side">
          <el-checkbox-group v-model="checkList">
            <el-checkbox label="通过"></el-checkbox>
            <el-checkbox label="删除"></el-checkbox>
            <el-checkbox label="忽略"></el-checkbox>
          </el-checkbox-group>
        </div>

        <div class="cont-review-table__main" slot="main">
          {{items.firstPost().content()}}
        </div>

        <div class="cont-review-table__footer" slot="footer">
          <div class="cont-review-table__footer__lf">
            <el-button type="text">通过</el-button>
            <i></i>
            <el-button type="text">删除</el-button>
            <i></i>
            <el-button type="text">忽略</el-button>
          </div>

          <div class="cont-review-table__footer__rt">
            <span>操作理由：</span>
            <el-input size="medium" v-model="reasonForOperationInput" ></el-input>
            <el-select size="medium" @change="reasonForOperationChange" v-model="reasonForOperationSelect" placeholder="选择操作理由">
              <el-option
                v-for="item in reasonForOperation"
                :key="item.value"
                :label="item.label"
                :value="item.value">
              </el-option>
            </el-select>
          </div>

          <div class="cont-review-table__footer__bottom">
            <el-button type="text">查看</el-button>
            <el-button type="text">编辑</el-button>
          </div>

        </div>

      </ContArrange>

      <div class="cont-review-table__table-footer" v-if="pageCount > 1">
        <el-pagination
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
          :current-page.sync="currentPag"
          :page-size="parseInt(searchData.pageSelect)"
          layout="total, prev, pager, next,jumper"
          :total="total">
        </el-pagination>
      </div>
    </div>

    <div class="cont-review-footer footer-btn">
      <el-button size="small" type="primary">提交</el-button>
      <el-button type="text">全部通过</el-button>
      <el-button type="text">全部删除</el-button>
      <el-button type="text">全部忽略</el-button>
      <el-checkbox v-model="appleAll">将操作应用到其他所有页面</el-checkbox>
    </div>

  </div>
</template>

<script>
import '../../../scss/site/contStyle.scss';
import contReviewCon from '../../../controllers/site/cont/contReviewCon'
export default {
    name: "cont-review-view",
  ...contReviewCon
}
</script>
