/**
 * 付费站点-已支付-未登录控制器
 */
import Header from '../../../view/m_site/common/headerView';
import browserDb from '../../../../../helpers/webDbHelper';
import PayMethod from '../../../view/m_site/common/pay/paymentMethodView';
export default {
    data: function () {
        return {
            headOpeShow: false,
            isfixNav: false,
            current: 0,
            siteInfo: false,
            siteUsername: '',  //站长
            joinedAt: '',    //加入时间
            sitePrice: '',   //加入价格
            username: '',   //当前用户名
            loading: false,  //是否处于加载状态
            finished: false, //是否已加载完所有数据
            isLoading: false, //是否处于下拉刷新状态
            pageIndex: 1,//页码
            pageLimit: 20,
            offset: 100, //滚动条与底部距离小于 offset 时触发load事件
            loginUserInfo: '',
            sitePrice: '',      //付费站点价钱
            siteExpire: '',     //到期时间
            orderSn: '',        //订单号
            wxPayHref: '',      //微信支付链接
            qrcodeShow: false,  //pc端显示二维码
            codeUrl: "",        //支付url，base64
            amountNum: '',      //支付价钱
            payStatus: false,   //支付状态
            payStatusNum: 0,    //支付状态次数
            authorityList: '',  //权限列表
            tokenId: '',        //用户ID
            dialogShow: false,  //微信支付确认弹框
            groupId: '',        //用户组ID
            limitList: [],      //用户组权限
            payList: [
                {
                    name: '钱包',
                    icon: 'icon-wallet'
                }
            ],     //支付方式
            show: false,        //是否显示支付方式
            errorInfo: '',      //密码错误提示
            value: '',          //密码
            walletBalance: '',  //钱包余额
            walletStatus: '',    //钱包支付密码状态
            payLoading: false,
        }
    },
    components: {
        Header,
        PayMethod
    },
    created() {
        this.getInfo();
        this.getUsers();

    },
    methods: {
        //请求站点信息，用于判断站点是否是付费站点
        getInfo(initStatus = false) {
            return this.appFetch({
                url: 'forum',
                method: 'get',
                data: {
                    include: ['users'],
                }
            }).then((res) => {
                if (res.errors) {
                    this.$toast.fail(res.errors[0].code);
                    throw new Error(res.error)
                } else {
                    if (initStatus) {
                        this.siteInfo = []
                    }
                    this.siteInfo = res.readdata;
                    if (res.readdata._data.set_site.site_author) {
                        this.siteUsername = res.readdata._data.set_site.site_author.username;;
                    } else {
                        this.siteUsername = '暂无站长信息';
                    }
                    this.sitePrice = res.readdata._data.set_site.site_price;
                    if (res.readdata._data.paycenter.wxpay_close == true) {
                        this.payList.unshift({
                            name: '微信支付',
                            icon: 'icon-wxpay'
                        })
                    }
                }
            });
        },
        //退出登录
        signOut() {
            browserDb.removeLItem('tokenId');
            browserDb.removeLItem('Authorization');
            this.$router.push({ path: '/login-user' });
            localStorage.clear();
        },

        getUsersInfo() {
            this.appFetch({
                url: 'users',
                method: 'get',
                splice: '/' + browserDb.getLItem('tokenId'),
                data: {
                    include: ['groups']
                }
            }).then(res => {
                if (res.errors) {
                    this.$toast.fail(res.errors[0].code);
                    this.value = '';
                } else {
                    this.payStatus = res.readdata._data.paid;
                    this.payStatusNum = +1;
                    if (this.payStatus) {
                        this.qrcodeShow = false;
                        this.show = false;
                        this.payLoading = false;
                        this.$router.push('/');
                        this.payStatusNum = 11;
                        // clearInterval(time);
                    }
                }
            }).catch(err => {
            })
        },

        onBridgeReady(data) {
            let that = this;
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest', {
                "appId": data.data.attributes.wechat_js.appId,     //公众号名称，由商户传入
                "timeStamp": data.data.attributes.wechat_js.timeStamp,         //时间戳，自1970年以来的秒数
                "nonceStr": data.data.attributes.wechat_js.nonceStr, //随机串
                "package": data.data.attributes.wechat_js.package,
                "signType": "MD5",         //微信签名方式：
                "paySign": data.data.attributes.wechat_js.paySign //微信签名
            })
            const payWechat = setInterval(() => {
                if (this.payStatus == '1' || this.payStatusNum > 10) {
                    clearInterval(payWechat);
                }
                this.getOrderStatus();
            }, 3000)

        },

        //付费，获得成员权限
        payImmediatelyClick(data) {
            //data返回选中项
            let isWeixin = this.appCommonH.isWeixin().isWeixin;
            let isPhone = this.appCommonH.isWeixin().isPhone;
            if (data.name === '微信支付') {
                this.show = false;
                if (isWeixin) {
                    //微信
                    this.getOrderSn().then(() => {
                        this.orderPay(12).then((res) => {
                            if (typeof WeixinJSBridge == "undefined") {
                                if (document.addEventListener) {
                                    document.addEventListener('WeixinJSBridgeReady', this.onBridgeReady(res), false);
                                } else if (document.attachEvent) {
                                    document.attachEvent('WeixinJSBridgeReady', this.onBridgeReady(res));
                                    document.attachEvent('onWeixinJSBridgeReady', this.onBridgeReady(res));
                                }
                            } else {
                                this.onBridgeReady(res);
                            }
                        })
                    });
                } else if (isPhone) {
                    //手机浏览器
                    this.getOrderSn().then(() => {
                        this.orderPay(11).then((res) => {
                            console.log(res)
                            this.wxPayHref = res.readdata._data.wechat_h5_link;
                            window.location.href = this.wxPayHref;
                            const payPhone = setInterval(() => {
                                if (this.payStatus && this.payStatusNum > 10) {
                                    clearInterval(payPhone);
                                }
                                this.getOrderStatus()
                            }, 3000)
                        })
                    });
                } else {
                    //pc
                    this.getOrderSn().then(() => {
                        this.orderPay(10).then((res) => {
                            this.codeUrl = res.readdata._data.wechat_qrcode;
                            this.qrcodeShow = true;
                            const pay = setInterval(() => {
                                if (this.payStatus && this.payStatusNum > 10) {
                                    clearInterval(pay);
                                }
                                this.getOrderStatus()
                            }, 3000)
                        })
                    });
                }
            }

        },
        onInput(key) {
            this.value = this.value + key;
            if (this.value.length === 6) {
                this.errorInfo = '';
                this.getOrderSn().then(() => {
									this.orderPay(20, this.value).then((res) => {
										if (res.errors) {
										} else {
											const pay = setInterval(() => {
												if (this.payStatus && this.payStatusNum > 10) {
														clearInterval(pay);
												}
												this.getUsersInfo()
											}, 3000)
										}
										
									})
                })
            }
        },
        //删除
        onDelete() {
            this.value = this.value.slice(0, this.value.length - 1);
        },
        //关闭
        onClose() {
            this.value = '';
            this.errorInfo = '';
            this.payLoading = false;
        },
        payClick() {
            // this.show = !this.show;
            this.show = true;
        },
        getOrderSn() {
            return this.appFetch({
                url: 'orderList',
                method: 'post',
                data: {
                    "type": 1
                }
            }).then(res => {
                this.orderSn = res.readdata._data.order_sn;
            }).catch(err => {
            })
        },
        orderPay(type, value) {
            return this.appFetch({
                url: 'orderPay',
                method: 'post',
                splice: '/' + this.orderSn,
                data: {
                    "payment_type": type,
                    'pay_password': value
                }
            }).then(res => {
                if (res.errors) {
                    this.$toast.fail(res.errors[0].code);
                    this.value = '';
                } else {
                    this.payLoading = true; 
								}
								return res;
            }).catch(err => {
            })
        },

        getOrderStatus() {
            return this.appFetch({
                url: 'order',
                method: 'get',
                splice: '/' + this.orderSn,
                data: {
                },
            }).then(res => {
                // const orderStatus = res.readdata._data.status;
                if (res.errors) {
                    this.$toast.fail(res.errors[0].code);
                    throw new Error(res.error)
                } else {
                    this.payStatus = res.readdata._data.status;
                    this.payStatusNum++;
                    if (this.payStatus == '1' || this.payStatusNum > 10) {
                        this.rewardShow = false;
                        this.qrcodeShow = false;
                        this.rewardedUsers.push({ _data: { avatarUrl: this.currentUserAvatarUrl, id: this.userId } });
                        this.payStatusNum = 11;
                        // this.detailsLoad(true);
                        clearInterval(pay);
                    }
                }

                // return res;
            })
        },

        //跳转到登录页
        loginJump: function () {
            this.$router.push({ path: 'login-user' })
        },
        //跳转到注册页
        registerJump: function () {
            this.$router.push({ path: 'sign-up' })
        },
        onRefresh() {    //下拉刷新
            this.pageIndex = 1;
            this.getInfo(true).then(() => {
                this.$toast('刷新成功');
                this.finished = false;
                this.isLoading = false;
            }).catch((err) => {
                this.$toast('刷新失败');
                this.isLoading = false;
            })
        },
        getUsers() {
            return this.appFetch({
                url: 'users',
                method: 'get',
                splice: '/' + browserDb.getLItem('tokenId'),
                data: {
                    // include:['groups']
                }
            }).then(res => {
                console.log(res)
                if (res.errors) {
                    this.$toast.fail(res.errors[0].code);
                } else {
                    this.walletBalance = res.readdata._data.walletBalance;
                    this.walletStatus = res.readdata._data.canWalletPay;
                    this.loginUserInfo = res.data.attributes.username
                }
            }).catch(err => {
            })
        }

    },

    mounted: function () {
        // this.getVote();
        window.addEventListener('scroll', this.handleTabFix, true);
    },
    beforeRouteLeave(to, from, next) {
        window.removeEventListener('scroll', this.handleTabFix, true)
        next()
    }
}
