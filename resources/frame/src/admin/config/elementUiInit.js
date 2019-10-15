import Vue from "vue";
import {
  MessageBox,
  Message,
  Dialog,
  Button
} from "element-ui";

Vue.use(Dialog);
import 'element-ui/lib/theme-chalk/dialog.css';

Vue.use(Button);
import 'element-ui/lib/theme-chalk/button.css';

Vue.prototype.$MessageBox = MessageBox;
Vue.prototype.$message = Message;
