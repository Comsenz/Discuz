<template>
  <div class="reward-box">
    <SystemHeader title="系统通知"></SystemHeader>
    <van-list
    v-model="loading"
    :finished="finished"
    finished-text="没有更多了"
    :immediate-check="false"
    @load="onLoad"
    >
      <van-pull-refresh v-model="isLoading" @refresh="onRefresh">
      <main class="reward-main content">
        <div class="systemCon cell-crossing" v-for='(item,index) in systemResList' :key='index'>
          <div class="systemTitle">{{item._data.title}}</div>
          <div class="systemTime">{{$moment(item._data.created_at).format('YYYY-MM-DD HH:mm')}}</div>
          <div class="systemDet">{{item._data.content}}</div>
        </div>
      </main>
      </van-pull-refresh>
    </van-list>
    <footer class="my-info-money-footer"></footer>
  </div>
</template>

<script>
import '../../../../defaultLess/m_site/common/common.less';
import '../../../../defaultLess/m_site/modules/myInfo.less';
import systemCon from '../../../../controllers/m_site/myInfo/myNotice/systemCon';
export default {
  name: "system",
  ...systemCon
}
</script>
