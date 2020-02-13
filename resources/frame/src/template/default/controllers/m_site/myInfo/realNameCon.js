
import ChangePWDHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import browserDb from '../../../../../helpers/webDbHelper';
export default {
    data: function () {
        return {
            name: '',
            idNumber: '',
            type:'users'
        }
    },
    components: {
        ChangePWDHeader
    },
    methods: {
        subm() {
        var nameReg = /^[\u4E00-\u9FA5]{2,4}$/; //验证姓名
        var name = this.name;
        var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/; //验证身份证号码
        var idNumber = this.idNumber;
        if(!nameReg.test(name)){
            this.$toast("您输入的姓名有误，请重新输入");
            return;
           }
        if(!reg.test(idNumber)){
            this.$toast("您输入的身份证号码不合法")
            return;
        }
        if (this.name === '') {
            this.$toast("姓名不能为空");
            return;
            }
        if (this.idNumber === '') {
            this.$toast("身份证号码不能为空");
            return;
            }
        this.appFetch({
            url:'realName',
            method:'patch',
            data:{
                "data":{
                    "type":this.type,
                    "attributes": {
                        "identity": this.idNumber,
                        "realname":this.name,
                      }
                }
            }
        })
        }
    }
}
