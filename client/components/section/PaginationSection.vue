<template>
  <div class="clearfix mt-3" v-if="self.isPaginate">
    <div class="float-left">
      Showing {{ from }} to {{ to }} of {{ total }} records
    </div>
    <div class="float-right">
      <ul class="pagination" >
          <li v-bind:class="['page-item',active_page < 5 ? 'disabled':'enabled']">
            <a class="page-link" @click="privousPage()" tabindex="-1"><i class="fas fa-chevron-left"></i></a>
          </li>

          <li  v-for="i in pages" :key=i
               v-bind:class="['page-item',{active : i == current_page}]"
               @click="self.onLoad(i)"><a class="page-link page">{{ i }}</a></li>
          <li v-if="total_page > 5" class="page-item disabled" > <a class="page-link" href="#">...Of</a> </li>


          <li v-if="total_page > 5" class="page-item disabled"><a class="page-link" href="#"> {{ total_page }}</a>  </li>
          <li v-bind:class="['page-item',{disabled:disable_next_page}]">
            <a class="page-link" @click="nextPage()"><i class="fas fa-chevron-right"></i></a>
          </li>
        </ul>
    </div>
  </div>
</template>

<script>

import { mapState} from 'vuex'
export default {
  props: {
    self: Object,
  },

  data () {
    return {
      pages : [],
      click_counter : 1,
      disable_next_page : false,
      active_page : '',
      end_page : '',
    }
  },
  created() {
    this.generatePage()

  },
  computed : {
    ...mapState('pagination',{
      from         : state => state.from,
      to           : state => state.to,
      total_page   : state => state.total_page,
      current_page : state => state.current_page,
      total        : state => state.total,
      per_page     : state => state.per_page,
    }),

  },
  methods: {
    generatePage(){
      this.pages     = [];
      var page_count = [];
      var total_pages = this.total_page  > 5 ? 5 : this.total_page;

      for(var i = 0;i <= total_pages -1 ;i++){
         page_count.push(i+1);

      }

      this.pages         = page_count;
      this.click_counter = 1;
      this.total_page > 5 ? this.disable_next_page = false : this.disable_next_page = true;
    },
     nextPage(){
        var counter    = this.click_counter +1 ;
        var start_page = (counter * 5) - 5;
        this.end_page   = this.total_page;
        var page_count = [];
        var elements   = this.$el.querySelectorAll('.page-link.page');
        this.active_page = start_page+1;
         for (var i = 0, len = elements.length; i < len; i++) {
             if (i < 5) {
               start_page = start_page + 1;
               elements[i].text = start_page;
               page_count.push(start_page);
             }
            }
       this.pages       = page_count;
       this.end_page == this.total_page ? this.disable_next_page = true : this.disable_next_page = false;
       this.click_counter = this.click_counter + 1;
     },

     privousPage(){
        var counter    = this.click_counter - 1;
        var start_page = (counter * 5) - 5;
        var page_count = [];
        var elements   = this.$el.querySelectorAll('.page-link.page');
        this.active_page = start_page +1;
         for (var i = 0, len = elements.length; i < len; i++) {
             if (i <= 5) start_page = start_page + 1;
             elements[i].text = start_page;
             page_count.push(start_page);
             this.end_page  = start_page + 1;
            }

        this.pages       = page_count;
        this.end_page < this.total_page ? this.disable_next_page = false : this.disable_next_page = true;
        this.click_counter = this.click_counter - 1;
    },

 },
};
</script>
