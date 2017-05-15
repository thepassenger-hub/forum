var showNotificationsMixin = {
    methods: {
        showError(error){
            let vm = this;
            this.errorMessage = error;
        },
        showSuccess(message){
            let vm = this;
            this.successMessage = message;
        }
    }
}

export default showNotificationsMixin;