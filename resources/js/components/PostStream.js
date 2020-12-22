export default {
    props: ['discussion'],
    setup({ discussion }) {
        const liked = function() {

        };
        return { liked };
    },
    render() {
        const attrs = this.$attrs;
        const discussion = this.discussion;
        const isLiked = this.discussion.firstPost().isLiked();

        return (
            <div className="PostStream-item" {...attrs}>

                <div>{discussion.title()}</div>

                <div className="Post-body" v-html={discussion.firstPost().contentHtml()}></div>

                <aside class="Post-actions">
                    <ul>
                        <li class="item-like">
                            <button onclick={() => {
                                discussion.firstPost().save({isLiked: !isLiked});
console.log(123);
                                // const data = discussion.firstPost().data.relationships.likedUsers.data;
                                // console.log(data);
                                // data.some((like, i) => {
                                //   if (like.id == 1) {
                                //     data.splice(i, 1);
                                //     return true;
                                //   }
                                // });

                                // if (isLiked) {
                                //   data.unshift({type: 'users', id: 1});
                                // }

                            }} class="Button Button--link" type="button" title="点赞"><span class="Button-label">{isLiked ? '已赞' : '点赞'}</span></button>
                        </li>
                        <li class="item-reply">
                            <button class="Button Button--link" type="button" title="回复"><span class="Button-label">回复</span></button>
                        </li>
                    </ul>
                </aside>

                <footer class="Post-footer">
                    <ul>
                        <li class="item-liked">
                            <div class="Post-likedBy">{discussion.firstPost().likedUsers().map(user => {
                                    return <a href="/u/yulei111"><span class="username">{user.username()}</span></a>;
                                })} like this.
                            </div>
                        </li>
                    </ul>
                </footer>
            </div>
          );
    }
}
