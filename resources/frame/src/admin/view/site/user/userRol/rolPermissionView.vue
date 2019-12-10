<template>
  <div class="rol-permission-box">
    <Card header="设置权限——角色名称（成员）"></Card>

    <Card header="帖子操作："></Card>
    <Card>
      <CardRow description="查看主题的详情页的权限">
        <el-checkbox v-model="checked">查看主题</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="发布主题的权限">
        <el-checkbox v-model="checked">发布主题</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="回复主题的权限">
        <el-checkbox v-model="checked">回复主题</el-checkbox>
      </CardRow>
    </Card>


    <Card header="站点前台管理："></Card>
    <Card>
      <CardRow description="前台设置成员的角色、禁用成员的权限">
        <el-checkbox v-model="checked">成员管理</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="前台批量删除主题的权限">
        <el-checkbox v-model="checked">批量删除</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="前台邀请用户注册并成为某一角色的权限">
        <el-checkbox v-model="checked">邀请成员</el-checkbox>
      </CardRow>
    </Card>


    <Card header="前台内容管理："></Card>
    <Card>
      <CardRow description="前台置顶主题的权限">
        <el-checkbox v-model="checked">置顶</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="前台精华、取消精华主题的权限">
        <el-checkbox v-model="checked">加精</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="前台删除单个主题的权限">
        <el-checkbox v-model="checked">删帖</el-checkbox>
      </CardRow>
    </Card>


    <Card header="权限限制："></Card>
    <Card>
      <CardRow description="发布主题，后台管理员审核后，前台才可显示">
        <el-checkbox v-model="checked">发布主题需审核</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="回复主题，后台管理员审核后，前台才可显示">
        <el-checkbox v-model="checked">回复主题需审核</el-checkbox>
      </CardRow>
    </Card>

    <Card class="footer-btn">
      <el-button type="primary" size="medium">提交</el-button>
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
