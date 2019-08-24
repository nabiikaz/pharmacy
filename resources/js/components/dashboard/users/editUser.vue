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
                <div class="md-form mb-2 text-center">
                                <input style="display:none;" type="file" id="image" ref="imgInput" @change="onFileSelected"  >
                                <img class=" avatar" alt="Avatar" v-bind:src="img" width="150"
                                    title="Upload the Medicine Image" style="cursor:pointer;border-radius:150px"
                                    @click="$refs.imgInput.click()" />
                            </div>
            <div class="md-form mb-2">
            
                <div class="row">
                    <div class="col-6">
                        <label for="fullname" >Fullname</label>
                        <input name="fullname" type="text" id="fullname" class="form-control " 
                                    required minlength="8" maxlength="25" v-model="user.name" >
                    </div>
                    <div class="col-6">
                        <label for="birthday">Birthday</label>
                         <input name="birthday" type="date" id="birthday" class="form-control "  
                                    required v-model="user.birthday" >
                    
                    </div>
                </div>
            </div>

            

            <div class="md-form mb-2">
            <div class="row">
                    <div class="col-6">
                        <label for="phone" >Phone Number</label>
                 <input name="tel" type="tel" id="phone" class="form-control " 
                                    required pattern="[0-9]{9}|[0-9]{10}" v-model="user.tel">
                        
                    </div>
                    <div class="col-6">
                        <label for="role">Role</label>
                        <select name="role" class="form-control" id="role" v-model="user.role" >
                    
                    <option value="moderator">Moderator</option>
                    <option value="admin">Admin</option>
                </select>
                    
                    </div>
                </div>
            
            </div>

            <div class="md-form mb-3">
                        <label for="email">Email</label>
                
                 <input name="email" type="email" id="email" class="form-control " required v-model="user.email" >
            
            </div>

          <hr>

            <div class="md-form mb-2">
                <label for="password">Password</label>
                <input name="password" type="password" id="password" class="form-control" 
                                 minlength="8" maxlength="16" v-model="user.password">
            
            </div>
            <div class="md-form mb-2">
                        
            
                 
            
            </div>
            <span :class="MessageClass">{{Message}}</span>

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
            userId:function(Id){
                this.getUser(Id)
                this.Message = ""
                this.MessageClass = "text-success"
            }

        },
    data(){
        return{
            img : "/img/avatars/man.png",
            Message:"",
            MessageClass:"text-success",


           

            user:{},
              

                 

           
        }

        
    },
    
    methods:{
        onFileSelected : function(e){
                 this.img = URL.createObjectURL(e.target.files[0])

                var fileReader = new FileReader()

                fileReader.readAsDataURL(e.target.files[0])

                fileReader.onload = (e) => {
                    this.user.image = e.target.result
                }
            },
        
            getUser: function(userId){
                    this.disable = false
                    this.errorMsg = ""
               
                    axios.get('/api/pharmacists/'+this.userId)
                    .then((Response)=>{
                         this.user = Response.data.data
                         this.img = "/img/avatars/"+this.user.Img
                         console.log(this.user)

                         

                    }).catch(error => {
                        
                        if(error.response){
                            /**
                             * the request was made and the server responded with  a
                             * status code that falls out of the range of 2**
                             *  */
                            
                            this.MessageClass = "text-danger"
                             switch (error.response.status) {
                                case 422 :                              
                                    this.Message  = Object.values(error.response.data.errors)[0][0]
                                    
                                    this.MessageClass = "text-danger"
                                    return
                                case 404:
                                    break;
                        
                            default:
                                break;
                            }   
                            this.Message = "Pharmacist : "+error.response.statusText
                        }
                    })
               
               

                 
            },

            editUser: function (event) {
                /** edit the Medicine using Axios via the server's API
                 * 
                 * 
                 * 
                 * 
                 * 
                 */
                
                

                axios.patch('/api/pharmacists/'+this.userId,this.user)
                    .then((Response)=>{
                        this.ImgFormData = null
                        this.getUser(this.userId)
                        switch (Response.status) {
                            case 200:


                                this.Message = "Pharmacist was updated successfully ."
                                this.MessageClass = "text-success"
                                //clear all inputs 
                                this.user = {}
                                $("#image").val("")
                                this.img = "/img/icons/pills.png"
                                //emit back the edited Medicine : 
                                this.$emit('updated', Response.data.data)


                                
                                break;
                        
                            default:
                                break;
                        }
                         
                

                         

                    }).catch(error => {
                        
                        if(error.response){
                            /**
                             * the request was made and the server responded with  a
                             * status code that falls out of the range of 2**
                             *  */
                            console.log(error.response)
                            this.Message = "Error : "+ error.response.data.errors
                            this.MessageClass = "text-danger"
                             switch (error.response.status) {
                            case 404:

                                

                                
                                break;
                        
                            default:
                                break;
                            }   
                        }
                        
                    });
              

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

