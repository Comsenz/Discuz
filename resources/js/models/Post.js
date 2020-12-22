import Model from "../common/Model";

export default class Post extends Model {
    // apiEndpoint() {
    //     return super.apiEndpoint()+'?include=likedUsers';
    // }
}

Object.assign(Post.prototype, {
    id() {
        return this.data.id;
    },
    contentHtml: Model.attribute('contentHtml'),
    summaryText: Model.attribute('summaryText'),
    isLiked: Model.attribute('isLiked'),
    likedUsers: Model.hasMany('likedUsers')
});
