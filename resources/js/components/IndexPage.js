import Button from './Button';
import { reactive, ref, createVNode } from 'vue';
import DiscussionList from "./DiscussionList";
import Loading from './Loading';


export default {
    setup() {
        let discussions = ref([]);
        app.store.find('threads').then(result => {
            discussions.value = result;
        });

        return { discussions };
    },
    render() {
        console.log(this.discussions);
        return (
            <div className="IndexPage">
                <div className="container">
                    <DiscussionList discussions={this.discussions} />
                </div>
            </div>
        );
    },
}
