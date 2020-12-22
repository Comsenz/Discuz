import Model from "../common/Model";

export default class Threads extends Model {}

Object.assign(Threads.prototype, {
    id() {
        return this.data.id;
    },
    title: Model.attribute('title'),
    firstPost: Model.hasOne('firstPost'),
    category: Model.hasOne('category'),
    isDeleted: Model.attribute('isDeleted')
});
