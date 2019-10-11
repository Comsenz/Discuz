import Vue from "vue";
import {
  MessageBox,
  Message,
  Dialog,
} from "element-ui";

Vue.use(Dialog);

Vue.prototype.$MessageBox = MessageBox;
Vue.prototype.$message = Message;
