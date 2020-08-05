<template>
    <main class="auth">
      <header id="auth-header" class="auth-header" 
       :style="{'background-image': 'url(' + require('../../../public/assets/img/img.png') + ')'}">
        <h1>
          <img :src="'/images/logo-whitebig.png'" alt="logo" width="200px">
          <span class="sr-only">Sign In</span>
        </h1>
      </header>
      <form class="auth-form" method="post">
        
        <div class="form-group">
          <div class="form-label-group">
            <input 
                v-model="data.email"
                type="text" 
                class="form-control" 
                :class="{'is-invalid': errors.email}" 
                autocomplete="off"> 
            <label for="inputUser">Email</label>
          </div>
            <div class="invalid-feedback" v-if="errors.email">
                <i class="fa fa-exclamation-circle fa-fw"></i> 
                {{ errors.email[0] }} 
            </div>
        </div>

        <div class="form-group">
          <div class="form-label-group">
            <input type="password" 
                v-model="data.password" 
                class="form-control" :class="{'is-invalid': errors.password}" 
                placeholder="Password"> 
            <label for="inputPassword">Password</label>
          </div>
          <p class="text-danger" v-if="errors.password">{{ errors.password[0] }}</p>
        </div>
        
        <div class="alert alert-danger" v-if="errors.invalid">{{ errors.invalid }}</div>

        <div class="form-group text-right">
            <router-link :to="{ name: 'reset-password' }">
                lupa password?
            </router-link>
        </div>

        <div class="form-group">
          <button class="btn btn-lg btn-primary btn-block" type="submit" @click.prevent="postLogin">
              Masuk
          </button>
        </div>
      </form>

      <footer class="auth-footer"> 
          Copyright &copy; {{ new Date().getFullYear() }}. Laundry  <br>
        <p class="text-center">
           Made with <i class="fas fa-heart" style="color:red"></i> By
           <a href="https://instagram.com/alibelucky" target="_blank"> alibelucky</a>
        </p> 
      </footer>
    </main>
</template>

<script>
    import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';

    export default {
        data() {
            return {
                data: {
                    email: '',
                    password: '',
                    remember_me: false
                }
            }
        },
        created() {
            if (this.isAuth) {
                this.$router.push({name: 'home'})
            }
        },
        computed: {
            ...mapGetters(['isAuth']),
            ...mapState(['errors'])
        },
        methods: {
            ...mapActions('auth', ['submit']),
            ...mapActions('user', ['getUserLogin']),
            ...mapMutations(['CLEAR_ERRORS']),
            postLogin() {
                this.submit(this.data).then(() => {
                    if (this.isAuth) {
                        this.CLEAR_ERRORS();
                        this.$router.push({name: 'home'})
                    }
                })
            }
        },
        watch: {
            $route: {
                immediate: true,
                handler(to, from) {
                    document.title = to.meta.title || 'Laundry - Login';
                }
            }
        },
        destroyed() {
            this.getUserLogin()
        }
    }
</script>
