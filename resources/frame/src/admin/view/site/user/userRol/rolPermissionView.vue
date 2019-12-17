<template>
  <div class="rol-permission-box">
    <Card header="设置权限——角色名称（成员）"></Card>

    <Card header="前台操作权限："></Card>
    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">查看帖子列表</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">查看帖子详情</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">发表帖子</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">回复帖子</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">上传附件</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">上传图片</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">查看附件</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">查看图片</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">站点会员列表</el-checkbox>
      </CardRow>
    </Card>


    <Card header="前台管理权限："></Card>
    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">删帖</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">置顶</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">加精</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">编辑</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">批量删除帖子</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">管理-邀请加入</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">编辑用户组</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">编辑用户状态（例如：禁用）</el-checkbox>
      </CardRow>
    </Card>

    <Card header="默认权限："></Card>
    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">站点信息</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">帖子点赞</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">帖子收藏</el-checkbox>
      </CardRow>
    </Card>

    <Card>
      <CardRow description="">
        <el-checkbox v-model="checked">帖子打赏</el-checkbox>
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
