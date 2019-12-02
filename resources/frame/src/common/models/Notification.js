import Model from '../Model';

export default class Notification extends Model {};

Object.assign(Notification.prototype, {
    detail: Model.attribute('data'),
    id:Model.attribute('id'),
    read_at:Model.attribute('read_at'),
    user_id:Model.attribute('user_id'),
    user_name:Model.attribute('data.user_name'),
    user:Model.hasOne('user'),
});