<template>
    <div id="app">
        <section class="section">
            <div class="d-flex flex-wrap align-items-stretch">
                <div class="col-lg-4 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-white">
                    <div class="p-4 m-3">
                        <img :src="'/assets/img/logo.png'" alt="logo" width="100"
                             class="shadow-light rounded-circle mb-5 mt-2">
                        <h4 class="text-dark font-weight-normal">Welcome to <span class="font-weight-bold">Laundry</span>
                        </h4>
                            <div class="form-group has-feedback" :class="{'has-error': errors.email}">
                                <label for="email">No Hp Or Email</label>
                                <input
                                    id="email"
                                    v-model="data.phone"
                                    class="form-control"
                                    name="phone"
                                    tabindex="1"
                                    required
                                    autofocus>
                                 <p class="text-danger" v-if="errors.email">{{ errors.email[0] }}</p>
                            </div>

                            <div class="form-group has-feedback" :class="{'has-error': errors.password}">
                                <div class="d-block">
                                    <label for="password" class="control-label">Password</label>
                                </div>
                                <input
                                    v-model="data.password"
                                    id="password"
                                    type="password"
                                    class="form-control"
                                    name="password"
                                    tabindex="2"
                                    required
                                >
                                <p class="text-danger" v-if="errors.password">{{ errors.password[0] }}</p>
                            </div>
                            <div class="alert alert-danger" v-if="errors.invalid">{{ errors.invalid }}</div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                        name="remember"
                                        class="custom-control-input" tabindex="3"
                                        id="remember-me"
                                        v-model="data.remember_me">
                                    <label class="custom-control-label" for="remember-me">Remember Me</label>
                                </div>
                            </div>

                            <div class="form-group text-right">
                                <router-link :to="{name: 'reset-password'}" class="float-left mt-3">
                                    Lupa password?
                                </router-link>
                                <button type="submit" class="btn btn-primary btn-lg btn-icon icon-right"
                                    @click.prevent="postLogin" tabindex="4">
                                    Login
                                </button>
                            </div>

                        <div class="text-center mt-5 text-small">
                            Copyright &copy; {{ new Date().getFullYear() }} Laundry. Made with 💙 by 
                            <a href="https://instagram.com">alibelucky</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-12 order-lg-2 order-1 min-vh-100 position-relative overlay-gradient-bottom"
                     :style="{'background-image': 'url(' + require('../../../public/assets/img/drawkit/drawkit-full-stack-man-colour.svg') + ')'}">
                     <div class="absolute-bottom-left index-2">
                        <div class="text-light p-5 pb-2">
                        <div class="mb-5 pb-3">
                            <h1 class="mb-2 display-4 font-weight-bold" style="color: black">Hello There!</h1>
                            Created by <a class="text-light bb" target="_blank" href="https://intagram.com/alibelucky">alibelucky</a>
                        </div>
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
                    phone: '',
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
                        // location.reload();
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
