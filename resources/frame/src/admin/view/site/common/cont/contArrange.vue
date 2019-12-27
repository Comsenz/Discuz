<template>
    <div class="cont-arrange-box">
      <div  class="cont-arrange-main">
        <div class="cont-arrange__lf-side">
          <slot name="side"></slot>
        </div>
        <main class="cont-arrange__rt-main">
          <div class="cont-arrange__rt-main-header">

            <div class="cont-arrange__rt-main-header__release">
              <p v-if="$attrs.author" ref="userName">
                <a :href="'/home-page/' + $attrs.userId" style="color: #333333;" target="_blank">
                  {{$attrs.author}}
                </a>
              </p>
              <p v-if="$attrs.replyBy" ref="userName">
                <a :href="'/home-page/' + $attrs.userId" style="color: #333333;" target="_blank">
                  {{$attrs.replyBy}}
                </a>
              </p>
              <span v-if="$attrs.author">发布于</span>
              <span v-if="$attrs.replyBy">回复主题</span>
              <p v-if="$attrs.theme">{{$attrs.theme}}</p>
              <p v-if="$attrs.themeName" ref="themeName" :class="$attrs.themeName?'themeName':''" :style=themeNameStyle >123{{$attrs.themeName}}</p>
            </div>

            <div v-if="$attrs.prply >= 0 && $attrs.browse >= 0" class="cont-arrange__rt-main-header__reply-view rt-box">
              <span>回复/查看：</span>
              <span>{{$attrs.prply}}/{{$attrs.browse}}</span>
            </div>

            <div v-if="$attrs.last" class="cont-arrange__rt-main-header__last-reply rt-box">
              <span>最后回复：</span>
              <span>{{$attrs.last}}</span>
            </div>

            <div v-if="$attrs.ip" class=" rt-box">
              <span>IP：</span>
              <span>{{$attrs.ip}}</span>
            </div>

            <div v-if="$attrs.finalPost" class="cont-arrange__rt-main-header__release-time rt-box">
              <span>更新时间：</span>
              <span>{{$attrs.finalPost}}</span>
            </div>

            <div v-if="$attrs.deleTime" class=" rt-box">
              <span>删除时间：</span>
              <span>{{$attrs.deleTime}}</span>
            </div>

            <slot name="header"></slot>
          </div>
          <div class="cont-arrange__rt-main-box" ref="contMain" v-bind:style="{'height':showContStatus? mainHeight + 30 + 'px':mainHeight>78?'78PX':''}"
          ><!--三元运算方法意思：高度不超过78PX也就是三行，不设置高度，高度自适应。如果超过78PX，则设置高度为78PX，显示'显示内容'组件。-->
            <slot  name="main"></slot>
          </div>

          <div ref="contControl" v-if="mainHeight > 78" class="cont-block-control" :class="showBottomStatus?'is-bottom-out':''" @click="showCont" >
            <p>
              <span class="iconfont icondown-menu" :class="showContStatus?'show-down':''"></span>
              {{showContStatus?"收起详情":"展开详情"}}
            </p>
          </div>

          <div class="cont-arrange__rt-main-footer" v-if="$slots.footer">
            <slot name="footer"></slot>
          </div>
        </main>
      </div>
    </div>
</template>

<script>
import  "../../../../scss/site/common/cont/contArrage.scss";
import contArrangeCon from '../../../../controllers/site/common/cont/contArrangeCon';
export default {
    name: "cont-arrange-view",
  ...contArrangeCon
}
</script>
