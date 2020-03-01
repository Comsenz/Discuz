<template>
    <div class="payment-method-box">
      <van-popup
        v-model="paySelectShow"
        round
        safe-area-inset-bottom
        class="way-to-choose-box"
        @close="onClose"
        @click-overlay="clickOverlay"
        get-container="body">
        <div class="way-to-choose-main">
          <van-icon name="cross" class="icon-close" size="20" @click="clickOverlay"/>
          <div class="manner-title">
            <h1>立即支付</h1>
            <p><span>￥</span>{{money}}</p>
            <i class="manner-title_grid"></i>
          </div>

          <div class="way-to-choose_cont">
            <p class="way-to-choose_cont-title">支付方式</p>
            <div class="way-to-choose_cont-select">
              <van-radio-group v-model="radio">

                <div class="way-to-choose_cont-select_cell" v-for="(item,index) in data" @click="descriptionShow === !walletStatus?radio = index:''">
                  <div class="way-to-choose_cont-select_cell-left" >
                    <span class="icon iconfont" :class="item.icon"></span>
                    <div class="way-to-choose_cont-select_cell-left-title">
                      <span>{{item.name}}</span>
                      <p v-if="!walletStatus && item.name === '钱包'" class="way-to-choose_cont-select_cell-left-title_description" @click="payStatusClick">请设置钱包支付密码</p>
                      <p v-else-if="descriptionShow && item.name === '钱包'" class="way-to-choose_cont-select_cell-left-title_description">钱包余额不足，剩余{{balance}}元</p>
                    </div>
                  </div>
                  <van-radio slot="right-icon" :disabled="(descriptionShow || !walletStatus)&& item.name === '钱包'" :name="index" />
                </div>

              </van-radio-group>
            </div>
          </div>

          <div class="way-to-choose_footer">
            <van-button type="primary" @click="payImmediatelyClick">立即支付</van-button>
          </div>
        </div>
      </van-popup>

      <van-popup
        v-model="payImmediatelyShow"
        round
        safe-area-inset-bottom
        close-icon-position="top-right"
        @close="onClose"
        @click-overlay="clickOverlay"
        class="pay-immediately-box"
        :class="error?'pay-immediately-box-err':''"
        get-container="body">
        <div class="pay-immediately-main">
          <van-icon name="cross" class="icon-close" size="20" @click="clickOverlay"/>
          <div class="manner-title">
            <h1>立即支付</h1>
            <p><span>￥</span>{{money}}</p>
            <i class="manner-title_grid"></i>
          </div>
          <div class="pay-immediately-main_cont">
            <van-cell title="支付方式" is-link @click="paySelectShow = !paySelectShow;payImmediatelyShow = !payImmediatelyShow">
              <template slot="default">
                <span class="icon iconfont" :class="data[radio].icon"></span>
                <span class="custom-title">{{data[radio].name}}</span>
              </template>
            </van-cell>
          </div>

          <van-password-input
            class="passwordInp"
            :value="pwdValue"
            :focused="showKeyboard"
            @focus="showKeyboard = true"
            :error-info="error"
          />
        </div>
      </van-popup>

      <van-number-keyboard
        safe-area-inset-bottom
        :z-index="2100"
        :show="showKeyboard"
        @input="onInput"
        @delete="onDelete"
        @blur="showKeyboard = false"
      />
    </div>
</template>

<script>
  import paymentMethodCon from '../../../../controllers/m_site/common/pay/paymentMethodCon';
  import '../../../../defaultLess/m_site/common/common.less';
  export default {
    name: "paymentMethodView",
    ...paymentMethodCon
  }
</script>
