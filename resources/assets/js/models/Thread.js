
class Thread {
    constructor(data){
        this.id = data.id;
        this.title = data.title;
        this.description = data.description;
        this.description = data.description;
        this.body = data.body;
        this.createdAt = data.created_at;
        this.updatedAt = data.updated_at;
        this.creator = data.creator.username;
    }
};

export default Thread;
