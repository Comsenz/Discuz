import Button from "./Button";
import LoginModal from './LoginModal';

export default {

    setup() {
        const login = function(e) {
            console.log(e, this);
            app.modal.show(LoginModal);
        }

        return { login };
    },
    render() {
        return (<ul className="Header-controls">
            <li><Button onClick={this.login.bind(this)} className={"Button Button--link"}>登录</Button></li>
            <li><Button className={"Button Button--link"}>注册</Button></li>
        </ul>);
    }
}
