<template>
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6" v-if="transaction.status == 0">
          <h4>Payment</h4>
          <hr>
          <div class="form-group">
            <label>Tagihan</label>
            <input 
              type="text"
              :value="transaction.amount | currency('Rp', '2', { spaceBetweenAmountAndSymbol: true })"
              class="form-control"
             readonly
            />
          </div>
          <div class="form-group">
            <label>Jumlah Bayar</label>
            <input 
              type="number"
              class="form-control"
              v-model="amount"/>
          </div>
          <div class="form-group" v-if="authenticated.role == 3">
            <label>Bukti Transfer</label>
            <input type="file" class="form-control" accept="image/*" @change="uploadImage($event)" id="file-input">
            <p class="text-medium text-black mt-3">
              Transfer ke <b>No.Rek : 0009213xxx</b>
            </p>
            <div id="preview">
              <img class="img-responsive" v-if="url" :src="url"/>
            </div>
          </div>
          <div class="form-group"
               v-show="authenticated.role != 3"
               v-if="transaction.customer && transaction.customer.deposit >= transaction.amount">
            <input type="checkbox" v-model="via_deposit" id="deposit">
            <label for="deposit">Bayar Via Deposit?</label>
          </div>
          <p v-if="isCustomerChange" v-show="authenticated.role != 3">
            Kembalian:
            {{ customerChangeAmount | currency('IDR', '2', { spaceBetweenAmountAndSymbol: true }) }}
          </p>
          <div class="form-group" v-if="isCustomerChange && authenticated.role != 3">
            <input type="checkbox" v-model="customer_change" id="customer_change">
            <label for="customer_change"> Kembalian Jadi Deposit?</label>
          </div>
          <button class="btn btn-primary" :disabled="loading" @click="makePayment">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="loading"></span>
            Bayar
          </button>
        </div>
        <div class="col-md-6" v-if="transaction.customer">
          <h6>Customer Info</h6>
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
        <div class="col-md-6" v-if="transaction.payment" id="printMe">
          <h6>Riwayat Pembayaran</h6>
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
                    {{ transaction.payment.amount | currency('IDR', '2', { spaceBetweenAmountAndSymbol: true }) }}
                  </div>
                </td>
              </tr>
              <tr>
                <th>Metode Pembayaran</th>
                <td>:</td>
                <td class="text-left">
                    <span v-if="transaction.payment.type == 'false'">
                      Cash
                    </span>
                  <span v-else>
                    Deposit
                  </span>
                </td>
              </tr>
              <hr>
              <tr v-if="transaction.payment.type == 'false'">
                <th>Kembalian</th>
                <td>:</td>
                <td class="text-left">
                  {{ transaction.payment.customer_change | currency('IDR', '2', { spaceBetweenAmountAndSymbol: true })}}
                </td>
              </tr>
            </table>
          </div>
        </div>
        
        <div class="col-md-12" style="padding-top: 20px" v-if="transaction.payment">
          <h5>Detail Laundry</h5>
          <hr>
          <div class="table-responsive">
            <table class="table table-hover table-bordered">
              <thead>
              <tr>
                <th class="text-center" width="25%">Paket</th>
                <th class="text-center" width="28%">Cucian Selesai</th>
                <th class="text-center">Berat/Satuan</th>
                <th class="text-center">Harga</th>
                <th class="text-center">Subtotal</th>
                <th class="text-center" v-if="$can('create transaction')">
                  Aksi
                </th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(row, index) in transaction.detail" :key="index">
                <td>
                  <h6>
                    <strong>{{ row.product.name }}</strong>
                    <sup v-html="row.status_label"></sup>
                  </h6>
                </td>
                <td class="text-center" v-if="row.status == 0">
                  {{ row.end_date | formatDate }}
                </td>
                <td v-else class="text-center">
                  <span class="badge badge-success">Selesai</span>
                </td>
                <td>
                  {{ row.qty }} ({{ row.product.unit_type == 'Kilogram' ? 'gram':'Potong'}})
                </td>
                <td>
                  <p class="text-small">
                    {{ row.price | currency('Rp', '2', { spaceBetweenAmountAndSymbol: true }) }}
                    / {{ row.product.unit_type == 'Kilogram' ? 'kg':'pcs'}}
                  </p>
                </td>
                <td>
                  <p class="text-small">
                    {{ row.subtotal | currency('Rp', '2', { spaceBetweenAmountAndSymbol: true }) }}
                  </p>
                </td>
                <td v-if="$can('create transaction')">
                  <div class="dropdown">
                    <button type="button" class="btn btn-icon btn-light" data-toggle="dropdown" aria-expanded="false"><i
                      class="fa fa-ellipsis-v"></i></button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <div class="dropdown-arrow"></div>
                      <button
                        class="dropdown-item"
                        v-show="$can('create transaction')"
                        v-if="transaction.status == 2  && row.status == 0"
                        @click="isDone(row.id)">
                        Selesaikan Transaksi
                      </button>
                      <button @click="print" class="dropdown-item">
                        Print
                      </button>
                    </div>
                  </div>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import {mapActions, mapState, mapMutations} from 'vuex'
  import Vue2Filters from 'vue2-filters'
  import Vue from 'vue'
  import moment from 'moment'

  import {Money} from 'v-money'
  import VueHtmlToPaper from 'vue-html-to-paper';

  const options = {
    name: '_blank',
    specs: [
      'fullscreen=yes',
      'titlebar=yes',
      'scrollbars=yes'
    ],
    styles: [
      'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
      'https://lubinar.id/files/style.css',
      'https://unpkg.com/kidlat-css/css/kidlat.css'
    ]
  }

  Vue.use(VueHtmlToPaper, options);
  Vue.use(Vue2Filters);

  export default {
    name: 'DetailTransaction',
    created() {
      this.detailTransaction(this.$route.params.id)
    },
    data() {
      return {
        amount: null,
        customer_change: false,
        loading: false,
        payment_message: null,
        via_deposit: false,
        photo: '',
        url: null,
        money: {
          decimal: ',',
          thousands: '.',
          prefix: 'Rp. ',
          suffix: '',
          precision: 0,
          masked: false /* doesn't work with directive */
        }
      }
    },
    computed: {
      ...mapState('transaction', {
        transaction: state => state.transaction
      }),
      ...mapState('user', {
        authenticated: state => state.authenticated
      }),
      isCustomerChange() {
        if (!this.via_deposit) {
          return this.amount > this.transaction.amount
        }
        return false
      },
      customerChangeAmount() {
        if (!this.via_deposit) {
          return parseInt(this.amount - this.transaction.amount)
        }
        return 0
      },
    },
    filters: {
      formatDate(val) {
        return moment(new Date(val)).calendar();
      }
    },
    methods: {
      ...mapActions('transaction', ['detailTransaction', 'payment', 'completeItem']),
      onFileChange(event) {
        const file = event.target.files[0];
        this.url = URL.createObjectURL(file);
      },
      uploadImage(event) {
        this.photo = event.target.files[0];
        this.url = URL.createObjectURL(this.photo)
      },
      makePayment() {
        if (this.amount == null) {
          this.$swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Anda belum memasukan nominal.',
          });
          return
        }

        if (this.amount < this.transaction.amount) {
          this.$swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Pembayaran kurang dari tagihan.',
          });
          // this.payment_message = 'Pembayaran Kurang Dari Tagihan'
          return
        }
        this.loading = true

        let form = new FormData();
        form.append('transaction_id', this.$route.params.id);
        form.append('amount', this.amount);
        form.append('customer_change', this.customer_change);
        form.append('via_deposit', this.via_deposit);
        form.append('photo', this.photo);

        this.payment(form).then((res) => {
          if (res.status == 'success') {
            this.$swal.fire(
              'Success!',
              'Pembayaran berhasil.',
              'success'
            );
            setTimeout(() => {
              this.loading = false
              this.amount = null
              this.customer_change = false
              this.payment_message = null
              this.via_deposit = false
            }, 500)
            this.detailTransaction(this.$route.params.id)
          } else if (res.status == 'berhasil') {
            this.$swal.fire({
              icon: 'info',
              showCancelButton: false,
              focusConfirm: false,
              title: 'Sukses',
              text: res.pesan
            });
            setTimeout(() => {
              this.loading = false
              this.amount = null
              this.customer_change = false
              this.payment_message = null
              this.via_deposit = false
            }, 500)
            this.$router.push({ name: 'transactions.list' })
          } else {
            this.loading = false
            this.$swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: res.data,
            });
          }
        })
      },
      isDone(id) {
        this.$swal({
          title: 'Kamu Yakin?',
          text: "Akan menyelesaikan pesanan ini!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Iya, Lanjutkan!'
        }).then((result) => {
          if (result.value) {
            this.completeItem({id: id}).then(() => {
              this.$swal.fire(
                'Success!',
                'Transaksi selesai.',
                'success'
              );
              this.detailTransaction(this.$route.params.id)
            })
          }
        })
      },
      print() {
        this.$htmlToPaper('printMe');
      }
    },
    components: {
      mixins: [Vue2Filters.mixin],
      Money
    },
  }
</script>

<style>
  @media print {
    @page {
      size: 58mm;
    }
  }
</style>
