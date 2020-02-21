import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
    data: function () {
        return {
            noticeTitle: '',   //用户角色通知标题
            noticeContent: '',  //用户通知内容
            query: '',            //获取当前用户的ID
        }
    },
    components: {
        Card,
        CardRow
    },
    created() {
        this.query = this.$route.query;
        console.log(this.id, '是ID')
        this.noticeConfigure()
    },
    methods: {
        noticeConfigure() {   //初始化配置列表信息
            this.appFetch({
                url: 'noticeConfigure',
                method: 'get',
                splice: this.query.id,
                data: {}
            }).then(res => {
                console.log(res, '是我的通知列表啊')
                this.noticeTitle = res.readdata._data.title  //用户角色通知标题
                this.noticeContent = res.readdata._data.content //用户通知内容
            })
        },
        Submission() {     //提交按钮
            this.appFetch({
                url: 'notification',
                method: 'patch',
                splice: this.query.id,
                data: {
                    "data": {
                        "attributes": {
                            "title": this.noticeTitle,
                            "content": this.noticeContent
                        }
                    }
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