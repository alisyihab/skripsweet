<template>
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Homepage</h1>
            </div>

            <card-status v-if="authenticated.role == 0 || authenticated.role == 1 " />

            <div class="section-body">
                <div class="row">
                    <chart-expense v-if="authenticated.role == 0 || authenticated.role == 2 " />
                    <profile v-if="authenticated.role == 3"/>
                    <last-trans />

                    <chart-trans v-if="authenticated.role == 0 || authenticated.role == 1 " />
                </div>
            </div>
        </section>
    </div>
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

    export default {
        computed: {
            ...mapState('user', {
                authenticated: state => state.authenticated
            }),
            ...mapState(['token']),
        },
        components: {
            mixins: [
                Vue2Filters.mixin
            ],
            'card-status': Card,
            'last-trans': LastTrans,
            'chart-expense': ExpenseChart,
            'chart-trans': TransactionChart,
            'profile': Profile

        },
    }
</script>

<style>
    .content {
        min-height: 0;
    }
</style>