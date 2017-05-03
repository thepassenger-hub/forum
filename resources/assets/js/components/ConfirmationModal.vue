<template>
<div>
    <transition name="modal">
        <div class="modal-mask confirmation-modal">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="modal-header">
                        <h3>
                        Warning.
                        </h3>
                    </div>
                    <div class="modal-body">
                        <div class="field">
                            <slot>
                                <p class="control">
                                    Are you sure you want to delete your reply?
                                </p>
                            </slot>
                        </div>                
                    </div>

                    <div class="modal-footer">
                        <div class="field is-grouped">
                            <p class="control">
                                <button class="button is-danger" @click="$emit('delete')">Yes</button>
                            </p>
                            <p class="control" id="close-modal-button">
                                <button class="button" @click="$emit('close')">No</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</div>
</template>

<script>
    export default {

    }
</script>