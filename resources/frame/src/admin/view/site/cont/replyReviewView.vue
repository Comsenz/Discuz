<template>
    <div class="reply-review-box">
      <Card header="搜索"></Card>

      <div class="cont-review-header">
        <div class="cont-review-header__lf">
          <div >
            <span class="cont-review-header__lf-title">用户名：</span>
            <el-input size="medium"></el-input>
          </div>
          <div >
            <span  class="cont-review-header__lf-title">每页显示：</span>
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

        <div class="cont-review-header__rt">
          <div>
            <span  class="cont-review-header__lf-title">内容包含：</span>
            <el-input size="medium" class="content-contains-input"></el-input>
            <el-checkbox v-model="checked">显示敏感词</el-checkbox>
          </div>

          <div class="cont-review-header__rt-search">
            <span  class="cont-review-header__lf-title">搜索范围：</span>
            <el-select v-model="value" size="medium" placeholder="请选择">
              <el-option
                v-for="item in options"
                :key="item.value"
                :label="item.label"
                :value="item.value">
              </el-option>
            </el-select>
            <el-select v-model="value" size="medium" placeholder="请选择">
              <el-option
                v-for="item in options"
                :key="item.value"
                :label="item.label"
                :value="item.value">
              </el-option>
            </el-select>
            <el-select v-model="value" size="medium" placeholder="请选择">
              <el-option
                v-for="item in options"
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
          author="小虫"
          theme="站长圈"
          finalPost="2019-1-1 12:00"
          ip="111.123.204.10"
        >
          <div class="cont-review-table__side" slot="side">
            <el-checkbox-group v-model="checkList">
              <el-checkbox label="通过"></el-checkbox>
              <el-checkbox label="删除"></el-checkbox>
              <el-checkbox label="忽略"></el-checkbox>
            </el-checkbox-group>
          </div>

          <!--<div class="cont-review-table__header" slot="header">

            <div>
              <p class="desc">
                111.206.145.10
              </p>
            </div>

            <div>
              <p>默认分类</p>
            </div>

            <div>
              <p>小虫</p>
              <p class="desc">2010-1-1 13:11 </p>
            </div>

            <div>
              <el-button type="text">查看</el-button>
              <el-button type="text">编辑</el-button>
            </div>

          </div>-->

          <div class="cont-review-table__main" slot="main">
            就是肯定就是开绿灯解放了撒可见度分厘卡世界的士大夫胜多负少士大夫胜多负少分厘卡即使到了附件商店困了就睡了发商店了
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
              <el-input size="medium" ></el-input>
              <el-select size="medium" v-model="value" placeholder="请选择">
                <el-option
                  v-for="item in options"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value">
                </el-option>
              </el-select>
            </div>
          </div>

        </ContArrange>
      </div>

      <div class="cont-review-footer footer-btn">
        <el-button size="small" type="primary">提交</el-button>
        <el-button type="text">全部通过</el-button>
        <el-button type="text">全部删除</el-button>
        <el-button type="text">全部忽略</el-button>
        <el-checkbox v-model="checked">将操作应用到其他所有页面</el-checkbox>
      </div>

    </div>
</template>

<script>
import '../../../scss/site/contStyle.scss';
import replyReviewCon from '../../../controllers/site/cont/replyReviewCon'

export default {
    name: "reply-review-view",
  ...replyReviewCon
}
</script>
