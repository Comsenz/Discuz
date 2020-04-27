<template>
  <div class="rol-permission-box">
    <Card :header="'设置权限——' + $router.history.current.query.name"></Card>
    <Card header="内容发布权限："></Card>
    <Card>
      <CardRow description="允许发布主题">
        <el-checkbox
          v-model="checked"
          label="createThread"
          :disabled="$router.history.current.query.id === '1' || $router.history.current.query.id === '7'"
        >发布主题</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="允许发布长文">
        <el-checkbox
          v-model="checked"
          label="createThreadLong"
          :disabled="$router.history.current.query.id === '1' || $router.history.current.query.id === '7'"
        >发布长文</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="允许发布视频">
        <el-checkbox
          v-model="checked"
          label="createThreadVideo"
          :disabled="videoDisabled || $router.history.current.query.id === '1' || $router.history.current.query.id === '7'"
        >发布视频</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="回复主题的权限">
        <el-checkbox
          v-model="checked"
          label="thread.reply"
          :disabled="$router.history.current.query.id === '1' || $router.history.current.query.id === '7'"
        >回复主题</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="发布主题时上传附件的权限">
        <el-checkbox
          v-model="checked"
          label="attachment.create.0"
          :disabled="$router.history.current.query.id === '1' || $router.history.current.query.id === '7'"
        >上传附件</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="发布主题时上传图片的权限">
        <el-checkbox
          v-model="checked"
          label="attachment.create.1"
          :disabled="$router.history.current.query.id === '1' || $router.history.current.query.id === '7'"
        >上传图片</el-checkbox>
      </CardRow>
    </Card>

    <Card header="安全设置："></Card>
    <Card>
      <CardRow description="启用验证码需先在腾讯云设置中开启验证码服务">
        <el-checkbox
          v-model="checked"
          label="createThreadWithCaptcha"
          :disabled="disabled || $router.history.current.query.id === '1' || $router.history.current.query.id === '7'"
        >发表内容时启用验证码</el-checkbox>
      </CardRow>
    </Card>

    <Card header="前台操作权限："></Card>
    <Card>
      <CardRow description="查看主题列表页的权限">
        <el-checkbox
          v-model="checked"
          label="viewThreads"
          :disabled="$router.history.current.query.id === '1'"
        >查看主题列表</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="查看主题的详情页的权限">
        <el-checkbox
          v-model="checked"
          label="thread.viewPosts"
          :disabled="$router.history.current.query.id === '1'"
        >查看主题详情</el-checkbox>
      </CardRow>
    </Card>

    <!-- <Card>
      <CardRow description="查看主题的详情页中的附件的权限">
        <el-checkbox v-model="checked" label="attachment.view.0">查看附件</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="查看主题的详情页中的图片的权限">
        <el-checkbox v-model="checked" label="attachment.view.1">查看图片</el-checkbox>
      </CardRow>
    </Card>-->

    <Card>
      <CardRow description="查看站点成员列表、搜索成员的权限">
        <el-checkbox
          v-model="checked"
          label="viewUserList"
          :disabled="$router.history.current.query.id === '1' || $router.history.current.query.id === '7'"
        >站点会员列表</el-checkbox>
      </CardRow>
    </Card>

    <Card header="前台管理权限："></Card>
    <Card>
      <CardRow description="前台删除单个主题的权限">
        <el-checkbox
          v-model="checked"
          label="thread.hide"
          :disabled="$router.history.current.query.id === '1' || $router.history.current.query.id === '7'"
        >删主题</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="前台删除单个回复的权限">
        <el-checkbox
          v-model="checked"
          label="thread.hidePosts"
          :disabled="$router.history.current.query.id === '1' || $router.history.current.query.id === '7'"
        >删回复</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="前台置顶、取消置顶主题的权限">
        <el-checkbox
          v-model="checked"
          label="thread.sticky"
          :disabled="$router.history.current.query.id === '1' || $router.history.current.query.id === '7'"
        >置顶</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="前台精华、取消精华主题的权限">
        <el-checkbox
          v-model="checked"
          label="thread.essence"
          :disabled="$router.history.current.query.id === '1' || $router.history.current.query.id === '7'"
        >加精</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="前台单个主题的编辑权限">
        <el-checkbox
          v-model="checked"
          label="thread.editPosts"
          :disabled="$router.history.current.query.id === '1' || $router.history.current.query.id === '7'"
        >编辑</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="前台批量管理主题的权限">
        <el-checkbox
          v-model="checked"
          label="thread.batchEdit"
          :disabled="$router.history.current.query.id === '1' || $router.history.current.query.id === '7'"
        >批量管理主题</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="前台按用户组邀请成员的权限">
        <el-checkbox
          v-model="checked"
          label="createInvite"
          :disabled="$router.history.current.query.id === '1' || $router.history.current.query.id === '7'"
        >管理-邀请加入</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="前台更改成员所属用户组的权限">
        <el-checkbox
          v-model="checked"
          label="group.edit"
          :disabled="$router.history.current.query.id === '1' || $router.history.current.query.id === '7'"
        >编辑用户组</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="前台更改成员禁用状态的权限">
        <el-checkbox
          v-model="checked"
          label="user.edit"
          :disabled="$router.history.current.query.id === '1' || $router.history.current.query.id === '7'"
        >编辑用户状态</el-checkbox>
      </CardRow>
    </Card>

    <Card header="用户信息权限："></Card>
    <Card>
      <CardRow description="开启后将可以修改用户名1次">
        <el-checkbox
          v-model="checked"
          label="user.edit.username"
          :disabled="$router.history.current.query.id === '1' || $router.history.current.query.id === '7'"
        >修改用户名</el-checkbox>
      </CardRow>
    </Card>

    <Card header="其他设置："></Card>
    <Card>
      <CardRow description="开启后将在用户名后显示角色">
        <el-checkbox
          v-model="checked"
          label="showGroups"
          :disabled="$router.history.current.query.id === '1' || $router.history.current.query.id === '7'"
        >显示用户角色</el-checkbox>
      </CardRow>
    </Card>

    <Card header="默认权限："></Card>
    <Card>
      <CardRow description>
        <p style="margin-left: 24PX">站点信息</p>
      </CardRow>
    </Card>

    <Card>
      <CardRow description>
        <p style="margin-left: 24PX">主题点赞</p>
      </CardRow>
    </Card>

    <Card>
      <CardRow description>
        <p style="margin-left: 24PX">主题收藏</p>
      </CardRow>
    </Card>

    <Card>
      <CardRow description>
        <p style="margin-left: 24PX">主题打赏</p>
      </CardRow>
    </Card>

    <!-- <Card header="权限限制："></Card>
    <Card>
      <CardRow description="发布主题，后台管理员审核后，前台才可显示">
        <el-checkbox v-model="checked">发布主题需审核</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="回复主题，后台管理员审核后，前台才可显示">
        <el-checkbox v-model="checked">回复主题需审核</el-checkbox>
      </CardRow>
    </Card>-->

    <Card class="footer-btn">
      <el-button type="primary" @click="submitClick" size="medium">提交</el-button>
    </Card>
  </div>
</template>

<script>
import "../../../../scss/site/module/userStyle.scss";
import rolPermissionCon from "../../../../controllers/site/user/userRol/rolPermissionCon";
export default {
  name: "user-permission-view",
  ...rolPermissionCon
};
</script>
