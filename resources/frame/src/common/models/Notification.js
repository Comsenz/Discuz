import Model from '../Model';

export default class Notification extends Model {}

Object.assign(Notification.prototype, {
    detail: Model.attribute('data'),
    id:Model.attribute('id'),
    read_at:Model.attribute('read_at'),
    user_id:Model.attribute('user_id'),
    user_name:Model.attribute('data.user_name'),
    firstPost: function() {
        return {
            content: () => {return this.attribute('post_content')},
            likedUsers: function() { return []},
        };
    },
    rewardedUsers: function() {
        return [];
    },
    lastThreePosts: function() {
        return [];
    },
    postCount: function() {
        return 0;
    },
    user: function() {
        return {
            username: () => { return this.attribute('user_name')},
            createdAt:function() { return ''}
        }
    }
});