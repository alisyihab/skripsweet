<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-6">
        <div class="card card-default">
          <div class="card-header">Reset Password</div>
          <div class="card-body">
            <form autocomplete="off" @submit.prevent="requestResetPassword" method="post">
              <div class="form-group">
                  <label for="email">E-mail</label>
                  <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
              </div>
              <button type="submit" class="btn btn-primary">Send Password Reset Link</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
</template>

<script>
    import $axios from "../api";

    export default {
        data() {
            return {
                email: null,
                has_error: false
            }
        },
        methods: {
            requestResetPassword() {
                $axios.post("reset-password", {email: this.email}).then(result => {
                    this.response = result.data;
                    this.$swal.fire(
                      'Success!',
                      'link reset password berhasil dikirim ke ' + this.email,
                      'success'
                    );
                }, error => {
                    console.error(error);
                });
            }
        }    
    }
</script>