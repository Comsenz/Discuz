import Vue from 'vue';
import {FormItem,Form,Option,Select,TableColumn,Table,CheckboxGroup,Checkbox,RadioButton,RadioGroup,Radio,Card,Button,Message,Input,Container,Header,Aside,Main,Menu,MenuItem } from 'element-ui'

Vue.use(FormItem).use(Form).use(Option).use(Select).use(TableColumn).use(Table).use(CheckboxGroup).use(Checkbox).use(RadioButton).use(RadioGroup).use(Radio).use(Card).use(Button).use(Input).use(Container).use(Header).use(Aside).use(Main).use(Menu).use(MenuItem);

Vue.prototype.$message = Message;
