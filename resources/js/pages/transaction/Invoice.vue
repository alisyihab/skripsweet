<template>
    <div class="invoice">
        <div class="invoice-print" id="printMe">
            <div class="row">
                <div class="col-lg-12">
                    <div class="invoice-title">
                      <h2>Invoice</h2>
                      <div class="invoice-number">#{{ transaction.invoice }}</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <address>
                                <strong>Pelanggan:</strong><br><br>
                                 <b>{{ transaction.customer.name }}</b>
                                <span v-html="transaction.customer.address"></span>
                            </address>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <address>
                                <strong>Kasir:</strong><br><br>
                               <b>{{ transaction.user ? transaction.user.name:'' }}</b><br><br>
                                Apt. 4B<br>
                                Bogor Barat, Indonesia
                            </address>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <address>
                                <strong>Metode Pembayaran:</strong><br>
                                {{ transaction.payment.type_label }}
                            </address>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <address>
                                <strong>Tanggal Laundry:</strong><br>
                                {{ transaction.created_at | formatDate }}<br><br>
                            </address>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <h2 class="section-title">Item</h2>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-md">
                            <tbody>
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Berat / Satuan</th>
                                <th class="text-right">Total</th>
                            </tr>
                            <tr v-for="(row, index) in transaction.detail" :key="index">
                                <td>{{ row.product.name }}</td>
                                <td class="text-center"> 
                                    {{ row.price | currency('Rp. ', '2', { spaceBetweenAmountAndSymbol: true }) }} 
                                / {{ row.product.unit_type }}</td>
                                <td class="text-center">{{ row.qty }} ({{ row.product.unit_type == 'Kilogram' ? 'gram':'Potong'}})</td>
                                <td class="text-right">
                                        {{ row.subtotal | currency('Rp', '2', { spaceBetweenAmountAndSymbol: true }) }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-8">
                            <p class="text-muted">
                                Tunjukan kepada kasir sebagai bukti pembayaran
                            </p>
                        </div>
                        <div class="col-lg-4 text-right">
                            <div class="invoice-detail-item">
                                <div class="invoice-detail-name">Subtotal</div>
                                <div class="invoice-detail-value">{{ transaction.amount | currency('Rp', '2', { spaceBetweenAmountAndSymbol: true }) }}</div>
                            </div>
                            <hr class="mt-2 mb-2">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="text-md-right">
            <button class="btn btn-warning btn-icon icon-left" @click="print">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapState, mapMutations } from 'vuex';
    import Vue2Filters from 'vue2-filters'
    import Vue from 'vue'
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
            'https://unpkg.com/kidlat-css/css/kidlat.css',
        ]
    }

    const moment = require('moment')
    require('moment/locale/id')

    Vue.use(Vue2Filters);

    Vue.filter('formatDate', function(value) {
        if (value) {
            return moment(String(value)).format('Do MMMM, YYYY')
        }
    });
    
    export default {
        created() {
            this.detailTransaction(this.$route.params.id)
        },
        data() {
            return {}
        },
        computed: {
            ...mapState('transaction', {
                transaction: state => state.transaction
            }),
        },
        methods: {
            ...mapActions('transaction', ['detailTransaction']),
            print() {
                this.$htmlToPaper('printMe');
            }
        }
    }
</script>