<template>
    <div>
        <section class="section">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <div class="login-brand">
                            <img :src="'/stisla/img/stisla-fill.svg'" alt="logo" width="100"
                                 class="shadow-light rounded-circle">
                        </div>
                        <div class="card card-primary">
                            <div class="card-header"><h4>Login</h4></div>
                            <div class="card-body">
                                <form  class="needs-validation" novalidate="">
                                    <div class="form-group has-feedback" :class="{'has-error': errors.email}">
                                        <label for="email">Email</label>
                                        <input aria-describedby="emailHelpBlock"
                                           id="email"
                                           type="email"
                                           class="form-control"
                                           placeholder="Registered email address"
                                           v-model="data.email"
                                           autofocus>
                                        <p class="text-danger" v-if="errors.email">{{ errors.email[0] }}</p>
                                    </div>
                                    <div class="alert alert-danger" v-if="errors.invalid">{{ errors.invalid }}</div>
                                    <div class="form-group has-feedback" :class="{'has-error': errors.password}">
                                        <div class="d-block">
                                            <label for="password" class="control-label">Password</label>
                                            <div class="float-right">
                                                <a href="#" class="text-small">
                                                    Forgot Password?
                                                </a>
                                            </div>
                                        </div>
                                        <input aria-describedby="passwordHelpBlock"
                                           id="password"
                                           type="password"
                                           placeholder="Your account password"
                                           class="form-control"
                                           v-model="data.password"
                                           tabindex="2">
                                         <p class="text-danger" v-if="errors.password">{{ errors.password[0] }}</p>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox"
                                                   class="custom-control-input"
                                                   v-model="data.remember_me"
                                                   tabindex="3" id="remember-me">
                                            <label class="custom-control-label" for="remember-me">Remember Me</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit"
                                                class="btn btn-primary btn-lg btn-block"
                                                tabindex="4"
                                                @click.prevent="postLogin">
                                            Login
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="simple-footer">
                            Copyright &copy; Laundry {{ new Date().getFullYear() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
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
            toggleBodyClass(addRemoveClass, className) {
                const el = document.body;

                if (addRemoveClass === 'addClass') {
                    el.classList.add(className);
                } else {
                    el.classList.remove(className);
                }
            },
            postLogin() {
                this.submit(this.data).then(() => {
                    if (this.isAuth) {
                        this.CLEAR_ERRORS();
                        this.$router.push({name: 'home'})
                    }
                })
            }
        },
        destroyed() {
            this.getUserLogin();
            this.toggleBodyClass('removeClass', 'layout-3');
        }
    }
</script>
