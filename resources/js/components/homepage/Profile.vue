<template>
    <div class="col-lg-8 col-md-12 col-12 col-sm-12">
        <div class="card profile-widget">
            <div class="profile-widget-header">
                <img alt="image" 
                    :src="'/storage/users/' + authenticated.photo"
                    class="rounded-circle profile-widget-picture"
                />
                <div class="profile-widget-items">
                    <div class="profile-widget-item">
                        <div class="profile-widget-item-label">Transaksi</div>
                        <div class="profile-widget-item-value">{{ trans.data }}</div>
                    </div>
                    <div class="profile-widget-item">
                        <div class="profile-widget-item-label">Point</div>
                        <div class="profile-widget-item-value">{{ authenticated.point }}</div>
                    </div>
                    <div class="profile-widget-item">
                        <div class="profile-widget-item-label">Deposit</div>
                        <div class="profile-widget-item-value">
                            {{ authenticated.deposit | currency('Rp.', '2', { spaceBetweenAmountAndSymbol: true }) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-widget-description pb-0">
                <div class="profile-widget-name">Hello
                    <div class="text-muted d-inline font-weight-normal">
                        {{ authenticated.name }}
                    </div>
                </div>
                <p>
                    Terimakasih telah bergabung dengan kami 
                    <i class="far fa-smile-wink" style="font-size: 28px"></i>
                </p>
            </div>
            <div class="card-footer text-center pt-0">
                <div class="font-weight-bold mb-2 text-small">Follow Laundry</div>
                <a href="https://facebook.com/satriani.ali" target="_blank"
                   class="btn btn-social-icon mr-1 btn-facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://github.com/alisyihab" target="_blank" class="btn btn-social-icon mr-1 btn-github">
                    <i class="fab fa-github"></i>
                </a>
                <a href="https://instagram.com/alibelucky" target="_blank"
                   class="btn btn-social-icon mr-1 btn-instagram">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import $axios from "../../api";

    export default {
        data() {
            return {
                trans: []
            }
        },
        computed: {
            ...mapState('user', {
                authenticated: state => state.authenticated
            }),
        },
        mounted() {
            trans: {
                $axios.get(`total_transaction`).then(response => {
                this.trans = response.data;
                })
                .catch(error => {
                    console.log(error)
              })
            }
        }
    }
</script>

<style>
@media only screen and (max-width: 768px) {
    .profile-widget-item-value {
        font-size: 12px !important;
    }
}
</style>