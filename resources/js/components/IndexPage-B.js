import Button from './Button';
import { reactive, ref, createVNode } from 'vue';

export default {
    setup() {
        const firstPost = ref({});
        const threadList = ref([]);
        const tmp = ref('laksdjfl');
        let count = ref(0);

        console.log(Button);

        app.store.find('threads').then(res => threadList.value = res);

        // getList().then(res => {

        //     threadList.value = res.data;
        // });
        console.log(count);
        setInterval(() => {
            count.value++;
        }, 1000);

        return {
            firstPost,
            threadList,
            tmp,
            count
        };
    },
    render(h,a,b,c) {
        return (
        <div>
            Home
            {/* <Button className="aaa" props={{a: 'b', d: 'c'}} onclick={() => {
                console.log(123123);
            }}>bbb</Button> */}
            {/* {h(Button)} */}

            <hr />
            <div>{h.firstPost.title}</div>
            <hr />
            <input type="text" oninput={e => {
                this.firstPost.title = e.target.value;
            }}/>
            <hr />
            <div className="IndexPage">
                <ul>
                    {this.threadList.map((thread, id) => {
                        return (<li className={
                            thread.isDeleted() ? 'delete' : ''
                        }>
                            <router-link to="/discussion">
                            <div>title: {thread.title()}, id: {thread.id()} {thread.isDeleted() ? 'askldjf' : 'ffff'}</div>
                            <div v-html={'content: '+thread.firstPost().contentHtml() + 'id: '+ thread.firstPost().id()}></div>
                            <div><Button onclick={() => {
                                    thread.delete().then(res => {
                                        delete this.threadList[id];
                                    });
                                }}>del</Button>
                                <Button onclick={() => {
                                    thread.save({isDeleted: !thread.isDeleted()}).then();
                                }}>hiddle</Button></div>
                            </router-link></li>);
                    })}
                </ul>

                <div>{this.tmp}</div>
            </div>
            <hr />
            <div>{this.count}</div>
        </div>);
    },
}
