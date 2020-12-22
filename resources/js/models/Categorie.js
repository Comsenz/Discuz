import Model from "../common/Model";

export default class Categorie extends Model {}

Object.assign(Categorie.prototype, {
    name: Model.attribute('name'),
});
