<template>
    <div class="home-page-box">
      <HomePageHeader title="Eliza的个人主页"></HomePageHeader>
      <main>

      </main>

    </div>
</template>

<script>
import '../../../less/m_site/myInfo/myInfo.less';
import homePageCon from '../../../controllers/m_site/myInfo/homePageCon';
export default {
    name: "home-page-view",
  ...homePageCon
}
</script>
