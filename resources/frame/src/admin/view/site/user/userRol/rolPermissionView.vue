<template>
  <div class="rol-permission-box">
    <Card header="设置权限——角色名称（成员）"></Card>

    <Card header="前台操作权限："></Card>
    <Card>
      <CardRow description="查看主题列表页的权限">
        <el-checkbox v-model="checked" label="viewThreadList">查看主题列表</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="查看主题的详情页的权限">
        <el-checkbox v-model="checked" label="viewThread">查看主题详情</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="发布主题的权限">
        <el-checkbox v-model="checked" label="createThread">发表帖子</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="回复主题的权限">
        <el-checkbox v-model="checked" label="thread.reply">回复主题</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="发布主题时上传附件的权限">
        <el-checkbox v-model="checked" label="attachment.create.0">上传附件</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="发布主题时上传图片的权限">
        <el-checkbox v-model="checked" label="attachment.create.1">上传图片</el-checkbox>
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
    </Card> -->

    <Card>
      <CardRow description="查看站点成员列表、搜索成员的权限">
        <el-checkbox v-model="checked" label="viewUserList">站点会员列表</el-checkbox>
      </CardRow>
    </Card>


    <Card header="前台管理权限："></Card>
    <Card>
      <CardRow description="前台删除单个主题的权限">
        <el-checkbox v-model="checked" label="thread.deletePosts">删帖</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="前台置顶、取消置顶主题的权限">
        <el-checkbox v-model="checked" label="thread.sticky">置顶</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="前台精华、取消精华主题的权限">
        <el-checkbox v-model="checked" label="thread.essence">加精</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="前台单个主题的编辑权限">
        <el-checkbox v-model="checked" label="thread.editPosts">编辑</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="前台批量删除主题的权限">
        <el-checkbox v-model="checked" label="thread.hide">批量删除主题</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="前台按用户组邀请成员的权限">
        <el-checkbox v-model="checked">管理-邀请加入</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="前台更改成员所属用户组的权限">
        <el-checkbox v-model="checked" label="group.edit">编辑用户组</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="前台更改成员禁用状态的权限">
        <el-checkbox v-model="checked" label="user.edit">编辑用户状态</el-checkbox>
      </CardRow>
    </Card>

    <Card header="默认权限："></Card>
    <Card>
      <CardRow description="">
        <p style="margin-left: 24PX">站点信息</p>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
        <p style="margin-left: 24PX">主题点赞</p>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
        <p style="margin-left: 24PX">主题收藏</p>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
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
import '../../../../scss/site/userStyle.scss';
import rolPermissionCon from '../../../../controllers/site/user/userRol/rolPermissionCon';
export default {
    name: "user-permission-view",
  ...rolPermissionCon
}
</script>
