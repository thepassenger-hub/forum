import Thread from './Thread';
import Reply from './Reply';

class ThreadWithReplies extends Thread{
    constructor(data){
        super(data);
        this.replies = this.createReplies(data.replies);
    }

    createReplies(replies) {
        let repliesCollection = [];
        replies.forEach(reply => {
            repliesCollection.push(new Reply(reply));
        });
        return repliesCollection;
    }
}

export default ThreadWithReplies;

