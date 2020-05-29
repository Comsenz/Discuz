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
          29:"请在“微信公众号-模板消息”中按照以下格式添加模版，并填写审核通过后的模板ID。<br>\n" +
            "{{first.DATA}}\n" +
            "回复内容：{{keyword1.DATA}}\n" +
            "原文内容：{{keyword2.DATA}}\n" +
            "回复时间：{{keyword3.DATA}}\n" +
            "{{remark.DATA}}",
          30:"请在“微信公众号-模板消息”中按照以下格式添加模版，并填写审核通过后的模板ID。<br>\n" +
            "{{first.DATA}}\n" +
            "点赞内容：{{keyword1.DATA}}\n" +
            "点赞时间：{{keyword2.DATA}}\n" +
            "{{remark.DATA}}",
          31:"请在“微信公众号-模板消息”中按照以下格式添加模版，并填写审核通过后的模板ID。<br>\n" +
            "{{first.DATA}}\n" +
            "支付内容：{{keyword1.DATA}}\n" +
            "支付类型：{{keyword2.DATA}}\n" +
            "支付时间：{{keyword3.DATA}}\n" +
            "{{remark.DATA}}",
          32:"请在“微信公众号-模板消息”中按照以下格式添加模版，并填写审核通过后的模板ID。<br>\n" +
            "{{first.DATA}}\n" +
            "@的内容：{{keyword1.DATA}}\n" +
            "@的时间：{{keyword2.DATA}}\n" +
            "{{remark.DATA}}",
          35:"请在“微信公众号-模板消息”中按照以下格式添加模版，并填写审核通过后的模板ID。<br>\n" +
            "{{first.DATA}}\n" +
            "提现金额：{{keyword1.DATA}}\n" +
            "申请时间：{{keyword2.DATA}}\n" +
            "提现状态：{{keyword3.DATA}}\n" +
            "{{remark.DATA}}",
          36:"请在“微信公众号-模板消息”中按照以下格式添加模版，并填写审核通过后的模板ID。<br>\n" +
            "{{first.DATA}}\n" +
            "提现金额：{{keyword1.DATA}}\n" +
            "申请时间：{{keyword2.DATA}}\n" +
            "提现状态：{{keyword3.DATA}}\n" +
            "原因：{{keyword4.DATA}}\n" +
            "{{remark.DATA}}",
        },   //微信通知提示语。tpi：提示语id不同显示不同，每添加一个通知，就需要对应的添加一个提示语，id根据接口返回对应添加
        wxNoticeCon:'',        //微信配置ID
      }
    },
    components: {
      Card,
      CardRow
    },
    created() {
      this.query = this.$route.query;

      this.noticeConfigure();
      this.getNoticeList();
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
