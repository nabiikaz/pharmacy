<template>
    <div>
        <div class="modal fade" id="modaladdUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Add New User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                        


                        <form method="post" @submit.prevent="addUser">
                            <div class="md-form mb-2 text-center">
                                <input style="display:none;" type="file" id="image" ref="imgInput" @change="onFileSelected" required >
                                <img class=" avatar" alt="Avatar" v-bind:src="img" width="150"
                                    title="Upload the Medicine Image" style="cursor:pointer;border-radius:150px"
                                    @click="$refs.imgInput.click()" />
                            </div>
                            <div class="md-from mb-2 ">
                            <h3 style="color: #6f6e6e;">Enter Personal Information</h3>
                        </div>
                            
                            <div class="md-form mb-2">


                                <div class="row">
                                    <div class="col-6">
                                        <label for="fullname">Fullname</label>
                                        <input name="fullname" type="text" id="fullname" class="form-control "
                                            v-model="user.name" required minlength="8" maxlength="25">
                                    </div>
                                    <div class="col-6">
                                        <label for="birthday">Birthday</label>
                                        <input name="birthday" type="date" id="birthday" class="form-control " 
                                            v-model="user.birthday" required>

                                    </div>
                                </div>
                            </div>



                            <div class="md-form mb-2">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="phone">Phone Number :</label>
                                        <input name="phone number" type="tel" id="phone" class="form-control " 
                                                v-model="user.tel" required pattern="[0-9]{9}|[0-9]{10}">

                                    </div>
                                    <div class="col-6">
                                        <label for="email">Email</label>

                                        <input name="email" type="email" id="email" class="form-control " 
                                        v-model="user.email" required>

                                    </div>
                                </div>


                            </div>

                            <div class="md-form mb-2">
                                <label for="password">Password</label>
                                <input name="password" type="password" id="password" class="form-control " 
                                            required minlength="8" maxlength="16" v-model="user.password">
                            </div>
                            <span :class="MessageClass">{{Message}}</span>

                            
                            <hr>
                            <div class="d-flex justify-content-center ">
                                <input type="submit" class="btn btn-primary next-btn w-100 " value="Add Medicine" >

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

        mounted() {

        },
        data() {
            return {

                img : "/img/icons/pills.png",
                Message:"",
                MessageClass:"text-success",
                Img:null,
                
                user:{
                    
                    name: "",
                    birthday: "",
                    tel: "",
                    email: "",
                    password: "",
                    

                },


               

            }
        },
        updated: function(){$('[data-tooltip=tooltip]').tooltip();}, 
methods: {
            onFileSelected : function(e){
                 this.img = URL.createObjectURL(e.target.files[0])

                var fileReader = new FileReader()

                fileReader.readAsDataURL(e.target.files[0])

                fileReader.onload = (e) => {
                    this.user.image = e.target.result
                }
            },

            addUser: function (id) {
                
                console.log(this.user)
                
                axios.post('/api/pharmacists',this.user)
                    .then((response)=>{
                        console.log(response)

                        switch (response.status) {
                            case 201:

                                this.Message = "Pharmacist was created successfully ."
                                this.MessageClass = "text-success"
                                //clear all inputs 
                                this.user = {}
                                $("#image").val("")
                                this.img = "/img/icons/pills.png"
                                //emit back the created Medicine : 
                                
                                this.$emit('created', response.data.data)


                                
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
                            
                            this.MessageClass = "text-danger"
                             switch (error.response.status) {
                            case 422 :                              
                                this.Message  = Object.values(error.response.data.errors)[0][0]
                                
                                this.MessageClass = "text-danger"
                                return

                                
                                break;
                        
                            default:
                                break;
                            }   
                            this.Message = "User Couldn't be created || Server Error : "+error.response.statusText
                        }else if (error.request) {
                            /*
                            * The request was made but no response was received, `error.request`
                            * is an instance of XMLHttpRequest in the browser and an instance
                            * of http.ClientRequest in Node.js
                            */
                            console.log(error.request);
                        } else {
                            // Something happened in setting up the request and triggered an Error
                            console.log('Error', error.message);
                        }
                       
                    });
            



                

            },
            
            untoggle_modal: function (id) {
                this.validationError = ""
                $("#" + id).modal('hide');
            },
           


        },
        components: {

        },

    }

</script>


<style scoped>
    body {
        font-family: Roboto, sans-serif;

    }

    label {
        font-size: 14px;
        color: gray;
        margin-bottom: 0px;

    }

    .next-btn {
        width: 150px;

    }

</style>
