<template>
  <header class="app-header app-header-dark">
    <div class="top-bar">
      <div class="top-bar-brand">
        <button class="hamburger hamburger-squeeze mr-2" type="button" data-toggle="aside-menu" aria-label="toggle aside menu">
          <span class="hamburger-box">
      			<span class="hamburger-inner"></span>
          </span>
        </button>
        <router-link to="/">
          <img :src="'/images/logo-white.png'" alt="logo">
        </router-link>
      </div>
      
      <div class="top-bar-list">
        <div class="top-bar-item px-2 d-md-none d-lg-none d-xl-none">
          <button class="hamburger hamburger-squeeze" type="button" data-toggle="aside" aria-label="toggle menu">
            <span class="hamburger-box">
              <span class="hamburger-inner"></span>
            </span>
          </button>
        </div>
        <div class="top-bar-item top-bar-item-right px-0">
          <ul class="header-nav nav">
            <li class="nav-item dropdown header-nav-dropdown">
              <a class="nav-link has-badge" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="badge badge-pill badge-warning"
                    v-if="notifications.length > 0">
                  {{ notifications.length }}
                </span>
                <span class="oi oi-bell"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-rich dropdown-menu-right">
                <div class="dropdown-arrow"></div>
                <h6 class="dropdown-header stop-propagation">
                  <span>
                    Pemberitahuan <span class="badge">({{notifications.length}})</span>
                  </span>
                </h6>
                <div class="dropdown-scroll perfect-scrollbar">
                	<span v-for="(row, index) in notifications" :key="index">
										<a href="javascript:void(0)" @click="readNotif(row)" class="dropdown-item unread">
											<div class="user-avatar">
												<img
                        	v-if="row.data.sender_photo == null"
                          alt="image" src="https://via.placeholder.com/160"
                        />
												<img
                        	v-else
                       		alt="image" :src="'/storage/users/' + row.data.sender_photo"
                        />
                      </div>
                      <div class="dropdown-item-body">
												<p class="subject"> {{ row.data.sender_name }} </p>
												<p class="text text-truncate" v-if="row.data.expenses">
													{{ row.data.expenses.description.substr(0, 30) }}
                        </p>
                        <p class="text" v-else-if="row.data.payment">
													<span>
														{{ row.data.note }}
													</span>
                        </p>
                        <p v-else-if="row.data.transaction" class="text text-truncate">
                        	Pembayaran telah di verifikasi oleh admin.
                        	<span v-html="row.data.transaction.status_label"></span>
                        </p>
                        <p v-else-if="row.data.detailTransaction" class="text text-truncate">
                        	{{ row.data.note }}
                        </p>
												<p v-else-if="row.data.completeItem" class="text text-truncate">
                        	{{ row.data.note }}
                        </p>
                        <span class="date">{{ row.created_at | formatDate }}</span>
                      </div>
                    </a>
                  </span>
                </div>
                <router-link :to="{ name: 'notifications.data' }" class="dropdown-footer">Lihat Semua
                  <i class="fas fa-fw fa-long-arrow-alt-right"></i>
                </router-link>
              </div>
            </li>
          </ul>
          <div class="dropdown d-none d-sm-flex">
            <button class="btn-account" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="user-avatar user-avatar-md">
              	<img
                	v-if="authenticated.photo !== null"
                  alt="image"
                  :src="'/storage/users/' + authenticated.photo"
                />
                <img v-if="authenticated.photo == null" alt="image" src="https://via.placeholder.com/160" class="rounded-circle mr-1">
              </span>
              <span class="account-summary pr-lg-4 d-none d-lg-block">
              	<span class="account-name">{{ authenticated.name }}</span> 
                <span class="account-description">
									<span v-if="authenticated.role == 0">	superadmin</span>
									<span v-else-if="authenticated.role == 1"> admin </span>
									<span v-else>Pelanggan</span>
              	</span>
        			</span>
            </button> <!-- .dropdown-menu -->
            <div class="dropdown-menu">
              <div class="dropdown-arrow d-lg-none" x-arrow=""></div>
              <div class="dropdown-arrow ml-3 d-none d-lg-block"></div>
              <h6 class="dropdown-header d-none d-sm-block d-lg-none">
                {{ authenticated.name }}
              </h6>
              <a class="dropdown-item" href="javascript:void(0)" @click="logout">
                <span class="dropdown-icon oi oi-account-logout text-danger"></span>
                Logout
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
  import {mapActions, mapState} from "vuex";
  import moment from 'moment'

  export default {
    computed: {
      ...mapState('user', {
        authenticated: state => state.authenticated
      }),
      ...mapState('notification', {
        notifications: state => state.notifications
      })
    },
    filters: {
      formatDate(val) {
        return moment(new Date(val)).fromNow()
      }
    },
    methods: {
      ...mapActions('notification', ['readNotification']),
      readNotif(row) {
        if (row.data.expenses) {
          this.readNotification({id: row.id}).then(() => this.$router.push({
            name: 'expenses.view',
            params: {
              id: row.data.expenses.id
            }
          }))
        } else if (row.data.payment) {
          this.readNotification({id: row.id}).then(() => this.$router.push({
            name: 'transaction.payment',
            params: {
              id: row.data.payment.transaction_id
            }
          }))
        } else if (row.data.transaction) {
          this.readNotification({id: row.id}).then(() => this.$router.push({
            name: 'transactions.view',
            params: {
              id: row.data.transaction.id
            }
          }))
        } else if (row.data.detailTransaction) {
          this.readNotification({id: row.id}).then(() => this.$router.push({
            name: 'transactions.view',
            params: {
              id: row.data.detailTransaction.id
            }
          }))
        } else if (row.data.completeItem) {
          this.readNotification({id: row.id}).then(() => this.$router.push({
            name: 'transactions.view',
            params: {
              id: row.data.completeItem.transaction_id
            }
          }))
        }
      },
      logout() {
        return new Promise((resolve, reject) => {
					localStorage.removeItem('token');
          resolve()
        }).then(() => {
          this.$store.state.token = localStorage.getItem('token');
          this.$router.push('/login')
        })
      }
    },
  }
</script>
