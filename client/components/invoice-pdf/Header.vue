<template>
 <!-- TITLE -->
  <div class="d-flex justify-content-center mb-5">
    <div class="d-flex flex-row justify-content-around" style="width:95%">
        <div class="d-flex flex-column text-center">
          <div><img :src="selfParent.logo" style="width:110px" crossorigin="anonymous" crossOrigin="anonymous"></div>
          <div class="fs-b-10">{{selfParent.subtitle}}</div>  
        </div>                        
    
        <div class="d-flex align-items-center flex-column text-center" style="max-width:600px"> 
          <div class="fs-b-25">{{selfParent.title}}</div>    
          <div v-html="selfParent.address"></div>   
        </div>                   
    </div> 
  </div>
 <!-- TITLE -->
</template>

<script>
export default {
	props : ["selfParent"],
}
</script>

<style scoped>
  /* border */
  .b-1{
    border: 1px solid black !important;
  }

  /* border bottom */
  .bb-1{
    border-bottom: 1px solid black !important;
  }

  /* border top */
  .bt-1{
    border-top: 1px solid black !important;
  }

  /* border radius */
  .brs-10{
    border-radius: 10px !important;
  }

  /* font-size */
  .fs-15{
    font-size: 15px !important;
  }

  /* border bottom none */
  .bb-n{
    border-bottom:0px solid white !important;    
  } 

  /* border top none */
  .bt-n{
    border-top:0px solid white !important;
  }

  /* font size 10 and font weight */
  .fs-b-10{
    font-size: 10px;
    font-weight: bold;
  }

  /* font size 25 and font weight */
  .fs-b-25{
    font-size: 25px;
    font-weight: bold;
  }

  /* font size 15 and fonr weight */
  .fs-b-15{
    font-size: 15px;
    font-weight: bold;
  }

  /*  border 0 */
  .b-0{
    border:0px white !important;
  }                

  #detail-payment > tbody > tr > td > ul > li ,
  #detail-payment > tbody > tr > td > ul{
    list-style-type:none;
    list-style-position:none;
    list-style:none;
    padding:0
  }

  #detail-payment > tbody > tr > td > ul > li {
    border-bottom:2px dotted black
  }     

  .detail-payment-total{
    border:1px solid black;
    padding:5px;
    border-radius:10px;
    padding-left:50px;
    padding-right:50px;
    display:inline-block;
  } 

   #detail-invoice > thead > tr > th {
    border:1px solid black !important;
    border-bottom:0px solid black !important;
  }

  #detail-invoice > tbody > tr > td{
    border:0.5px solid black !important;
    border-color: black !important;
  }

  *{
    color:  black !important;
  }
</style>