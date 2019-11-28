<template>
    <div class="cont-arrange-box">
      <div class="cont-arrange-main">
        <div class="cont-arrange__lf-side">
          <slot name="side"></slot>
        </div>
        <main class="cont-arrange__rt-main">
          <div class="cont-arrange__rt-main-header">

            <div class="cont-arrange__rt-main-header__release">
              <p>{{$attrs.author}}</p>
              <span>发布于</span>
              <p>{{$attrs.theme}}</p>
            </div>

            <div v-if="$attrs.prply && $attrs.browse" class="cont-arrange__rt-main-header__reply-view rt-box">
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
              <span>发布时间：</span>
              <span>{{$attrs.finalPost}}</span>
            </div>

            <div v-if="$attrs.deleTime" class=" rt-box">
              <span>删除时间：</span>
              <span>{{$attrs.deleTime}}</span>
            </div>

            <slot name="header"></slot>
          </div>
          <div class="cont-arrange__rt-main-box" ref="contMain" v-bind:style="{'height':showContStatus? mainHeight + 40 + 'px':mainHeight>78?'78PX':''}"
          ><!--三元运算方法意思：高度不超过78PX也就是三行，不设置高度，高度自适应。如果超过78PX，则设置高度为78PX，显示'显示内容'组件。-->
            <slot  name="main"></slot>
          </div>

          <div ref="contControl" v-if="mainHeight > 78" class="cont-block-control" :class="showBottomStatus?'is-bottom-out':''" @click="showCont" >
            <p>
              <span class="iconfont icondown-menu" :class="showBottomStatus?'show-down':''"></span>
              {{showContStatus?"收起详情":"展开详情"}}
            </p>
          </div>

          <div class="cont-arrange__rt-main-footer" v-if="$slots.footer">
            <slot name="footer"></slot>
          </div>
        </main>
      </div>
      <!--多列，获取到父组件传过来数组的长度，生成多个模板-->
      <!--<div class="cont-arrange-main">
        <div class="cont-arrange__lf-side">
          <slot name="side"></slot>
        </div>
        <main class="cont-arrange__rt-main">
          <div class="cont-arrange__rt-main-header">

            <div class="cont-arrange__rt-main-header__release">
              <p>{{$attrs.author}}</p>
              <span>发布于</span>
              <p>{{$attrs.theme}}</p>
            </div>

            <div v-if="$attrs.prply && $attrs.browse" class="cont-arrange__rt-main-header__reply-view rt-box">
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
              <span>发布时间：</span>
              <span>{{$attrs.finalPost}}</span>
            </div>

            <div v-if="$attrs.deleTime" class=" rt-box">
              <span>删除时间：</span>
              <span>{{$attrs.deleTime}}</span>
            </div>

            <slot name="header"></slot>
          </div>
          <div class="cont-arrange__rt-main-box" ref="contMain"  v-bind:style="{'height':showContStatus? mainHeight + 40 + 'px':mainHeight>78?'78PX':''}"
          >&lt;!&ndash;三元运算方法意思：高度不超过78PX也就是三行，不设置高度，高度自适应。如果超过78PX，则设置高度为78PX，显示'显示内容'组件。&ndash;&gt;
            <slot  name="main"></slot>
          </div>
          <div ref="contControl" v-if="mainHeight > 78" class="cont-block-control" :class="showBottomStatus?'is-bottom-out':''" @click="showCont" >
            <p>
              <span class="iconfont icondown-menu" :class="showBottomStatus?'show-down':''"></span>
              {{showContStatus?"收起详情":"展开详情"}}
            </p>
          </div>
          <div class="cont-arrange__rt-main-footer" v-if="$slots.footer">
            <slot name="footer"></slot>
          </div>
        </main>
      </div>-->
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
