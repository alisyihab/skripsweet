<template>
<main class="app-main">
        <!-- .wrapper -->
        <div class="wrapper">
          <!-- .empty-state -->
          <div class="empty-state">
            <!-- .empty-state-container -->
            <div class="empty-state-container">
                <page404 />
            </div><!-- /.empty-state-container -->
          </div><!-- /.empty-state -->
        </div><!-- /.wrapper -->
      </main><!-- /.app-main -->
</template>

<script>
import Page404 from "../components/Page404";

export default {
    name: "Page404",
    components: {
        'page404': Page404
    }  
}
</script>