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
                        <div class="md-from mb-2 ">
                            <h3 style="color: #6f6e6e;">Enter Personal Information</h3>
                        </div>


                        <form method="post" @submit.prevent="NextModal">
                            
                            <div class="md-form mb-2">


                                <div class="row">
                                    <div class="col-6">
                                        <label for="fullname">Fullname</label>
                                        <input name="fullname" type="text" id="fullname" class="form-control "
                                            v-model="fullname" required minlength="8" maxlength="25">
                                    </div>
                                    <div class="col-6">
                                        <label for="birthday">Birthday</label>
                                        <input name="birthday" type="date" id="birthday" class="form-control " 
                                            v-model="birthday" required>

                                    </div>
                                </div>
                            </div>



                            <div class="md-form mb-2">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="phone">Phone Number :</label>
                                        <input name="phone number" type="tel" id="phone" class="form-control " 
                                                v-model="tel" required pattern="[0-9]{9}|[0-9]{10}">

                                    </div>
                                    <div class="col-6">
                                        <label for="email">Email</label>

                                        <input name="email" type="email" id="email" class="form-control " 
                                        v-model="email" required>

                                    </div>
                                </div>


                            </div>
                            
                            <hr>
                            <div class="d-flex justify-content-end">
                                <input type="submit" class="btn btn-primary next-btn pull-right " value="Next">

                            </div>


                        </form>

                    </div>

                </div>
            </div>
        </div>



        <div class="modal fade" id="modaladdUser2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Add New User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" @submit.prevent="addUser">
                        <div class="modal-body mx-3">
                            <div class="md-from mb-2 ">
                                <h3 style="color: #6f6e6e;">Enter User Credentials</h3>
                            </div>
                            
                            <div class="md-form mb-2">
                                <label for="username">Login Username</label>
                                <input name="username" type="text" id="username" class="form-control"
                                            required minlength="8" maxlength="16">
                            </div>


                            <div class="md-form mb-2">
                                <label for="password">Password</label>
                                <input name="password" type="password" id="password" class="form-control " 
                                            required minlength="8" maxlength="16">
                            </div>


                            
                        </div>


                        <hr>
                        <div class="d-flex justify-content-end mb-2 mr-3">
                            <div  class="btn btn-primary previous-btn mr-2 "   @click="PreviousModal">Previous</div>
                            <input type="submit" class="btn btn-success submit-btn mr-3" value="Add User" >

                        </div>
                    </form>
                   
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

                currentModal: 1,
                fullname: "",
                birthday: "",
                tel: "",
                email: "",
                username: "",
                password: "",
                validationError : "",


               

            }
        },
        methods: {

            NextModal: function (id) {
                
               //hide the current modal (the personal information modal)
                $("#modaladdUser").modal('hide')
                
                //show the second modal (the credentials modal)
                $("#modaladdUser2").modal("show")



                

            },
            PreviousModal : function(){
                
                
                this.validationError = ""

                //hide the current modal (the credentials modal)
                $("#modaladdUser2").modal('hide')
                
                //show the first modal (the personal information modal)
                $("#modaladdUser").modal("show")



                
            },
            untoggle_modal: function (id) {
                this.validationError = ""
                $("#" + id).modal('hide');
            },
            addUser: function () { //this method adds a user via the api 
                /* we add new User using Axios 
                *
                *
                * 
                * 
                * 
                * 
                * 
                * */


                alert("User Added")
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
