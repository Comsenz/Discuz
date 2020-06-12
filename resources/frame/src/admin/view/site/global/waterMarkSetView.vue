<template>
  <div class="site-set-box">
    <Card header="开启水印：">
      <CardRow description="是否开启水印">
        <el-switch
          v-model="switchBtn"
          active-color="#336699"
          inactive-color="#bbbbbb"
        >
        </el-switch>
      </CardRow>
    </Card>

    <Card header="图片：">
      <CardRow description="请选择图片（.png格式）进行上传">
        <el-upload
          class="avatar-uploader"
          action
          :http-request="uploaderLogo"
          :show-file-list="false"
          :on-success="handleAvatarSuccess"
          @change="handleFile"
          :before-upload="beforeAvatarUpload"
        >
          <div v-if="imageUrl" class="avatar">
            <img
              :src="imageUrl"
              class="avatar-LogoImage"
            />
          </div>
          <i v-else class="el-icon-plus avatar-uploader-icon"></i>
        </el-upload>
        <el-button
          type="text"
          :style="{
            opacity: deleteBtn ? '1' : '0',
            cursor: deleteBtn ? 'pointer' : 'auto'
          }"
          @click="deleteImage"
          >删除</el-button
        >
      </CardRow>
    </Card>

    <Card header="水印位置：">
      <CardRow description="请选择水印所在的位置">
        <div class="posi-list">
          <span class="posi-child" v-for="(item, index) in posiList" :key="index" v-bind:class="{ posiactive:index==posiCurrent }" @click="posiClick(item.val, index)">{{
            item.name
          }}</span>
        </div>
      </CardRow>
    </Card>
    <Card header="边距：">
      <CardRow description="请选择水印距离图片的边距">
        <div class="proportion-box">
          <span>垂直</span>
          <el-input
            class
            size="small"
            type="number"
            v-model="verticalSpacing"
            @blur.native.capture="onblurFun"
          ></el-input>
          <span class="marL10">PX</span>
        </div>
        <div class="proportion-box">
          <span>水平</span>
          <el-input
            size="small"
            type="number"
            v-model="horizontalSpacing"
            @blur.native.capture="onblurFun"
          ></el-input>
          <span class="marL10">PX</span>
        </div>
      </CardRow>
    </Card>
    <Card class="footer-btn">
      <el-button type="primary" size="medium" @click="submi">提交</el-button>
    </Card>
  </div>
</template>

<script>
import waterMarkSetCon from "../../../controllers/site/global/waterMarkSetCon";
import "../../../scss/site/module/globalStyle.scss";
export default {
  name: "water-mark-set-view",
  ...waterMarkSetCon
};
</script>
<style>
  .avatar-LogoImage {
    max-width: 96%;
  }
</style>
