<template>
      <TheFooter>
        <div>
          <!--footer-->
          <a href="/">{{$store.state.config.app_name}}</a>
          <span class="ml-1">&copy; {{this.year}} {{$store.state.config.creator}}.</span>
        </div>
        <div class="ml-auto">
          <span class="mr-1">Powered by</span>
          <a href="https://coreui.io">{{$store.state.config.company}}</a>
        </div>
      </TheFooter>
</template>
<script>
import { Footer as TheFooter } from '@coreui/vue'
export default {
  name: 'DefaultFooter',
  components: {
    TheFooter
  },
    computed: {
        year() {
            var y = 2019;
            var d = new Date();
            if(d.getFullYear() != y){
                return y +"-"+ d.getFullYear()
            }else{
                return y;
            }
        },
    },
}
</script>
