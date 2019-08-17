<template>
    <div>

        <div class="table-wrapper">
            <div class="table-title" style="background-color:#272b30;">
                <div class="row">
                    <div class="col-sm-4">
                        <h2>Medicines / Batches </h2>
                    </div>
                    <div class="col-sm-3">
                        <input class="form-control form-control-dark w-100" type="text" placeholder="Search"
                            aria-label="Search" v-model="search">

                        

                    </div>
                    <div class="col-sm-1  ml-0 pl-0">
                        <img class="" style="cursor:pointer;color:white;" :src="'/img/icons/'+filter_flow+'.png'" width="38"
                                @click="(filter_flow == 'Ascending' )? filter_flow='Descending':filter_flow='Ascending'"
                                :title="filter_flow">
                    </div>
                    <div class="col-md-4">
                        <a href="#" class="btn btn-primary" data-toggle="modal"
                            :data-target="(medicineId==-1)? '#modalSelectMedicine':'#modalSelectSupplier'">
                            <i class="material-icons"></i> <span>Add New Medicine Batch</span>

                        </a>
                    </div>
                </div>

            </div>


            <div class="row">
                <div class="col-sm-5">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard/medicines">Medicines</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="#">Batches</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard/medicines/sales">Sales</a>
                        </li>

                    </ul>

                </div>

                <div class="col-sm-7 ">
                    <div class="row">
                        <div class="col-sm-1">
                            


                        </div>
                        <div class="col-sm-6">
                            <div class="md-form ">
                               

                            </div>

                        </div>
                        <div class="col-sm-5 d-flex justify-content-end  pr-5" data-toggle="modal" data-target="#modalCart">
                            <img style="cursor:pointer;" src="/img/icons/add_to_cart.png" width="38"
                                :title="filter_flow">
                            <span
                                :class=" (batches_in_cart_lenght==0)? 'rounded-circle   text-center pt-1  text-white bg-danger':'rounded-circle   text-center pt-1  text-white bg-success'"
                                style="width:30px;height:30px;position:absolute;margin-top:-13px;margin-left:13px;"><b>{{batches_in_cart_lenght}}</b></span>

                        </div>




                    </div>

                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr style="cursor:pointer">







                        <th class="text-center " :class="(selected_column.includes('medicine_name'))? 'select-search':''"
                            @click="(selected_column.includes('medicine_name'))? selected_column=selected_column.filter(function(e) { return e !== 'medicine_name' }):selected_column.push('medicine_name')">Name</th>
                        <th class="text-center" :class="(selected_column.includes('fabrication_date'))? 'select-search':''"
                            @click="(selected_column.includes('fabrication_date'))? selected_column=selected_column.filter(function(e) { return e !== 'fabrication_date' }):selected_column.push('fabrication_date')">
                            Fabrication Date</th>
                        <th class="text-center" :class="(selected_column.includes('expiry_date'))? 'select-search':''"
                            @click="(selected_column.includes('expiry_date'))? selected_column=selected_column.filter(function(e) { return e !== 'expiry_date' }):selected_column.push('expiry_date')">Expiry Date
                        </th>
                        <th class="text-center" :class="(selected_column.includes('unit_price'))? 'select-search':''"
                            @click="(selected_column.includes('unit_price'))? selected_column=selected_column.filter(function(e) { return e !== 'unit_price' }):selected_column.push('unit_price')">Unit Price</th>
                        <th class="text-center" :class="(selected_column.includes('batch_price'))? 'select-search':''"
                            @click="(selected_column.includes('batch_price'))? selected_column=selected_column.filter(function(e) { return e !== 'batch_price' }):selected_column.push('batch_price')">Batch Price
                        </th>
                        <th class="text-center" :class="(selected_column.includes('quantity_stock'))? 'select-search':''"
                            @click="(selected_column.includes('quantity_stock'))? selected_column=selected_column.filter(function(e) { return e !== 'quantity_stock' }):selected_column.push('quantity_stock')">Quantity
                        </th>
                        <th class="text-center" :class="(selected_column.includes('refund'))? 'select-search':''"
                         @click="(selected_column.includes('refund'))? selected_column=selected_column.filter(function(e) { return e !== 'refund' }):selected_column.push('refund')"  >Refund</th>


                        <th class="text-center" style="width:170px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(batch,index) in batches" :key="index">



                        <td class="text-center" :class="(selected_column.includes('medicine_name'))? 'select-search-data':''">
                            {{batch.medicine_name }}</td>
                        <td class="text-center" :class="(selected_column.includes('fabrication_date'))? 'select-search-data':''">
                            {{batch.fabrication_date }}</td>
                        <td class="text-center" :class="(selected_column.includes('expiry_date'))? 'select-search-data':''">
                            {{batch.expiry_date }}</td>
                        <td class="text-center" :class="(selected_column.includes('unit_price'))? 'select-search-data':''">
                            {{batch.unit_price }}</td>
                        <td class="text-center" :class="(selected_column.includes('batch_price'))? 'select-search-data':''">
                            {{batch.batch_price }}</td>
                        <td class="text-center" :class="(selected_column.includes('quantity_stock'))? 'select-search-data':''"
                            :style="(batch.quantity_stock<batch.quantity_min)? 'color:red;':'color:green;'"
                            data-tooltip="tooltip"
                            :data-original-title="(batch.quantity_stock<batch.quantity_min)?'Quantity en Stock < min( '+batch.quantity_min+' )       risk of rupture':'' ">
                            {{batch.quantity_stock }} <strong>/</strong> {{batch.quantity_bought }}</td>

                         <td class="d-flex justify-content-center" :class="(selected_column.includes('refund'))? 'select-search-data':''"
                                                             data-tooltip="tooltip" :data-original-title="batch.refund+' %'" >
                            <img width="22" height="22" v-bind:src="(batch.refund > 0)? '/img/icons/checkmark.png':'/img/icons/xmark.png'" class="icon" >
                        </td>

        
                        <td class="text-center">
                            <a href="#" class="sell" title="" data-tooltip="tooltip" data-original-title="Add To Cart"
                                @click="addToCart(batch.Id)">
                                <img src="/img/icons/add_to_cart.png" style="color:green;" width="22">
                            </a>

                            <a :href="'/dashboard/medicines/sales/'+batch.Id" class="sales" title=""
                                data-tooltip="tooltip" data-original-title="Sales">
                                <img src="/img/icons/sales.png" style="color:green;" width="22">
                            </a>

                            <a href="#" class="delete" title="" data-tooltip="tooltip" data-original-title="Delete">
                                <img src="/img/icons/trash.png" width="24">
                            </a>
                        </td>

                    </tr>


                </tbody>
            </table>
            <div class="clearfix">

                <ul class="pagination">
                    <li class="page-item disabled"><a href="#"
                            @click="(paginationCurrent > 1)? paginationCurrent--:true; ">Previous</a></li>
                    <li class="page-item" v-if="paginationCurrent > 2"><a href="#" @click="paginationCurrent -=2 "
                            class="page-link">{{paginationCurrent -2}}</a></li>
                    <li class="page-item" v-if="paginationCurrent > 1"><a href="#" @click="paginationCurrent--"
                            class="page-link">{{paginationCurrent -1}}</a></li>
                    <li class="page-item active"><a href="#" class="page-link">{{paginationCurrent}}</a></li>
                    <li class="page-item"><a href="#" @click="paginationCurrent++ "
                            class="page-link">{{paginationCurrent+1}}</a></li>
                    <li class="page-item"><a href="#" @click="paginationCurrent+=2 "
                            class="page-link">{{paginationCurrent+2}}</a></li>
                    <li class="page-item"><a href="#" @click="paginationCurrent++ " class="page-link">Next</a></li>
                </ul>
            </div>
        </div>


        <div class="modal fade" id="modalSelectMedicine" tabindex="-1" role="dialog" aria-labelledby="add New Batch"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content " style="border-top-left-radius:10px;border-top-right-radius:10px;">
                    <div class="modal-header bg-success text-center">
                        <h4 class="modal-title  w-100 font-weight-bold text-white">Add New Medicine Batch</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body mx-3">
                        <div class="md-form text-center">
                            <h3 style="color: #6f6e6e;">1- To Add New Medicine Batch Please Select a Medicine</h3>





                        </div>
                        <hr>
                        <div class="md-form ">
                            <a href="/dashboard/medicines" class="form-control btn btn-success">Select A Medicine</a>
                        </div>




                    </div>


                </div>
            </div>
        </div>

        <div class="modal fade" id="modalSelectSupplier" tabindex="-1" role="dialog" aria-labelledby="add New Batch"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content " style="border-top-left-radius:10px;border-top-right-radius:10px;">
                    <div class="modal-header bg-success text-center">
                        <h4 class="modal-title  w-100 font-weight-bold text-white">Add New Medicine Batch</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body mx-3">

                        <div class="md-form ">
                            <h3 style="color: #6f6e6e;" class="text-center ">2- Select the Supplier : </h3>

                            <select v-model="selected_supplier" class="form-control">
                                <option v-for="(supplier,index) in suppliers" :key="index" :value="supplier.id">
                                    {{supplier.name}}</option>
                            </select>
                            <span class="text-primary mb-3">Or add a new supplier !</span>
                            <form @submit.prevent="addNewSupplier">
                                <div class="pb-2 ">
                                    <input type="text" placeholder="Fullname" class="form-control"
                                        v-model="new_supplier.name" required>
                                </div>

                                <div class="pb-2">
                                    <input type="tel" placeholder="phone number" class="form-control"
                                        v-model="new_supplier.tel" required minlength="9" maxlength="10">
                                </div>

                                <div class="pb-2">
                                    <input type="text" placeholder="address" class="form-control"
                                        v-model="new_supplier.address" required minlength="8" maxlength="35">
                                </div>

                                <div class="pb-2">
                                    <input type="email" placeholder="email" class="form-control"
                                        v-model="new_supplier.email" required>
                                </div>
                                <hr>

                                <div class="md-form ">
                                    <button class="form-control btn btn-success">Add New Supplier</button>
                                </div>

                            </form>










                        </div>





                    </div>


                </div>
            </div>
        </div>

        <div class="modal fade" id="modalAddNewBatch" tabindex="-1" role="dialog" aria-labelledby="add New Batch"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content " style="border-top-left-radius:10px;border-top-right-radius:10px;">
                    <div class="modal-header bg-success text-center">
                        <h4 class="modal-title  w-100 font-weight-bold text-white">Add New Medicine Batch</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body mx-3">

                        <div class="md-form ">
                            <h3 style="color: #6f6e6e;" class="text-center ">Add New Medicine Batch : </h3>



                            <form @submit.prevent="addNewBatch">
                                <span style="color:gray;">Medicine Name :<strong> {{newBatch.medicine_name}} </strong> </span>
                                <div class="pb-2 ">
                                    <label for="fabrication_date" class="mb-0 ">Fabricaion Date</label>
                                    <input type="date" id="fabrication_date" class="form-control"
                                        v-model="newBatch.fabricaion_date" required>
                                </div>

                                <div class="pb-2 ">
                                    <label for="expiry_date" class="mb-0 ">Expiry Datee</label>
                                    <input type="date" id="expiry_date" class="form-control"
                                        v-model="newBatch.expiry_date" required>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="quatity_bought" class="mb-0 ">Quantity Purchased</label>
                                        <input type="number" id="quatity_bought" class="form-control"
                                            v-model="newBatch.quatity_bought" required min="0">
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="quantity_stock" class="mb-0 ">Quantity Stock</label>
                                        <input type="number" id="quantity_stock" class="form-control"
                                            v-model="newBatch.quantity_stock" required min="0">
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="quantity_minimum" class="mb-0 ">Quantity Minimum</label>
                                        <input type="number" id="quantity_minimum" class="form-control"
                                            v-model="newBatch.quantity_min" required min="0">
                                    </div>


                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="unit_price" class="mb-0 ">Unit Price</label>
                                        <input type="number" step="0.01" id="unit_price" class="form-control"
                                            v-model="newBatch.unit_price" required min="0">
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="batch_price" class="mb-0 ">Batch Price</label>
                                        <input type="number" step="0.01" id="batch_price" class="form-control"
                                            v-model="newBatch.batch_price" required min="0">
                                    </div>

                                </div>



                                <hr>

                                <div class="md-form ">
                                    <button class="form-control btn btn-success">Add New Supplier</button>
                                </div>

                            </form>










                        </div>





                    </div>


                </div>
            </div>
        </div>


        <div id="modalCart" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content " style="border-top-left-radius:10px;border-top-right-radius:10px;">
                    <div class="modal-header bg-success text-center">
                        <h4 class="modal-title  w-100 font-weight-bold text-white">Current Items In The Cart</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <table class="table table-striped table-hover">
                            <thead>
                                <tr style="cursor:pointer">







                                    <th class="text-center ">Name</th>
                                    <th class="text-center">Fabrication Date</th>
                                    <th class="text-center">Expiry Date</th>
                                    <th class="text-center">Unit Price</th>
                                    <th class="text-center">Batch Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center" style="width:170px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(batch,index) in batches_in_cart" :key="index">



                                    <td class="text-center">{{batch.medicine_name }}</td>
                                    <td class="text-center">{{batch.fabrication_date }}</td>
                                    <td class="text-center">{{batch.expiry_date }}</td>
                                    <td class="text-center">{{batch.unit_price }}</td>
                                    <td class="text-center">{{batch.batch_price }}</td>
                                    <td class="text-center">
                                        <input type="number" class="form-control" v-model.number="batch.quantity" :max="batch.quantity_stock" min="1">
                                    </td>


                                    <td class="text-center">
                                        

                                       

                                        <a href="#" class="delete" title="" data-tooltip="tooltip"
                                            data-original-title="Delete" @click="removeBatchFromCart(batch.Id)">
                                            <img src="/img/icons/trash.png" width="24">
                                        </a>
                                    </td>

                                </tr>


                            </tbody>
                        </table>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {

        mounted() {
            $('[data-tooltip="tooltip"]').tooltip();




        },

        data() {

            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                medicineId: this.$attrs.medicineid,
                route: window.location.pathname,
                search: "",
                selected_column: [],

                filter_flow: 'Ascending',
                filter_by: '0',


                //batches in the cart are with no session ( that means if the page is refreshed the items will vanish)
                batches_in_cart: [],
                batches_in_cart_lenght: 0,


                //displayed batches
                batches: [{
                        Id: 1,
                        medicine_name: "DOLIPRANE",
                        fabrication_date: "2019-08-26",
                        expiry_date: "2020-08-27",
                        unit_price: 0,
                        batch_price: 0,
                        quantity_bought: 25,
                        quantity_stock: 9,
                        quantity_min: 90,
                        refund: 1,//pourcentage of refund if 0% then no refund else a pourcentage is defined as a refund %
                        


                    },
                    {
                        Id: 2,
                        medicine_name: "sulpiride",
                        fabrication_date: "2019-08-26",
                        expiry_date: "2020-08-27",
                        unit_price: 150,
                        batch_price: 100,
                        quantity_bought: 25,
                        quantity_stock: 9,
                        quantity_min: 90,
                        refund: 0,//pourcentage of refund if 0% then no refund else a pourcentage is defined as a refund %



                    }




                ],
                selected_supplier: null,

                new_supplier: {
                    id: null,
                    name: "",
                    address: "",
                    tel: "",
                    email: "",
                },
                suppliers: [{
                        Id: 1,
                        name: "ahmed"
                    },
                    {
                        Id: 2,
                        name: "zakaria"
                    },
                    {
                        Id: 3,
                        name: "ilyes"
                    },
                ],
                newBatch: {
                    Id: 0,
                    medicine_name: "",
                    fabrication_date: "",
                    expiry_date: "",
                    unit_price: null,
                    batch_price: null,
                    quantity_bought: null,
                    quantity_stock: null,
                    quantity_min: null,

                },

                paginationCurrent: 1

        }
    },

    watch: {
            paginationCurrent: function (page) {
                this.getBatches();
            },
            search: function (val) {
                this.paginationCurrent = 1

                this.getBatches()

            },
            //when selected_supplier is changed (a supplier is selected) then exit the modal and show theaddNewBatch modal
            selected_supplier: function () {

                $("#modalSelectSupplier").modal("hide")
                $("#modalAddNewBatch").modal("show")


            },

            //watch batches_in_cart so that if a new batch is being added to the cart the number of items increases
            batches_in_cart: function (old,val) {
                this.batches_in_cart_lenght = val.length
                

            }



        },




        methods: {
            //remove batch from the cart 
            removeBatchFromCart: function(batchId){

                //search for the batch in batches_in_cart collection
                for (let i = 0; i < this.batches_in_cart.length; i++) {
                    if (batchId == this.batches_in_cart[i].Id)
                        this.batches_in_cart.splice(i,1)
                    
                }

            },
            //get batches in the current page
            getBatches: function () {
                /** get batches in the current page using the server's API with (axios)
                 *
                 *
                 *
                 */


            },
            getBatch: function (batchId) {

                /** get the batch with the batchId using the server's API with (axios)
                 *
                 *
                 *
                 */

                let batch = {
                    Id: 0,
                    medicine_name: "test Medicine Name",
                    fabrication_date: "07/08/2015",
                    expiry_date: "07/09/2019",
                    unit_price: 29,
                    batch_price: 20,
                    quantity_bought: null,
                    quantity_stock: 5,
                    quantity_min: 3,


                };









                return batch

            },
            addNewSupplier: function () {

            },
            addNewBatch: function () {

            },
            //this function add a new a batch to cart :
            addToCart: function (batchId) {
                //retrieve the batch informations from the server
                let batch = this.getBatch(batchId)
                //we add the quantity property to batch object


                //test if the batch already exist in the batches_in_cart data
                //if the batch exist in batches_in_cart thenwe increment the quantity by one
                for (let i = 0; i < this.batches_in_cart.length; i++) { 
                    //test if thequantity in the stock is above 0( > 0)
                    if (batch.Id == this.batches_in_cart[i].Id) { 

                        if (this.batches_in_cart[i].quantity < batch.quantity_stock){
                            this.batches_in_cart[i].quantity++

                            

                            console.log(batch.Id+" / "+this.batches_in_cart[i].Id)


                        }
                    } 

                }

                if (this.batches_in_cart.length == 0){
                    batch.quantity = 1
                    this.batches_in_cart.push(batch)
                }















            }



        },
        components: {


        }

    }

