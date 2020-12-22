export default {
    props: ['discussion'],
    render() {
        const discussion = this.discussion;
        return (
            <div {...this.$attrs}>
                <div className={'DiscussionListItem-content Slidable-content'}>

                    <router-link
                        to="/u/1"
                        class="DiscussionListItem-author"
                        title="title"
                    >
                        <span className="Avatar">A</span>
                    </router-link>

                    <router-link to={"/d/"+discussion.id()} class="DiscussionListItem-main">
                        <h3 className="DiscussionListItem-title">{discussion.title() ? discussion.title() : discussion.firstPost().summaryText()}</h3>

                        <ul className="DiscussionListItem-info">
                            <li className="item-tags">
                                <span className="TagsLabel">
                                    <span className="TagLabel">
                                        <span className="TagLable-text">{discussion.category().name()}</span>
                                    </span>
                                </span>
                            </li>
                            <li class="item-terminalPost">
                                <span><i class="icon fas fa-reply"></i> <span class="username">phenomlab</span> replied <time pubdate="true" datetime="2020-12-15T23:47:22+08:00" title="Tuesday, December 15, 2020 11:47 PM" data-humantime="true">3 days ago</time></span>
                            </li>
                        </ul>
                    </router-link>

                    <span class="DiscussionListItem-count" title="Mark as Read">182</span>
                </div>
            </div>
        );
    }
}
