<template>
  <div class="my-collection-box">
    <CollectionHeader title="我的收藏"></CollectionHeader>
    <main class="my-collection-main content">
      <div class="my-collection-main-cont" v-for="(item,index) in collectionList" :key="index">

        <div class="home-page-cont">
          <ContHeader
            :imgUrl="imgUrl"
            :stateTitle="stateTitle"
            :time="item.attributes.createdAt"
            :userName="userName">
          </ContHeader>
          <ContMain
            :contText="contText">
          </ContMain>
          <ContFooter></ContFooter>
        </div>
      </div>
      <!-- <div class="my-collection-main-cont">
        <div class="barrier"></div>
        <div class="home-page-cont">
          <ContHeader
            :imgUrl="imgUrl"
            :stateTitle="stateTitle"
            :time="time"
            :userName="userName">
          </ContHeader>
          <ContMain
            :contText="contText">
          </ContMain>
          <ContFooter></ContFooter>
        </div>
      </div> -->
    </main>
  </div>
</template>

<script>
import '../../../less/m_site/myInfo/myInfo.less';
import myCollectionCon from '../../../controllers/m_site/myInfo/myCollectionCon';
import '../../../scss/m_site/mobileIndex.scss';
export default {
  name: "my-collection-view",
  ...myCollectionCon
}
</script>
