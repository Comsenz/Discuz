
/*
* 支付方式管理器
* */

/**
 * 公共组件：支付方式——使用文档
 * @param {{引入}}            例： import PayMethod from '../../../view/m_site/common/pay/paymentMethodView';
 *
 * @param {[api——参数]}       类型————   说明——————
 * @param {{data}}           Array     支付方式数组，格式：let data = [{name:'微信支付',icon:'icon-weixin'}] 。name、icon为固定字段。
 * @param {{v-model}}        Boolean   当前组件是否显示
 * @param {{money}}          String    金额
 * @param {{balance}}        String    余额
 * @param {{error}}          String    支付密码错误信息
 *
 * @param {{Events——事件}}              说明——————
 * @param {{oninput}}                  密码输入时触发
 *                                     回调参数：每次输入的字符
 * @param {{delete}}                   数字键盘删除事件
 *                                     回调参数：-
 * @param {{close}}                    关闭组件时触发
 *                                     回调参数：-
 * @param {{payImmediatelyClick}}      立即支付点击事件
 *                                     回调参数：选中项
 */

export default {
  data:function () {
    return {
      paySelectShow:false,           //支付方式弹框
      payImmediatelyShow:false,      //立即支付弹框
      radio: 0,                      //选中的支付方式
      descriptionShow:false,         //钱包描述是否显示
      pwdValue: '',                  //钱包密码
      showKeyboard: false,           //键盘是否显示
    }
  },
  props:{
    value:{
      type:Boolean,
      default:false
  },
    money:{
      type:String,
      default:'0.00'
    },
    balance:{
      type:String,
      default:'0.00'
    },
    data:{
      type:Array,
      default:[
        {
          name:'钱包',
          icon:''
        }
      ]
    },
    error:{
      type:String
    }
  },
  methods:{
    onInput(key) {
      this.pwdValue = (this.pwdValue + key).slice(0, 6);
      this.$emit('oninput',key);
    },
    onDelete() {
      this.pwdValue = this.pwdValue.slice(0, this.pwdValue.length - 1);
      this.$emit('delete');
    },
    onClose(){
      this.$emit('close')
    },
    payImmediatelyClick(){
      if (this.data[this.radio].name === '钱包'){
        this.paySelectShow = !this.paySelectShow;
        this.payImmediatelyShow = !this.payImmediatelyShow
      }
      this.$emit('payImmediatelyClick',this.data[this.radio])
    }
  },
  watch:{
    value(val){
      this.paySelectShow = val;
      this.descriptionShow = this.money > this.balance;
    },
    paySelectShow(val){
      if (!val){
        this.$emit('input', false);
      }
    },
    payImmediatelyShow(val){
      if (!val){
        this.pwdValue = '';
      }
    }
  }
}
