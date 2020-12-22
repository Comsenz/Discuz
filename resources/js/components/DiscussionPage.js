import { ref } from 'vue';
import PostStream from './PostStream';
import Loading from './Loading';

export default {
    props: ['id'],
    setup({ id }) {
        const discussion = ref(null);

        // if(app.payload.apiDocument) {
        //     discussion.value = app.store.pushPayload(app.payload.apiDocument);
        // } else {
            app.store.find('threads', id, {
                include: 'firstPost.likedUsers'
            }).then(result => discussion.value = result);
        // }



        return { discussion }
    },
    render() {
        const discussion = this.discussion;
        app.aaa = discussion;
        console.log(discussion);
        return (
            <div className="DiscussionPage">
                <div className="DiscussionPage-discussion">
                    <div className="container">
                        {discussion ? <div className="DiscussionPage-stream">
                            <PostStream discussion={discussion} />
                        </div> : <Loading />}
                    </div>
                </div>
            </div>
        );
    }
}
