<template>
  <div class="col-lg-8 col-md-12 col-12 col-sm-12">
    <div class="card card-fluid">
      <div class="card-body text-center">
        <span class="user-avatar user-avatar-xl my-3">
          <img :src="'/storage/users/' + authenticated.photo" alt="">
        </span>
        <h3 class="card-title text-truncate">
          <span>{{ authenticated.name }}</span>
        </h3>
        <h6 class="card-subtitle text-muted mb-3"><b>No Telpone:</b> {{ authenticated.phone }} </h6>
        <p>
          <b>Alamat Rumah : </b> <span v-html="authenticated.address"></span>
        </p>
        <p class="text-muted">
          Terimakasih telah menggunakan jasa kami
          <i class="far fa-smile-wink" style="font-size: 28px"></i>
        </p>
        <p class="text center">
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
        </p>
      </div>
      <div class="card-footer">
        <div class="card-footer-item card-footer-item-bordered">
          <div class="metric">
            <h6 class="metric-value"> Laundry </h6>
            <p class="metric-label">
              {{ trans.data }}
            </p>
          </div>
        </div>
        <div class="card-footer-item card-footer-item-bordered">
          <div class="metric">
            <h6 class="metric-value"> Deposit </h6>
            <p class="metric-label">
              {{ authenticated.deposit | currency('Rp.', '2', {spaceBetweenAmountAndSymbol: true}) }}
            </p>
          </div>
        </div>
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