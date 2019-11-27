import Vue from 'vue';
import {Upload,Popover,DatePicker,FormItem,Form,Option,Select,TableColumn,Table,CheckboxGroup,Checkbox,RadioButton,RadioGroup,Radio,Card,Button,Message,Input,Container,Header,Aside,Main,Menu,MenuItem } from 'element-ui'

Vue.use(Upload).use(Popover).use(DatePicker).use(FormItem).use(Form).use(Option).use(Select).use(TableColumn).use(Table).use(CheckboxGroup).use(Checkbox).use(RadioButton).use(RadioGroup).use(Radio).use(Card).use(Button).use(Input).use(Container).use(Header).use(Aside).use(Main).use(Menu).use(MenuItem);

Vue.prototype.$message = Message;


import 'element-ui/lib/theme-chalk/base.css';
// collapse 展开折叠
import CollapseTransition from 'element-ui/lib/transitions/collapse-transition';

Vue.component(CollapseTransition.name, CollapseTransition);
