<template>
    <div>
        <div class="col-md-12" v-show="$can('create transaction')">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Customer Info</h4>
                            <hr>
                            <div class="profile-widget pt-4">
                                <div class="profile-widget-header">
                                    <img alt="image"
                                         :src="'/storage/users/' + transaction.customer.photo"
                                         class="rounded-circle profile-widget-picture"
                                    />
                                    <div class="profile-widget-items">
                                        <div class="profile-widget-item">
                                            <div class="profile-widget-item-label">Deposit</div>
                                            <div class="profile-widget-item-value">
                                                {{ transaction.customer.deposit |
                                                currency('Rp.', '2', { spaceBetweenAmountAndSymbol: true })
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="profile-widget-description pb-0">
                                    <div class="profile-widget-name">
                                        {{ transaction.customer.name }}
                                        <div class="text-muted d-inline font-weight-normal">
                                            <div class="slash"></div>
                                            {{ transaction.customer.nik }}
                                        </div>
                                    </div>
                                    <hr>
                                    <p>
                                        <b>Alamat Rumah : </b> <span v-html="transaction.customer.address"></span>
                                    </p>
                                    <p>
                                        <b>No Telpone:</b> {{ transaction.customer.phone }}
                                    </p>
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
                                            <button class="btn btn-primary" @click="accept">Terima</button>
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
        components: { ExpandableImage }
    }
</script>
