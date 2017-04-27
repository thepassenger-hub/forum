var showNotificationsMixin = {
    methods: {
        showError(error){
            let vm = this;
            this.errorMessage = error;
            // setTimeout(function(){
            //     vm.errorMessage = false;
            // }, 10000);
        },
        showSuccess(message){
            let vm = this;
            this.successMessage = message;
            // setTimeout(function(){
            //     vm.successMessage = false;
            // }, 10000);
        }
    }
}

export default showNotificationsMixin;