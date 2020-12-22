import Button from './Button';
import { reactive } from 'vue';
export default {
    className: 'Modal--small',
    setup() {
        const state = reactive({username: '', password: ''});
        const login = function() {
            app.request({
                url: app.forum.attribute('apiUrl') + '/login',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    data: {
                        attributes: {
                            username: this.username,
                            password: this.password
                        }
                    }
                })
            }).then(result => result.json()).then(data => {
                console.log(data);
            });
            return false;
        };
        return { ...state, login };
    },
    render() {
        return (
            <div className="Modal-content">
                <div className="Modal-close App-backControl">
                    <Button className="Button Button--icon Button--link" />
                </div>
                <form onsubmit={this.login.bind(this)}>
                    <div className="Modal-header">
                        <h3 className="App-titleControl App-titleControl--text">登录</h3>
                    </div>
                    <div className="Modal-body">
                    <div class="Form Form--centered">
                        <div class="Form-group">
                            <input class="FormControl" name="username" type="text" placeholder="用户名" oninput={e => this.username = e.target.value} />
                        </div>
                        <div class="Form-group">
                            <input class="FormControl" name="password" type="password" placeholder="密码" oninput={e => this.password = e.target.value} />
                        </div>
                        <div class="Form-group">
                            <label class="checkbox"><input type="checkbox" />记住密码</label>
                        </div>
                        <div class="Form-group">
                            <button class="Button Button--primary Button--block" type="submit" title="登录"><span class="Button-label">登录</span></button>
                        </div>
                        </div>
                    </div>
                    <div className="Modal-footer">
                        <p className="LogInModal-forgotPassword">
                            <a href="lksjdf">asdg</a>
                        </p>
                    </div>
                </form>
            </div>
        );
    }
}
