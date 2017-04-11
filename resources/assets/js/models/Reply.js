
class Reply {
    constructor(data){
        this.id = data.id;
        this.body = data.body;
        this.createdAt = data.created_at;
        this.updatedAt = data.updated_at;
        this.creator = data.creator.username;
    }
}

export default Reply;