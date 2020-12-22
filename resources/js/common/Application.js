import routes from './routes';
import { mountApp, mount }  from './mount';
import Store from './Store';
import Thread from '../models/Thread';
import Forum from '../models/Forum';
import Post from '../models/Post';
import User from '../models/User';
import HeaderSecondary from "../components/HeaderSecondary";
import Categorie from '../models/Categorie';
import { reactive } from 'vue';
import ModalManage from '../components/ModalManage';

export default class Application {
    payload;
    main;
    router;
    forum = {};
    store = new Store(reactive({
        forums: Forum,
        threads: Thread,
        posts: Post,
        categories: Categorie,
        users: User
    }));
    constructor() {
        routes(this);
    }
    load(payload) {
        this.payload = payload;

        this.store.pushPayload({data: this.payload.resources});

        this.forum = this.store.getById('forums', 1);
    }
    boot() {
        this.mount();
    }

    mount() {
        mountApp('#content', this);

        mount('#header-secondary', HeaderSecondary);
        this.modal = mount('#modal', ModalManage);
    }

    request(originalOptions) {
        const options = Object.assign({}, originalOptions);
        const url = options.url;
        delete options.url;

        options.headers = originalOptions.headers || {};

        options.headers['Authorization'] = 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIiLCJqdGkiOiJlZDcwZjVjOTU5ZWI2Y2E4Nzk0YzNmMzZiMzA4MDMzNWRhYzhhMGJiODQ2NmM2ODQxNDBmMjQ5ODU4YmQ4ODBjODZkYTlkY2IxMDRmMTdiZSIsImlhdCI6MTYwODUzOTM4MiwibmJmIjoxNjA4NTM5MzgyLCJleHAiOjE2MTExMzEzODIsInN1YiI6IjEiLCJzY29wZXMiOltudWxsXX0.LqoLipB7CgKdbN_s2wSv_UHdgpZb4GgIEzH2r5zE0nBPbeEFUo4FqyJq7lJVvKMs_t2LZ78ya9JNLGKSZdCRX8SrN6PPpxV2UzYCejdGF7Fv9aAPKz8BmG5GVcR9lA-nQiPVZ--N1UMpRhToEArZcOUg7vk-jKPGzcXMQDlNJdYKghRXo7Fd8Bme8hmbq3ymXIlFa3_fJFmjIr3M_1qfti30OqJWBkfs-HxBtu4TXIObCqOyB2ZU_12NpExnC74Dp5sjFKRXl30UYqe-9awHkvCN3iWotXjGhaWk7Uz2Tm8Yk8tiK5Dpoqv3kAP0lvbcEop_ULzapo6VkADi2Mgeew';
        return fetch(url, options).then(result => result.json());
    }
}
