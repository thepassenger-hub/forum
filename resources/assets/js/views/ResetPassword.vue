<template>
<div>
  <div>
  </div>
    <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">

          <div class="modal-header">
            <h3>
              Reset Password
            </h3>
          </div>

          <div class="modal-body">
            <div class="field">
                <p class="control">
                    <input class="input" type="email" placeholder="Email" v-model="form.email">
                </p>
            </div>

              <div class="field">
                  <p class="control">
                      <input class="input" type="password" placeholder="New password" v-model="form.password">
                  </p>
              </div>

              <div class="field">
                  <p class="control">
                      <input class="input" type="password" placeholder="Confirm password" v-model="form.password_confirmation">
                  </p>
              </div>
              
            <div>
                <transition name="fade">
                    <error v-if="errorMessage" :errorMessage="errorMessage" @close="errorMessage = false"></error>
                </transition>
                <transition name="fade">
                    <success v-if="successMessage" :successMessage="successMessage" @close="successMessage = false"></success>
                </transition>
            </div>
          </div>

          <div class="modal-footer">
              <div class="field is-grouped">
                  <p class="control">
                      <button class="button is-primary" @click="resetPassword">Reset Password</button>
                  </p>
                  <p class="control">
                      <button class="button" @click="$router.push('/')">Back</button>
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
      import Form from '../models/Form';
      import showNotificationsMixin from '../mixins/showNotificationsMixin';

      export default {
          data() {
              return {
                  form: new Form({
                      email: '',
                      password: '',
                      password_confirmation: '',
                      token: this.$route.params.token
                  }),
                  errorMessage: false,
                  successMessage: false
              }
          },
          mixins: [showNotificationsMixin],
          methods: {
              resetPassword() {
                  this.form.post('/password/reset')
                      .then(response => {
                          this.showSuccess(response.message);
                          this.$root.user = response.user;
                          this.form = new Form(this.form.originalData);
                      })
                      .catch(error => {
                          this.showError(error);
                          let data = this.form.originalData;
                          data['email'] = this.form.email;
                          this.form = new Form(data);
                      })
              }
          },
          created() {
              this.$root.path.update(this.$route.path);
          },
          components: {
              'error': require('../components/Error.vue'),
              'success': require('../components/Success.vue')
              
          }
      }
</script>