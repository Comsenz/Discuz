<template>
  <div class="user-details-box">
    <div class="details-wallet-header">
      <p class="details-wallet-header__name">{{this.userInfo.username}}（UID：{{this.userInfo.id}}）</p>
      <i class="details-wallet-header__i"></i>
      <span class="details-wallet-header__details">详情</span>
      <span @click="$router.push({path:'/admin/wallet', query: query})">钱包</span>
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
        action
        :http-request="uploaderLogo"
        :show-file-list="false"
        :on-success="handleAvatarSuccess"
        @change="handleFile"
        :before-upload="beforeAvatarUpload"
      >
        <img v-if="imageUrl" :src="imageUrl" class="avatar" />
        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
      </el-upload>
      <el-button
        type="text"
        :style="{'opacity':deleBtn?'1':'0','cursor':deleBtn?'pointer':'auto'}"
        @click="deleteImage"
      >删除</el-button>
    </Card>

    <Card header="新密码：">
      <CardRow description="如果不更改密码此处请留空">
        <el-input v-model="newPassword" clearable :disabled="disabled"></el-input>
      </CardRow>
    </Card>

    <Card header="手机号：">
      <CardRow>
        <el-input v-model="userInfo.originalMobile"></el-input>
      </CardRow>
    </Card>

    <Card header="用户角色：">
      <CardRow description="设置允许参与搜索的用户组">
        <el-select v-model="userRole[0]" placeholder="请选择">
          <el-option
            v-for="item in options"
            :disabled="item.value === '6' || item.value === '7'"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          ></el-option>
        </el-select>
      </CardRow>
    </Card>

    <Card header="状态：">
      <CardRow>
        <el-select
          v-model="userInfo.status"
          placeholder="请选择"
          @change="userStatusChange(userInfo.status)"
        >
          <el-option
            v-for="item in optionsStatus"
            :disabled="item.value ==='2'"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          ></el-option>
        </el-select>
      </CardRow>
    </Card>

    <Card header="禁用原因：" v-show="disabledReason">
      <CardRow>
        <el-input v-model="reasonsForDisable" clearable></el-input>
      </CardRow>
    </Card>

    <!-- <Card header="已加入的站点：">
      <span class="add-site">站长帮<i>（站长）</i></span>
      <span class="add-site">站长帮<i>（站长）</i></span>
    </Card>-->

    <Card header="注册时间：">
      <p>{{$moment(userInfo.createdAt).format('YYYY-MM-DD HH:mm')}}</p>
    </Card>

    <Card header="注册IP：">
      <p>{{userInfo.registerIp}}</p>
    </Card>

    <Card header="最后登录时间：" v-if="userInfo.loginAt">
      <p>{{$moment(userInfo.loginAt).format('YYYY-MM-DD HH:mm')}}</p>
    </Card>

    <Card header="最后登录IP：">
      <p>{{userInfo.lastLoginIp}}</p>
    </Card>

    <Card header="微信昵称：" v-if="wechatNickName">
      <p>{{wechatNickName}}</p>
    </Card>

    <Card header="性别：" v-if="sex">
      <p>{{sex === 0 ? "未知" : sex === 1 ? "男" : "女"}}</p>
    </Card>

    <Card header="实名认证姓名：" v-show="realname">
      <p>{{userInfo.realname}}</p>
    </Card>

    <Card header="实名认证身份证号：" v-show="realname">
      <p>{{userInfo.identity}}</p>
    </Card>

    <Card class="footer-btn">
      <el-button type="primary" size="medium" @click="submission">提交</el-button>
    </Card>
  </div>
</template>

<script>
import "../../../../scss/site/module/userStyle.scss";
import userDetailsCon from "../../../../controllers/site/user/userMange/userDetailsCon";
export default {
  name: "user-details-view",
  ...userDetailsCon
};
</script>
