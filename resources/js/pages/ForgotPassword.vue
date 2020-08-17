<template>
  <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
              <img :src="'/assets/img/logo.png'" alt="logo" width="100" class="shadow-light rounded-circle">
            </div>

            <div class="card card-primary">
              <div class="card-header">
                  <h4>Lupa Password</h4>
                </div>

              <div class="card-body">
                <p class="text-muted">We will send a link to reset your password</p>
                <form autocomplete="off" @submit.prevent="requestResetPassword" method="POST">
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control" name="email" tabindex="1"  v-model="email" required autofocus>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                     Send Password Reset Link
                    </button> <br>
                    <router-link :to="{ name: 'login' }">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </router-link>
                  </div>
                </form>
              </div>
            </div>
            <div class="simple-footer">
             Copyright &copy; {{ new Date().getFullYear() }} Laundry. Made with 💙 by 
              <a href="https://instagram.com">alibelucky</a>
            </div>
          </div>
        </div>
      </div>
    </section>
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
                  if (this.response = result.data) {
                      this.$swal.fire(
                        'Success!',
                        'link reset password berhasil dikirim ke ' + this.email,
                        'success'
                      );
                  } if (this.response = result.error) {
                    this.this.$swal.fire(
                        'Error',
                        'gagal di kirim ' + this.email,
                        'error'
                      );
                  }
                }, error => {
                    console.error(error);
                });
            }
        }    
    }
</script>