<template>
    <div>
        <div class="modal fade" id="modaladdMedicine" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Add New Medicine</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                        


                        <form method="post" @submit.prevent="addMedicine">
                            <div class="md-form mb-2 text-center">
                                <input style="display:none;" type="file" ref="imgInput" @change="onFileSelected" >
                                <img class="shadow-sm p-3  bg-white rounded" v-bind:src="img" width="150"
                                    title="Upload the Medicine Image" style="cursor:pointer;"
                                    @click="$refs.imgInput.click()" />
                            </div>
                            <div class="md-from mb-2 ">
                            <h3 style="color: #6f6e6e;">Enter New Medicine Information</h3>
                        </div>

                            <div class="md-form mb-2">


                                <div class="row">
                                    <div class="col-6">
                                        <label for="name">Name</label>
                                        <input name="name" type="text" id="name" class="form-control " v-model="name"
                                            required minlength="8" maxlength="25">
                                    </div>
                                    <div class="col-6">
                                        <label for="dosage">Dosage (mg)</label>
                                        <input name="dosage" type="number" id="dosage" class="form-control "
                                            v-model="dosage" required min="0.01" step="0.01">

                                    </div>
                                </div>
                            </div>



                            <div class="md-form mb-2">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="form">Form </label>
                                        <select name="form" id="form" class="form-control" v-model="form" required>
                                            <option value="Comprime">Comprimé</option>
                                            <option value="gelule">gélule</option>
                                            <option value="collyre">collyre</option>
                                            <option value="sirop">sirop</option>
                                        </select>

                                    </div>
                                    <div class="col-6">
                                        <label for="Family">Family</label>

                                        <input name="Family" type="text" id="Family" class="form-control "
                                            v-model="family" required>

                                    </div>
                                </div>


                            </div>

                            <div class="md-form mb-2">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="Stock">Stock</label>

                                        <input name="Stock" type="text" id="Stock" class="form-control "
                                            v-model="stock" required>


                                    </div>
                                    <div class="col-6">
                                        <label for="refund">Refund</label>

                                        <input name="refund" type="number" id="refund" class="form-control "
                                            v-model="refund" required min="0" max="100">

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




    </div>

</template>


<script>
import { constants } from 'crypto';
    export default {

        props: ['medicineId'],

        mounted() {
        },
        data() {
            return {

                img : "/img/icons/pills.png",

                Img : null,
                Id: "",
                name: "",
                dosage: "",
                form: "",
                family: "",
                stock: 0,
                stock_min: 15,
                refund: 0,//pourcentage of refund if 0% then no refund else a pourcentage is defined as a refund %




            }
        },
        methods: {
            onFileSelected : function(event){
                this.img = URL.createObjectURL(event.target.files[0])

                this.Img = new FormData()
                this.Img.append("image",event.target.files[0],event.target.files[0].name)
            },




            addMedicine: function () { //this method adds a Medicine via the api 
                /* we add new Medicine using Axios 
                 *
                 *
                 * 
                 * 
                 * 
                 * 
                 * 
                 * */


                alert("Medicine Added")
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
