import Vue from 'vue';
import { Card,Button,Message,Input,Container,Header,Aside,Main,Menu,MenuItem } from 'element-ui'

Vue.use(Card).use(Button).use(Input).use(Container).use(Header).use(Aside).use(Main).use(Menu).use(MenuItem);

Vue.prototype.$message = Message;
