import Vue from 'vue';
import { Button,Message,Input,Container,Header,Aside,Main } from 'element-ui'

Vue.use(Button).use(Input).use(Container).use(Header).use(Aside).use(Main);

Vue.prototype.$message = Message;
