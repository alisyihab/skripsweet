<template>
  <div>
    <div class="card">
          <div class="card-header bg-light text-left">
            <i class="fa fa-fw fa-circle text-red"></i> <i class="fa fa-fw fa-circle text-yellow"></i> <i class="fa fa-fw fa-circle text-teal"></i>
          </div>
          <div class="card-body">
            <h1 class="state-header display-1 font-weight-bold">
              <span>4</span> <i class="far fa-frown text-red"></i> <span>4</span>
            </h1>
            <h3> Page not found! </h3>
            <p class="state-description lead"> Sorry, we've misplaced that URL or it's pointing to something that doesn't exist. </p>
            <div class="state-action">
              <router-link to="/" class="btn btn-lg btn-light"><i class="fa fa-angle-left"></i> Go Home</router-link>
            </div>
          </div>
        </div>
  </div>
</template>

<script>
export default {}
</script>