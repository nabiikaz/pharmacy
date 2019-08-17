<template>
    <div>
        <div class="modal fade" id="modaleditsupplier" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Edit Supplier</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                        <form action="post" @submit.prevent="editSupplier">
                           <div class="md-form mb-2">


                               <label for="fullname">Fullname</label>
                                        <input name="fullname" type="text" id="fullname" class="form-control "
                                            v-model="fullname" required minlength="8" maxlength="25">
                            </div>

                             <div class="md-form mb-2">


                               <label for="phone">Phone Number :</label>
                                        <input name="phone number" type="tel" id="phone" class="form-control " 
                                                v-model="tel" required pattern="[0-9]{9}|[0-9]{10}">
                            </div>

                             <div class="md-form mb-2">


                               <label for="address">Address</label>
                                        <input name="address" type="text" id="address" class="form-control "
                                            v-model="address" required minlength="8" maxlength="25">
                            </div>

                             <div class="md-form mb-2">


                              <label for="email">Email</label>

                                        <input name="email" type="email" id="email" class="form-control " 
                                        v-model="email" required>
                            </div>



                            <hr>


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
        props: ['supplierId'],


        watch: {
            supplierId: function (val) {
                /* axios
                 *
                 *
                 * 
                 * 
                 * */


                this.fullname = "nabi zakaria"
                this.birthday = "1995-08-07"
                this.tel = "0555655100"
                this.role = "moderator"
                this.email = "nabizakaria"

            }

        },
        data() {
            return {

                fullname: "",
                birthday: "",
                tel: "",
                role: "",
                email: "",






            }


        },

        methods: {

            editSupplier: function () {

            },

        }

    }

</script>


<style scoped>
    body {
        font-family: Roboto, sans-serif;

    }

    label {
        font-size: 14px;
        color: gray;

    }

    .submit-btn {
        width: 400px;
        background-color: #272b30;
    }

</style>
