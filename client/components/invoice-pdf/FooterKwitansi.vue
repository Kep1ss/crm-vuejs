<template>
  <div class="d-flex w-100 justify-content-end">              
     <div style="width:160px">
      <div class="p-2 brs-10 b-1">
        <table class="w-100">
          <tr class="bb-1 fs-15 text-center">
            <td>{{selfParent.today}}</td>
          </tr>
          <tr class="bb-1 fs-15 text-center">
            <td>Pimpinan</td>
          </tr>
          <tr>
            <td style="height:70px"></td>
          </tr>
          <tr>
            <td class="bt-1 text-center"><b>{{selfParent.leader}}</b></td>
          </tr>
        </table>
      </div>
     </div>
  </div>
</template>

<script>
export default {
  props : ["selfParent"]
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