import Model from "../common/Model";

export default class User extends Model {}

Object.assign(User.prototype, {
    id() {
        return this.data.id;
    },
    username: Model.attribute('username'),
});
