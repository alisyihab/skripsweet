<template>
    <main class="app-main">
        <div class="wrapper">
            <div class="page">
                <div class="page-inner">
                    <header class="page-title-bar">
                        <h1 class="page-title"> Dashboard </h1>
                    </header>
                    <div class="page-section">
                        <card-status v-if="authenticated.role == 0 || authenticated.role == 1 " />  
                        <div class="section-block">
                            <div class="row">
                                <chart-expense v-if="authenticated.role == 0 " />
                                <profile v-if="authenticated.role == 3"/>

                                <status v-if="authenticated.role == 1"  />

                                <last-trans />

                                <chart-trans v-if="authenticated.role == 0" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    import {mapActions, mapState} from 'vuex';
    import moment from 'moment'
    import _ from 'lodash';
    import Vue2Filters from 'vue2-filters';

    import Card from "../components/homepage/Card";
    import ExpenseChart from '../components/homepage/ExpenseChart';
    import LastTrans from "../components/homepage/LastTrans";
    import TransactionChart from '../components/homepage/TransactionChart';
    import Profile from '../components/homepage/Profile';
    import StatusExpense from '../components/homepage/statusExpense';

    export default {
        computed: {
            ...mapState('user', {
                authenticated: state => state.authenticated
            }),
            ...mapState(['token']),
        },
        watch: {
            $route: {
                immediate: true,
                handler(to, from) {
                    document.title = to.meta.title || 'Laundry - Dashboard';
                }
            }
        },
        components: {
            mixins: [
                Vue2Filters.mixin
            ],
            'card-status': Card,
            'last-trans': LastTrans,
            'chart-expense': ExpenseChart,
            'chart-trans': TransactionChart,
            'profile': Profile,
            'status': StatusExpense

        },
    }
</script>

<style>
    .content {
        min-height: 0;
    }
</style>