<template>
<div>
     <div class="container">
      <home_navbar></home_navbar>



    </div>
            <home_filter @filterApplied="filterApplied"></home_filter>

</div>
   
    
</template>

<script>
export default {



    methods:{
        filterApplied:function(filter){
            
            console.log(filter)
        }
    },
    components:{
    }
    
}
</script>
<style scoped>

</style>