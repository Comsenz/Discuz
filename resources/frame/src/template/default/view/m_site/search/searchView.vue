<template>
    <div class="search">
      <!-- <form action="/">
        <van-search
          v-model="value"
          placeholder="搜索用户和主题"
          show-action
          @search="onSearch"
          @cancel="onCancel"
        />
      </form> -->
      <input type="text" name="" class="searchInp">
      <div class="searchRes">
        
      </div>
    </div>
</template>

<script>
import  '../../../scss/m_site/mobileIndex.scss';
import '../../../scss/m_site/login/loginSignUpModule.scss';
import loginCon from '../../../controllers/m_site/login/loginUserCon'
export default {
    name: "search-view",
  ...loginCon
}
</script>
