<template>
  <div class="app-main">
    <main>
      <div class="wrapper">
        <div class="page">
          <div class="page-inner">
            <header class="page-title-bar">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active">
                    <router-link to="/">
                      <i class="breadcrumb-icon fa fa-angle-left mr-2"></i> dashboard
                    </router-link>
                  </li>
                </ol>
              </nav>
              <div class="d-md-flex align-items-md-start">
                <h1 class="page-title mr-sm-auto"> {{ $route.meta.title }} </h1>
              </div>
            </header>
            <div class="page-section">
              <router-view></router-view>
            </div>
          </div>
        </div>
      </div>
    </main>
    
    <div v-if="!$can('read users')">
      <page404/>
    </div>
  </div>
</template>

<script>
  import Page404 from '../../components/Page404';

  export default {
    name: 'IndexSetting',
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