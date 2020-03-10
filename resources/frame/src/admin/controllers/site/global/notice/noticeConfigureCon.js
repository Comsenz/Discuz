/*
* 通知设置配置控制器
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
    data: function () {
      return {
        noticeTitle: '',      //用户角色通知标题
        noticeContent: '',    //用户通知内容
        query: '',            //获取当前用户的ID
        wxNoticeDescription:{
          13:"请在“微信公众号-模板消息”中按照以下格式添加模版，并填写审核通过后的模板ID。<br>\n" +
            "{{first.DATA}}\n" +
            "用户名：{{keyword1.DATA}}\n" +
            "时间：{{keyword2.DATA}}\n" +
            "{{{remark.DATA}}\n",
          14:"请在“微信公众号-模板消息”中按照以下格式添加模版，并填写审核通过后的模板ID。<br>\n" +
            "{{first.DATA}}\n" +
            "用户名：{{keyword1.DATA}}\n" +
            "时间：{{keyword2.DATA}}\n" +
            "{{remark.DATA}}",
          15:"请在“微信公众号-模板消息”中按照以下格式添加模版，并填写审核通过后的模板ID。<br>\n" +
            "{{first.DATA}}\n" +
            "用户名：{{keyword1.DATA}}\n" +
            "原因：{{keyword2.DATA}}\n" +
            "{{remark.DATA}}",
          16:"请在“微信公众号-模板消息”中按照以下格式添加模版，并填写审核通过后的模板ID。<br>\n" +
            "{{first.DATA}}\n" +
            "内容：{{keyword1.DATA}}\n" +
            "时间：{{keyword2.DATA}}\n" +
            "{{remark.DATA}}",
          17:"请在“微信公众号-模板消息”中按照以下格式添加模版，并填写审核通过后的模板ID。<br>\n" +
            "{{first.DATA}}\n" +
            "内容：{{keyword1.DATA}}\n" +
            "原因：{{keyword2.DATA}}\n" +
            "时间：{{keyword3.DATA}}\n" +
            "{{remark.DATA}}",
          18:"请在“微信公众号-模板消息”中按照以下格式添加模版，并填写审核通过后的模板ID。<br>\n" +
            "{{first.DATA}}\n" +
            "内容：{{keyword1.DATA}}\n" +
            "原因：{{keyword2.DATA}}\n" +
            "时间：{{keyword3.DATA}}\n" +
            "{{remark.DATA}}",
          19:"请在“微信公众号-模板消息”中按照以下格式添加模版，并填写审核通过后的模板ID。<br>\n" +
            "{{first.DATA}}\n" +
            "内容：{{keyword1.DATA}}\n" +
            "时间：{{keyword2.DATA}}\n" +
            "{{remark.DATA}}",
          20:"请在“微信公众号-模板消息”中按照以下格式添加模版，并填写审核通过后的模板ID。<br>\n" +
            "{{first.DATA}}\n" +
            "内容：{{keyword1.DATA}}\n" +
            "时间：{{keyword2.DATA}}\n" +
            "{{remark.DATA}}",
          21:"请在“微信公众号-模板消息”中按照以下格式添加模版，并填写审核通过后的模板ID。<br>\n" +
            "{{first.DATA}}\n" +
            "内容：{{keyword1.DATA}}\n" +
            "时间：{{keyword2.DATA}}\n" +
            "{{remark.DATA}}",
          22:"请在“微信公众号-模板消息”中按照以下格式添加模版，并填写审核通过后的模板ID。<br>\n" +
            "{{first.DATA}}\n" +
            "用户名：{{keyword1.DATA}}\n" +
            "原因：{{keyword2.DATA}}\n" +
            "时间：{{keyword3.DATA}}\n" +
            "{{remark.DATA}}",
          23:"请在“微信公众号-模板消息”中按照以下格式添加模版，并填写审核通过后的模板ID。<br>\n" +
            "{{first.DATA}}\n" +
            "用户名：{{keyword1.DATA}}\n" +
            "时间：{{keyword2.DATA}}\n" +
            "{{remark.DATA}}",
          24:"请在“微信公众号-模板消息”中按照以下格式添加模版，并填写审核通过后的模板ID。<br>\n" +
            "{{first.DATA}}\n" +
            "用户名：{{keyword1.DATA}}\n" +
            "原角色：{{keyword2.DATA}}\n" +
            "新角色：{{keyword2.DATA}}\n" +
            "{{remark.DATA}}",
        },   //微信通知提示语
        wxNoticeCon:''        //微信配置ID
      }
    },
    components: {
      Card,
      CardRow
    },
    created() {
      this.query = this.$route.query;
      this.noticeConfigure();
    },
    methods: {
      noticeConfigure() {   //初始化配置列表信息
        this.appFetch({
          url: 'noticeConfigure',
          method: 'get',
          splice: this.query.id,
          data: {}
        }).then(res => {
          this.noticeTitle = res.readdata._data.title;  //用户角色通知标题
          this.noticeContent = res.readdata._data.content; //用户通知内容
          this.wxNoticeCon = res.readdata._data.template_id; //微信模板ID
        })
      },
      Submission() {     //提交按钮
        let attributes = {};

        if (this.query.type === 'system'){
          attributes = {
            'attributes':{
              "title": this.noticeTitle,
              "content": this.noticeContent
            }
          }
        } else if (this.query.type === 'wx'){
          attributes = {
            'attributes':{
              "template_id": this.wxNoticeCon
            }
          }
        }

        this.appFetch({
          url: 'notification',
          method: 'patch',
          splice: this.query.id,
          data: {
            "data": attributes
          }
      }).then(res=>{
        if (res.errors) {
            this.$message.error(res.errors[0].code);
          } else {
            this.$message({
              message: '提交成功',
              type: 'success'
          });
          this.noticeConfigure();
        }
      })
      }
    }
}
