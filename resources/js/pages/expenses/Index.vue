<template>
  <div class="app-main">
    <main v-show="$can('read expense')">
      <div class="wrapper">
        <div class="page">
          <div class="page-inner">
            <header class="page-title-bar">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active">
                    <router-link :to="{ name: 'expenses.data' }" v-show="$route.name !== 'expenses.data' ">
                      <i class="breadcrumb-icon fa fa-angle-left mr-2"></i> Kembali
                    </router-link>
                  </li>
                </ol>
              </nav>
              <div class="d-md-flex align-items-md-start">
                <h1 class="page-title mr-sm-auto"> {{ $route.meta.title }} </h1><!-- .btn-toolbar -->
                <div class="btn-toolbar">
                  <router-link
                    :to="{ name: 'expenses.create' }"
                    v-show="$route.name == 'expenses.data'"
                    class="btn btn-light"
                  >
                    <i class="oi oi-plus"></i>
                    <span class="ml-1">Tambah Data</span>
                  </router-link>
                </div>
              </div>
            </header>
            <div class="page-section">
              <router-view></router-view>
            </div>
          </div>
        </div>
      </div>
    </main>
    <div v-if="!$can('read expense')">
      <page404/>
    </div>
  </div>
</template>

<script>
  import Page404 from '../Page404';

  export default {
    name: 'IndexExpenses',
    components: {
      'page404': Page404
    },
    watch: {
      $route: {
        immediate: true,
        handler(to, from) {
          document.title = to.meta.title;
        }
      }
    },
  }
</script>