</script>


<style scoped>
    body {
        color: #566787;
        background: #f5f5f5;
        font-family: 'Varela Round', sans-serif;
        font-size: 13px;
    }

    .table-wrapper {
        background: #fff;
        padding: 20px 25px;
        margin: 30px 0;
        border-radius: 3px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    }

    .table-title {
        padding-bottom: 15px;
        background: #299be4;
        color: #fff;
        padding: 16px 30px;
        margin: -20px -25px 10px;
        border-radius: 3px 3px 0 0;
    }

    .table-title h2 {
        margin: 5px 0 0;
        font-size: 24px;
    }

    .table-title .btn {
        color: #566787;
        float: right;
        font-size: 13px;
        background: #fff;
        border: none;
        min-width: 50px;
        border-radius: 2px;
        border: none;
        outline: none !important;
        margin-left: 10px;
    }

    .table-title .btn:hover,
    .table-title .btn:focus {
        color: #566787;
        background: #f2f2f2;
    }

    .table-title .btn i {
        float: left;
        font-size: 21px;
        margin-right: 5px;
    }

    .table-title .btn span {
        float: left;
        margin-top: 2px;
    }

    table.table tr th,
    table.table tr td {
        border-color: #e9e9e9;
        padding: 12px 15px;
        vertical-align: middle;
    }

    table.table tr th:last-child {
        width: 100px;
    }

    table.table-striped tbody tr:nth-of-type(odd) {
        background-color: #fcfcfc;
    }

    table.table-striped.table-hover tbody tr:hover {
        background: #f5f5f5;
    }

    table.table th i {
        font-size: 13px;
        margin: 0 5px;
        cursor: pointer;
    }

    table.table td:last-child i {
        opacity: 0.9;
        font-size: 22px;
        margin: 0 5px;
    }

    table.table td a {
        font-weight: bold;
        color: #566787;
        display: inline-block;
        text-decoration: none;
    }

    table.table td a:hover {
        color: #2196F3;
    }

    table.table td a.settings {
        color: #2196F3;
    }

    table.table td a.delete {
        color: #F44336;
    }

    table.table td i {
        font-size: 19px;
    }

    table.table .avatar {
        border-radius: 50%;
        vertical-align: middle;
        margin-right: 10px;
        width: 40px;
    }

    .status {
        font-size: 30px;
        margin: 2px 2px 0 0;
        display: inline-block;
        vertical-align: middle;
        line-height: 2px;
    }

    .text-success {
        color: #10c469;
    }

    .text-info {
        color: #62c9e8;
    }

    .text-warning {
        color: #FFC107;
    }

    .text-danger {
        color: #ff5b5b;
    }

    .pagination {
        float: right;
        margin: 0 0 5px;
    }

    .pagination li a {
        border: none;
        font-size: 13px;
        min-width: 30px;
        min-height: 30px;
        color: #999;
        margin: 0 2px;
        line-height: 30px;
        border-radius: 2px !important;
        text-align: center;
        padding: 0 6px;
    }

    .pagination li a:hover {
        color: #666;
    }

    .pagination li.active a,
    .pagination li.active a.page-link {
        background: #03A9F4;
    }

    .pagination li.active a:hover {
        background: #0397d6;
    }

    .pagination li.disabled i {
        color: #ccc;
    }

    .pagination li i {
        font-size: 16px;
        padding-top: 6px
    }

    .hint-text {
        float: left;
        margin-top: 10px;
        font-size: 13px;
    }

    label {
        font-size: 14px;
        color: gray;

    }

    .select-search-data {

        background-color: #c5e4b254;
    }

    .select-search {
        background-color: #4e9cda;

    }

</style>
