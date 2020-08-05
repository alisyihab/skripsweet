<template>
  <div class="section-block">
    <span v-for="(row, index) in notifications" :key="index">
      <h2 class="section-title"> {{ index | formatDate }} </h2>
      <ul class="timeline">
        <li class="timeline-item" v-for="(row, index) in notifications" :key="index">
          <div class="timeline-figure">
            <span class="tile tile-circle tile-sm">
                <i class="far fa-calendar-alt fa-lg"></i>
            </span>
          </div>
          <div class="timeline-body">
            <div class="media">
              <div class="media-body">
                <h6 class="timeline-heading">
                  <a href="#" class="text-link">Management Meeting - W3 evaluation</a>
                </h6>
                <p class="mb-0">
                  <a href="#">Jeffrey Wells</a> created a schedule </p>
                <p class="timeline-date d-sm-none"> About a minute ago </p>
              </div>
              <div class="d-none d-sm-block">
                <span class="timeline-date">About a minute ago</span>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </span>
  </div>
</template>

<script>
import $axios from "../../api";
import moment from 'moment'
import InfiniteLoading from 'vue-infinite-loading';

export default {
  data() {
    return {
      notifications: [],
      // page: 1
    }
  },
  // methods: {
  //     infiniteHandler($state) {
  //         $axios.get(`list-notif`, {
  //             params: {
  //                 page: this.page,
  //             },
  //         }).then(({ data }) => {
  //             if (data.result.data.length) {
  //                 this.page += 1;
  //                 this.notifications.push(...data.result.data);
  //                 $state.loaded();
  //             } else {
  //                 $state.complete();
  //             }
  //         })
  //     }
  // },
  // filters: {
  //     formatDate(val) {
  //         return moment(new Date(val)).fromNow()
  //     }
  // },
  // components: {
  //     InfiniteLoading
  // }
  mounted() {
    notifications: {
      $axios.get(`list-notif`).then((response) => {
        this.notifications = response.data.result;
      }).catch((error) => {
        console.log(error)
      })
    }
  },
  filters: {
      formatDate(val) {
          return moment(new Date(val)).calendar()
      }
  },
}
</script>