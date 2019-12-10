<template>
  <div class="user-details-box">
    <div class="details-wallet-header">
      <p class="details-wallet-header__name">admin（UID：13）</p>
      <i class="details-wallet-header__i"></i>
      <span class="details-wallet-header__details">详情</span>
      <span @click="$router.push({path:'/admin/wallet'})">钱包</span>
    </div>

    <Card>
      <!--<div class="user-avatar">
        <img src="../../../../../../static/images/noavatar.gif" alt="用户头像">
        <footer>
          <el-upload
            class="upload-demo"
            action="https://jsonplaceholder.typicode.com/posts/"
            :on-preview="handlePreview"
            :on-remove="handleRemove"
            :before-remove="beforeRemove"
            multiple
            :limit="3"
            :on-exceed="handleExceed"
            :file-list="fileList">
            <el-button size="small" type="text">重新上传</el-button>
            &lt;!&ndash;<el-button size="small" type="text">删除</el-button>&ndash;&gt;
            <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div>
          </el-upload>
        </footer>
      </div>-->
      <el-upload
        class="avatar-uploader"
        action="https://jsonplaceholder.typicode.com/posts/"
        :show-file-list="false"
        :on-success="handleAvatarSuccess"
        :before-upload="beforeAvatarUpload">
        <img v-if="imageUrl" :src="imageUrl" class="avatar">
        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
      </el-upload>
      <el-button type="text">删除</el-button>
    </Card>

    <Card header="新密码：">
      <CardRow description="如果不更改密码此处请留空">
        <el-input></el-input>
      </CardRow>
    </Card>

    <Card header="手机号：">
      <CardRow>
        <el-input></el-input>
      </CardRow>
    </Card>

    <Card header="状态：">
      <CardRow >
        <el-select v-model="value" placeholder="请选择">
          <el-option
            v-for="item in options"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </CardRow>
    </Card>

    <Card header="已加入的站点：">
      <span class="add-site">站长帮<i>（站长）</i></span>
      <span class="add-site">站长帮<i>（站长）</i></span>
    </Card>

    <Card header="注册时间：">
      <p>2011-1-1 13:19</p>
    </Card>

    <Card header="注册IP：">
      <p>192.168.0.1</p>
    </Card>

    <Card header="最后登录时间：">
      <p>2011-1-1 13:19</p>
    </Card>

    <Card header="最后登录IP：">
      <p>122.122.231.231</p>
    </Card>

    <Card header="注册时间：">
      <p>2011-1-1 13:19</p>
    </Card>

    <Card header="微信昵称：">
      <p>小虫</p>
    </Card>

    <Card header="性别：">
      <p>男</p>
    </Card>

    <Card header="实名认证姓名：">
      <p>大虫</p>
    </Card>

    <Card header="实名认证身份证号：">
      <p>0000234897289374</p>
    </Card>

    <Card class="footer-btn">
      <el-button type="primary" size="medium">提交</el-button>
    </Card>

  </div>
</template>

<script>
import '../../../../scss/site/userStyle.scss';
import userDetailsCon from '../../../../controllers/site/user/userMange/userDetailsCon'
export default {
    name: "user-details-view",
  ...userDetailsCon
}
</script>
