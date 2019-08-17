<template>

    <span class="text-danger ">{{ displayMessage }}</span>
    
</template>


<script>
export default {
    props: ["input","validation"],

    data(){
        return{
            
        }
    },

    computed: {
        displayMessage : function(){
            
            for(let i = 0;i < this.input.length;i++){
                let tmp_input = this.input[i]
                switch (this.validation[i]) {
                    case "fullname":
                        
                        if(tmp_input.length < 8 || tmp_input.length > 20)
                            return "fullname should be between 8 and 20 characters";
                        
                        break;
                
                    default:
                        break;
                }
            }
        }
    }



}
</script>



<style scoped>

</style>
