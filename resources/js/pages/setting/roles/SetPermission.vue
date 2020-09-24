<template>
  <div class="row">
    <div class="col-md-5">
      <div class="card">
        <div class="card-header">
          <h6>Assign Role to User</h6>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label>Role</label>
            <select class="form-control" v-model="role_user.role">
              <option value="">Pilih</option>
              <option v-for="(row, index) in roles" :value="row.name" :key="index">{{ row.name }}</option>
            </select>
            <p class="text-danger" v-if="errors.role_id">{{ errors.role_id[0] }}</p>
          </div>
          <div class="form-group">
            <label>User</label>
            <select class="form-control" v-model="role_user.user_id">
              <option value="">Pilih</option>
              <option v-for="(row, index) in users" :value="row.id" :key="index">{{ row.name }} ({{row.email}})</option>
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" @click="setRole" :disabled="loading">
              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="loading"></span>
              Set Role
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-7">
      <div class="card">
        <div class="card-header">
          <h6>Set Permission</h6>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label>Role</label>
            <select class="form-control" v-model="role_selected">
              <option value="">Pilih</option>
              <option v-for="(row, index) in roles" :value="row.id" :key="index">{{ row.name }}</option>
            </select>
            <p class="text-danger" v-if="errors.role_id">{{ errors.role_id[0] }}</p>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" @click="checkPermission" :disabled="loading">
              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="loading"></span>
              Cek
            </button>
          </div>
          <div class="form-group">
            <div class="list-group list-group-flush list-group-bordered">
              <div class="list-group-header"> Set Hak Akses</div>
              <template>
                <label v-for="(row, index) in permissions" :key="index"
                       class="list-group-item custom-control custom-checkbox mb-0">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    :key="index"
                    :value="row.name"
                    :checked="role_permission.findIndex(x => x.name == row.name) !== -1"
                    @click="addPermission(row.name)"
                  >
                  <span class="custom-control-label">{{ row.name }}</span>
                </label>
              </template>
            </div>
          </div>
          <div class="float-right">
            <button class="btn btn-primary" @click="setPermission">
              <i class="fas fa-paper-plane"></i> Set Permission
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import {mapActions, mapState, mapMutations} from 'vuex'

  export default {
    name: 'SetPermission',
    data() {
      return {
        role_user: {
          role: '',
          user_id: ''
        },
        role_selected: '',
        new_permission: [],
        loading: false,
      }
    },
    created() {
      this.getRoles();
      this.getAllPermission();
      this.getUserLists()
    },
    computed: {
      ...mapState(['errors']),
      ...mapState('user', {
        users: state => state.users,
        roles: state => state.roles,
        permissions: state => state.permissions,
        role_permission: state => state.role_permission
      })
    },
    methods: {
      ...mapActions('user', [
        'getUserLists',
        'getRoles',
        'getAllPermission',
        'getRolePermission',
        'setRolePermission',
        'setRoleUser'
      ]),
      ...mapMutations('user', ['CLEAR_ROLE_PERMISSION']),
      setRole() {
        this.loading = true;
        this.setRoleUser(this.role_user).then(() => {
          this.role_user = {
            role: '',
            user_id: ''
          };
          this.$swal.fire(
            'Success!',
            'Hak role telah diberikan.',
            'success'
          );
          setTimeout(() => {
            this.loading = false;
          })
        })
      },
      addPermission(name) {
        let index = this.new_permission.findIndex(x => x === name);
        if (index === -1) {
          this.new_permission.push(name)
        } else {
          this.new_permission.splice(index, 1)
        }
      },
      checkPermission() {
        this.loading = true;
        this.getRolePermission(this.role_selected).then(() => {
          this.loading = false;
          this.new_permission = this.role_permission
        })
      },
      setPermission() {
        this.setRolePermission({
          role_id: this.role_selected,
          permissions: this.new_permission
        }).then((res) => {
          if (res.status === 'success') {
            this.$swal.fire(
              'Success!',
              'Hak akses telah diberikan.',
              'success'
            );
            setTimeout(() => {
              this.role_selected = '';
              this.new_permission = [];
              this.loading = false;
              this.alert_permission = false;
              this.CLEAR_ROLE_PERMISSION()
            }, 3000)
          }
        })
      }
    }
  }
</script>

<style type="text/css">
  .tab-pane {
    height: 150px;
    overflow-y: scroll;
  }
</style>