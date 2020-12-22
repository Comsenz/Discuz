import DiscussionListItem from "./DiscussionListItem";

export default {
    props: ['discussions'],
    render() {
        // console.log(this.discussions, ' ------');
        const discussions = this.discussions;
        return (
            <div className="DiscussionList">
                <ul className="DiscussionList-discussions">
                    {discussions.map((discussion) => {
                        return (
                            <li>
                                <DiscussionListItem className="DiscussionListItem" discussion={discussion}/>
                            </li>
                        );
                    })}
                </ul>
            </div>
        );
    }
}
