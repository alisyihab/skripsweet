<template>
  <div>
    <div class="col-md-12" v-show="$can('create transaction')">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4>Customer Info</h4>
              <hr>
              <div class="card card-fluid">
                <div class="card-body text-center">
                    <span class="user-avatar user-avatar-xl my-3">
                        <img :src="'/storage/users/' + transaction.customer.photo" alt="">
                    </span>
                  <h3 class="card-title text-truncate">
                    <span>{{ transaction.customer.name }}</span>
                  </h3>
                  <h6 class="card-subtitle text-muted mb-3"><b>No Telpone:</b> {{ transaction.customer.phone }} </h6>
                  <p>
                    <b>Alamat Rumah : </b> <span v-html="transaction.customer.address"></span>
                  </p>
                </div>
                <div class="card-footer">
                  <div class="card-footer-item card-footer-item-bordered">
                    <div class="metric">
                      <h6 class="metric-value"> Deposit </h6>
                      <p class="metric-label">
                        {{ transaction.customer.deposit | currency('Rp.', '2', { spaceBetweenAmountAndSymbol: true }) }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <h4>Pembayaran</h4>
              <hr>
              <div class="table-responsive">
                <table class="table">
                  <thead>
                  <tr>
                    <th>Item</th>
                    <th>qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(row, index) in transaction.detail" :key="index">
                    <td><strong>{{ row.product.name }}</strong></td>
                    <td>{{ row.qty }} ({{ row.product.unit_type == 'Kilogram' ? 'gram':'Pcs'}})</td>
                    <td>
                      {{ row.price | currency('Rp', '2', { spaceBetweenAmountAndSymbol: true }) }}
                      / {{ row.product.unit_type }}
                    </td>
                    <td>
                      {{ row.subtotal | currency('Rp', '2', { spaceBetweenAmountAndSymbol: true }) }}
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
              <hr>
              <div class="table-responsive">
                <table class="table" style="widht:50%; border:none;">
                  <tr>
                    <th>Total Belanja</th>
                    <td>:</td>
                    <td class="text-left">
                      {{ transaction.amount | currency('IDR', '2', { spaceBetweenAmountAndSymbol: true }) }}
                    </td>
                  </tr>
                  <tr>
                    <th>Total Pembayaran</th>
                    <td>:</td>
                    <td>
                      <div class="text-left">
                        {{ transaction.payment.amount | currency('IDR', '2', { spaceBetweenAmountAndSymbol: true
                        }) }}
                      </div>
                    </td>
                  </tr>
                  <hr>
                  <tr>
                    <th>Bukti Transfer</th>
                    <td>:</td>
                    <td class="text-left">
                      <expandable-image
                        class="image"
                        :src="'/storage/transaction/' + transaction.payment.photo"
                        alt="img"
                        :title="transaction.payment.photo"
                      />
                    </td>
                  </tr>
                  <tr>
                    <th>
                      <button class="btn btn-primary" @click="accept" v-if="transaction.status == 1">Terima</button>
                    </th>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
  import {mapActions, mapState, mapMutations} from 'vuex';
  import ExpandableImage from '../../components/ExpandableImage';

  export default {
    created() {
      this.detailTransaction(this.$route.params.id)
    },
    computed: {
      ...mapState('transaction', {
        transaction: state => state.transaction
      }),
    },
    methods: {
      ...mapActions('transaction', ['detailTransaction', 'acceptPayment']),
      accept() {
        this.$swal({
          title: 'Kamu Yakin?',
          text: "Permintaan yang disetujui tidak dapat dikembalikan!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Iya, Lanjutkan!'
        }).then((result) => {
          if (result.value) {
            this.$swal.fire(
              'Success!',
              'Pembayaran telah disetujui.',
              'success'
            );
            this.acceptPayment(this.$route.params.id).then(() => this.$router.push({name: 'transactions.list'}))
          }
        })
      },
    },
    components: {ExpandableImage}
  }
</script>
