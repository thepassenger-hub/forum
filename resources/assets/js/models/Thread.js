import Reply from './Reply';

class Thread {
    constructor(data){
        for (let field in data) {
            this[field] = data[field];
        }
    }
};

export default Thread;
