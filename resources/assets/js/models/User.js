class User {
    constructor(data){
        for (let field in data) {
            this[field] = data[field];
        }
    }
};

export default User;