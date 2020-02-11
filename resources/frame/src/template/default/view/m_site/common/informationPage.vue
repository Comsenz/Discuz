<template>
    <div class="information-page-box">
      <van-icon class="information-icon" name="checked" color="#07c160" size="64" />
      <p class="title">{{setInfo[$route.query.setInfo].title}}</p>
      <span class="comment">{{setInfo[$route.query.setInfo].comment}}</span>
      <div class="login">
        <van-button type="primary" @click="btnClick">完成</van-button>
      </div>
    </div>
</template>

<script>
  import informationPageCon from '../../../controllers/m_site/common/informationPageCon';
  export default {
    name: "informationPage",
    ...informationPageCon
  }
</script>

<style lang="less">
  .information-page-box{
    width: 100%;
    height: 100%;
    margin-bottom: -3000px;
    overflow: hidden;
    background-color: white;
    display: flex;
    text-align: center;
    flex-direction: column;
    padding: 40% 20px 3000px 20px;

    .title{
      font-size:19px;
      color:black;
      margin-top: 30px;
      margin-bottom: 10px;
    }
    .comment{
      margin-top:10px;
      font-size: 15px;
      color:#000;
      opacity: 0.5;
    }
    .login{
      width:100%;
      margin-top:24px;
    }
  }
</style>
