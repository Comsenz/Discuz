
/*
* 支付方式管理器
* */

/**
 * 公共组件：支付方式——使用文档
 * @param {{引入}}            例： import PayMethod from '../../../view/m_site/common/pay/paymentMethodView';
 *
 * @param {[api——参数]}       类型————   说明——————
 * @param {{data}}           Array     支付方式数组，格式：let data = [{name:'微信支付',icon:'icon-weixin'}] 。name、icon为固定字段。name = '钱包'为默认内置判断条件名称
 * @param {{v-model}}        Boolean   当前组件是否显示
 * @param {{money}}          String    金额
 * @param {{balance}}        String    余额
 * @param {{error}}          String    支付密码错误信息
 * @param {{walletStatus}}   Boolean   钱包设置支付密码状态
 * @param {{payUrl}}         String    设置支付密码路由,点击钱包注释后可跳转对应路由
 * @param {{pwdValue}}       String    支付密码输入框的值
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
 * @param {{clickOverlay}}             点击遮罩层和右上角关闭按钮事件
 *                                     回调参数：-
 */
import webDb from '../../../../../../helpers/webDbHelper';

export default {
  data:function () {
    return {
      paySelectShow:false,           //支付方式弹框
      payImmediatelyShow:false,      //立即支付弹框
      radio: 0,                      //选中的支付方式
      descriptionShow:false,         //钱包描述是否显示
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
    },
    walletStatus:{
      type:Boolean,
      default:false
    },
    payUrl:{
      type:String
    },
    pwdValue:{
      type:String,
      default:''
    }
  },
  methods:{
    onInput(key) {
      this.$emit('oninput',key);
    },
    onDelete() {
      this.$emit('delete');
    },
    onClose(){
      this.$emit('close');
    },
    payImmediatelyClick(name){
      if ((!this.descriptionShow && this.walletStatus) && this.data[this.radio].name !== '钱包'){
        this.showKeyboard = true;
        if (this.data[this.radio].name === '钱包'){
          this.paySelectShow = !this.paySelectShow;
          this.payImmediatelyShow = !this.payImmediatelyShow
        }
        this.$emit('payImmediatelyClick',this.data[this.radio])
      }
    },
    payStatusClick(){
      if (this.payUrl){
        this.$router.push({path:'/' + this.payUrl});
        webDb.setLItem('payUrl',this.$route.fullPath);
      }
    },
    clickOverlay(){
      this.$emit('input', false);
      this.$emit('clickOverlay');
      this.radio = 0;
      this.paySelectBox = false;
      this.payImmediatelyShow = false;
    }
  },
  watch:{
    value(val){
      this.paySelectShow = val;
      this.descriptionShow = parseFloat(this.money) > parseFloat(this.balance);
      if (!val){
        this.paySelectBox = false;
        this.payImmediatelyShow = false;
        this.showKeyboard = false;
      }
    },
    payImmediatelyShow(val){
      if (!val){
        // this.pwdValue = '';
      }
    }
  }
}
