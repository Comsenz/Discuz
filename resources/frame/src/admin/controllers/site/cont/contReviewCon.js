/*
* 内容审核
* */

import Card from '../../../view/site/common/card/card';
import ContArrange from '../../../view/site/common/cont/contArrange';


export default {
  data:function () {
    return {
      tableData: [{
        className: '站长',
        sort: '1',
        classIntroduction:'分类介绍分类介绍分类介绍分类介绍分类介绍分类介绍'
      }, {
        className: '攻城狮',
        sort: '2',
        classIntroduction:'分类介绍分类介绍分类介绍分类介绍分类介绍分类介绍'
      }, {
        className: '版主',
        sort: '3',
        classIntroduction:'分类介绍分类介绍分类介绍分类介绍分类介绍分类介绍'
      }],

      deleteStatus:true,
      multipleSelection:[],

      checkList: ['通过'],

      options: [{
        value: '选项1',
        label: '黄金糕'
      }, {
        value: '选项2',
        label: '双皮奶'
      }, {
        value: '选项3',
        label: '蚵仔煎'
      }, {
        value: '选项4',
        label: '龙须面'
      }, {
        value: '选项5',
        label: '北京烤鸭'
      }],
      value: '',

      checked:false

    }
  },

  methods:{
    handleSelectionChange(val) {
      this.multipleSelection = val;

      if (this.multipleSelection.length >= 1){
        this.deleteStatus = false
      } else {
        this.deleteStatus = true
      }

    },

  },
  components:{
    Card,
    ContArrange
  }

}
