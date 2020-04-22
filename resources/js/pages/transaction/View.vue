<template>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6" v-if="transaction.status == 0">
                        <h4>Payment</h4>
                        <hr>
                        <div class="form-group">
                            <label>Tagihan</label>
                            <input type="text"
                                   :value="transaction.amount | currency('IDR', '2', { spaceBetweenAmountAndSymbol: true })"
                                   class="form-control"
                                   readonly />
                        </div>
                        <div class="form-group">
                            <label>Jumlah Bayar</label>
                            <money type="tel"
                                class="form-control"
                                v-model.lazy="amount"
                                v-bind="money"/>
                        </div>
                        <div class="form-group"
                             v-if="transaction.customer && transaction.customer.deposit >= transaction.amount">
                            <input type="checkbox" v-model="via_deposit"> Bayar Via Deposit?
                        </div>
                        <p v-if="isCustomerChange">
                            Kembalian:
                            {{ customerChangeAmount | currency('IDR', '2', { spaceBetweenAmountAndSymbol: true }) }}
                        </p>
                        <div class="form-group" v-if="isCustomerChange">
                            <input type="checkbox" v-model="customer_change" id="customer_change">
                            <label for="customer_change"> Kembalian Jadi Deposit?</label>
                        </div>
                        <p class="text-danger" v-if="payment_message">{{ payment_message }}</p>
                        <button class="btn btn-primary btn-sm" :disabled="loading" @click="makePayment">Bayar</button>
                    </div>
                    <div class="col-md-6" v-if="transaction.customer">
                        <h4>Customer Info</h4>
                        <hr>
                        <table>
                            <tr>
                                <th width="30%">NIK </th>
                                <td width="5%">:</td>
                                <td>{{ transaction.customer.nik }}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td>:</td>
                                <td>{{ transaction.customer.name }}</td>
                            </tr>
                            <tr>
                                <th>No Telp </th>
                                <td>:</td>
                                <td>{{ transaction.customer.phone }}</td>
                            </tr>
                            <tr>
                                <th>Alamat </th>
                                <td>:</td>
                                <td>{{ transaction.customer.address }}</td>
                            </tr>
                            <tr>
                                <th>Deposit </th>
                                <td>:</td>
                                <td>
                                    {{ transaction.customer.deposit | currency('IDR', '2', { spaceBetweenAmountAndSymbol: true }) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Point </th>
                                <td>:</td>
                                <td>{{ transaction.customer.point }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6" v-if="transaction.payment" id="printMe">
                        <h4>Riwayat Pembayaran</h4>
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
                                    <td>{{ row.qty }} ({{ row.product.unit_types == 'Kilogram' ? 'gram':'Potong'}})</td>
                                    <td>
                                         {{ row.price | currency('IDR', '2', { spaceBetweenAmountAndSymbol: true }) }}
                                        / {{ row.product.unit_types }}
                                    </td>
                                    <td>
                                        {{ row.subtotal | currency('IDR', '2', { spaceBetweenAmountAndSymbol: true }) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        <hr>
                        <table class="table" style="widht:50%; border:none;">
                             <tr>
                                <th>Total Belanja </th>
                                <td>:</td>
                                <td class="text-left">
                                    {{ transaction.amount | currency('IDR', '2', { spaceBetweenAmountAndSymbol: true }) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Total Pembayaran </th>
                                <td>:</td>
                                <td>
                                    <div class="text-left">
                                        {{ transaction.payment.amount | currency('IDR', '2', { spaceBetweenAmountAndSymbol: true }) }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Metode Pembayaran </th>
                                <td>:</td>
                                <td class="text-left">{{ transaction.payment.type_label }}</td>
                            </tr>
                            <hr>
                            <tr>
                                <th>Kembalian</th>
                                <td>:</td>
                                <td class="text-left">
                                    {{ transaction.payment.customer_change | currency('IDR', '2', { spaceBetweenAmountAndSymbol: true })}}
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="pull-right" style="padding-top:50px; padding-right:50px">
                        <button @click="print" class="btn btn-info btn-sm">
                            <i class="fa fa-print"></i>Print
                        </button>
                    </div>

                    <div class="col-md-12" style="padding-top: 20px">
                        <div class="alert alert-success" v-if="payment_success">Pembayaran Berhasil</div>

                        <h4>Detail Transaction</h4>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Paket</th>
                                        <th width="28%">Waktu Layanan</th>
                                        <th>Berat/Satuan</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(row, index) in transaction.detail" :key="index">
                                        <td>
                                            <strong>{{ row.product.name }}</strong>
                                            <sup v-html="row.status_label"></sup>
                                        </td>
                                        <td>{{ row.service_time }}</td>
                                        <td>
                                            {{ row.qty }} ({{ row.product.unit_types == 'Kilogram' ? 'gram':'Potong'}})
                                        </td>
                                        <td>
                                            {{ row.price | currency('IDR', '2', { spaceBetweenAmountAndSymbol: true }) }}
                                            / {{ row.product.unit_types }}
                                        </td>
                                        <td>
                                            {{ row.subtotal | currency('IDR', '2', { spaceBetweenAmountAndSymbol: true }) }}
                                        </td>
                                        <td>
                                            <button class="btn btn-success btn-sm"
                                                    v-if="transaction.status == 1  && row.status == 0"
                                                    @click="isDone(row.id)">
                                                <i class="fa fa-paper-plane-o"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { mapActions, mapState, mapMutations } from 'vuex'
    import Vue2Filters from 'vue2-filters'
    import Vue from 'vue'
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
                payment_success: false,
                via_deposit: false,
                money: {
                    decimal: ',',
                    thousands: '.',
                    prefix: 'IDR ',
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
            isCustomerChange() {
                if(!this.via_deposit) {
                    return this.amount > this.transaction.amount
                }
                return false
            },
            customerChangeAmount() {
                if (!this.via_deposit) {
                    return parseInt(this.amount - this.transaction.amount)
                }
                return 0
            }
        },
        methods: {
            ...mapActions('transaction', ['detailTransaction', 'payment', 'completeItem']),
            makePayment() {
                if (this.amount < this.transaction.amount) {
                    this.payment_message = 'Pembayaran Kurang Dari Tagihan'
                    return
                }
                this.loading = true
                this.payment({
                    transaction_id: this.$route.params.id,
                    amount: this.amount,
                    customer_change: this.customer_change,
                    via_deposit: this.via_deposit
                }).then((res) => {
                    if (res.status == 'success') {
                        this.payment_success = true
                        setTimeout(() => {
                            this.loading = false
                            this.amount = null
                            this.customer_change = false
                            this.payment_message = null
                            this.via_deposit = false
                        }, 500)
                        this.detailTransaction(this.$route.params.id)
                    } else {
                        this.loading = false
                        alert(res.data)
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
                        this.completeItem({ id: id }).then(() => {
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
