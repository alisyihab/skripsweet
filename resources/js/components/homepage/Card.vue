<template>
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>
                            <router-link :to="{ name: 'customers.data' }">
                                Pelanggan
                            </router-link>
                        </h4>
                    </div>
                    <div class="card-body">
                        {{ totData.customer }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                    <i class="fas fa-luggage-cart"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Transaksi</h4>
                    </div>
                    <div class="card-body">
                        {{ totData.orders }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Pendapatan</h4>
                    </div>
                    <div class="card-body">
                        {{ totData.income | currency('Rp.', '2', { spaceBetweenAmountAndSymbol: true }) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Pengeluaran</h4>
                    </div>
                    <div class="card-body">
                        {{ totData.expanse | currency('Rp.', '2', { spaceBetweenAmountAndSymbol: true }) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import $axios from "../../api";

    export default {
        data() {
            return {
                totData: [],
            }
        },
        mounted() {
           totData: {
               $axios.get(`data`).then(response => {
                this.totData = response.data;
                })
                .catch(error => {
                    console.log(error)
              })
           }
        }
    }
</script>