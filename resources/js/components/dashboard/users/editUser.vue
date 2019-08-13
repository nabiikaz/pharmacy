<template>
    <div>
            <div class="modal fade" id="modaleditUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header text-center">
            <h4 class="modal-title w-100 font-weight-bold">Edit User</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body mx-3">
            <form action="post" @submit.prevent="editUser">
            <div class="md-form mb-2">
            
                <div class="row">
                    <div class="col-6">
                        <label for="fullname" >Fullname</label>
                        <input name="fullname" type="text" id="fullname" class="form-control " 
                                    required minlength="8" maxlength="25" v-model="fullname" >
                    </div>
                    <div class="col-6">
                        <label for="birthday">Birthday</label>
                         <input name="birthday" type="date" id="birthday" class="form-control "  
                                    required v-model="birthday" >
                    
                    </div>
                </div>
            </div>

            

            <div class="md-form mb-2">
            <div class="row">
                    <div class="col-6">
                        <label for="phone" >Phone Number</label>
                 <input name="tel" type="tel" id="phone" class="form-control " 
                                    required pattern="[0-9]{9}|[0-9]{10}" v-model="tel">
                        
                    </div>
                    <div class="col-6">
                        <label for="role">Role</label>
                        <select name="role" class="form-control" id="role" v-model="role" >
                    
                    <option value="employee">Employee</option>
                    <option value="admin">Admin</option>
                </select>
                    
                    </div>
                </div>
            
            </div>

            <div class="md-form mb-3">
                        <label for="email">Email</label>
                
                 <input name="email" type="email" id="email" class="form-control " required v-model="email" >
            
            </div>

          <hr>

            <div class="md-form mb-2">
                <div class="row">
                    <div class="col-6">
                        <label for="login" >Login Username</label>
                 <input name="username" type="text" id="login" class="form-control" 
                                required minlength="8" maxlength="16" v-model="username">
                       
                    </div>
                    <div class="col-6">
                        <label for="password">Password</label>
                 <input name="password" type="password" id="password" class="form-control" 
                                required minlength="8" maxlength="16" v-model="password">
                       
                    
                    </div>
                </div>
                 
            
            </div>
            <div class="md-form mb-2">
                        
            
                 
            
            </div>

            <hr>
            <div class="d-flex justify-content-center">
             <input type="submit" class="btn btn-primary submit-btn " value="submit">

            </div>

            </form>
            
        </div>
       
        </div>
    </div>
    </div>

    </div>
</template>


<script>

export default {
      props: ['userId'],

        
        watch:{
            userId:function(val){
                 /* axios
            *
            *
            * 
            * 
            * */

           
                this.fullname= "nabi zakaria"
                this.birthday= "1995-08-07"
                this.tel= "0555655100"
                this.role="employee"
                this.email= "nabizakaria"
                this.username= "ldlkjdlkdj"
                this.password= "lkksjlfkjf"
            }

        },
    data(){
        return{

            fullname: "",
            birthday: "",
            tel: "",
            role : "",
            email: "",
            username: "",
            password: "",
              

                 

           
        }

        
    },
    
    methods:{
        
        editUser: function(){
              
        },
        
    }
    
}
</script>


<style scoped>

body{
        font-family: Roboto,sans-serif;

}

label{
    font-size: 14px;
    color: gray;
    
}

.submit-btn{
    width:400px;
    background-color:#272b30;
}

</style>

