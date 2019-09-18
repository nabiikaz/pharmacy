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
                                title="Batches">
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







                        <th class="text-center " :class="(selected_column == 'medicine_name')? 'select-search':''"
                            @click="(selected_column == 'medicine_name')? selected_column='':selected_column = 'medicine_name'">Name</th>
                        <th class="text-center" :class="(selected_column == 'fabrication_date')? 'select-search':''"
                            @click="(selected_column == 'fabrication_date')? selected_column='':selected_column = 'fabrication_date'">
                            Fabrication Date</th>
                        <th class="text-center" :class="(selected_column == 'expiry_date')? 'select-search':''"
                            @click="(selected_column == 'expiry_date')? selected_column='':selected_column = 'expiry_date'">Expiry Date
                        </th>
                        <th class="text-center" :class="(selected_column == 'unit_price')? 'select-search':''"
                            @click="(selected_column == 'unit_price')? selected_column='':selected_column = 'unit_price'">Unit Price</th>
                        <th class="text-center" :class="(selected_column == 'batch_price')? 'select-search':''"
                            @click="(selected_column == 'batch_price')? selected_column='':selected_column = 'batch_price'">Batch Price
                        </th>
                        <th class="text-center" :class="(selected_column == 'quantity_stock')? 'select-search':''"
                            @click="(selected_column == 'quantity_stock')? selected_column='':selected_column = 'quantity_stock'">Quantity
                        </th>
                        <th class="text-center" :class="(selected_column == 'refund_rate')? 'select-search':''"
                         @click="(selected_column == 'refund_rate')? selected_column='':selected_column = 'refund_rate'"  >Refund</th>


                        <th class="text-center" style="width:170px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(batch,index) in batches" :key="index">



                        <td class="text-center" :class="(selected_column == 'medicine_name')? 'select-search-data':''">
                            {{batch.medicine_name }}</td>
                        <td class="text-center" :class="(selected_column == 'fabrication_date')? 'select-search-data':''">
                            {{batch.fabrication_date }}</td>
                        <td class="text-center" :class="(selected_column == 'expiry_date')? 'select-search-data':''">
                            {{batch.expiry_date }}</td>
                        <td class="text-center" :class="(selected_column == 'unit_price')? 'select-search-data':''">
                            {{batch.unit_price }}</td>
                        <td class="text-center" :class="(selected_column == 'batch_price')? 'select-search-data':''">
                            {{batch.batch_price }}</td>
                        <td class="text-center" :class="(selected_column == 'quantity_stock')? 'select-search-data':''"
                            :style="(batch.quantity_stock<batch.quantity_min)? 'color:red;':'color:green;'"
                            data-tooltip="tooltip"
                            :data-original-title="(batch.quantity_stock<batch.quantity_min)?'Quantity en Stock < min( '+batch.quantity_min+' )       risk of rupture':'' ">
                            {{batch.quantity_stock }} <strong>/</strong> {{batch.quantity }}</td>

                         <td class="d-flex justify-content-center" :class="(selected_column == 'refund_rate')? 'select-search-data':''"
                                                             data-tooltip="tooltip" :data-original-title="batch.refund_rate+' %'" >
                            <img width="22" height="22" v-bind:src="(batch.refund_rate > 0)? '/img/icons/checkmark.png':'/img/icons/xmark.png'" class="icon" >
                        </td>

        
                        <td class="text-center">
                            <a href="#" class="sell" title="" data-tooltip="tooltip" data-original-title="Add To Cart"
                                @click="addToCart(batch.id)">
                                <img src="/img/icons/add_to_cart.png" style="color:green;" width="22">
                            </a>

                            <a :href="'/dashboard/medicines/sales/'+batch.id" class="sales" title=""
                                data-tooltip="tooltip" data-original-title="Sales">
                                <img src="/img/icons/sales.png" style="color:green;" width="22">
                            </a>

                            <a href="#" class="delete" title="" data-tooltip="tooltip" data-original-title="Delete" @click="deleteBatch(batch.id)">
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

        <div class="modal fade" id="modalSelectCustomer" tabindex="-1" role="dialog" aria-labelledby="add New Batch"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content " style="border-top-left-radius:10px;border-top-right-radius:10px;">
                    <div class="modal-header bg-success text-center">
                        <h4 class="modal-title  w-100 font-weight-bold text-white">Select Customer</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body mx-3">

                        <div class="md-form ">
                            <h3 style="color: #6f6e6e;" class="text-center ">Select the Customer : </h3>

                            <select v-model="selected_customer_id" class="form-control " >
                                <option v-for="(customer,index) in customers" :key="index" :value="customer.id">
                                    {{customer.name}}</option>
                            </select>
                            <span class="text-primary mb-3">Or add a new customer !</span>
                            <form @submit.prevent="addNewCustomer">
                                <div class="pb-2 ">
                                    <input type="text" placeholder="Fullname" class="form-control"
                                        v-model="new_customer.name" required>
                                </div>

                                <div class="pb-2">
                                    <input type="tel" placeholder="phone number" class="form-control"
                                        v-model="new_customer.tel" required minlength="9" maxlength="10">
                                </div>

                                <div class="pb-2">
                                    <input type="text" placeholder="address" class="form-control"
                                        v-model="new_customer.address" required minlength="8" maxlength="35">
                                </div>

                                <div class="pb-2">
                                    <input type="email" placeholder="email" class="form-control"
                                        v-model="new_customer.email" >
                                </div>

                                <div class="pb-2">


                                    <label for="refund">Refund</label>

                                    <input name="refund" type="number" id="refund" class="form-control "
                                        v-model="new_customer.refund_rate" required step="0.01"   min="0" max="100">
                                </div>

                                <span :class="MessageClass">{{Message}}</span>

                                <hr>

                                <div class="md-form ">
                                    <button class="form-control btn btn-success">Add New Customer</button>
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
                                        <input type="number" class="form-control" :class="(error_batch_id ==batch.id) ?'is-invalid':''" v-model.number="batch.quantity" :max="batch.quantity_stock" min="1">
                                    </td>


                                    <td class="text-center">
                                        

                                       

                                        <a href="#" class="delete" title="" data-tooltip="tooltip"
                                            data-original-title="Delete" @click="removeBatchFromCart(index)">
                                            <img src="/img/icons/trash.png" width="24">
                                        </a>
                                    </td>

                                </tr>


                            </tbody>
                        </table>
                            <span :class="MessageClass">{{Message}}</span>
                        <hr>

                        <div class="row ">
                            <div class="col-6"></div>
                            <div class="col-6 d-flex justify-content-end">
                                <input type="text" class="btn btn-warning mr-2" style="width: 140px;" value="Select Customer" @click="selectCustomer">
                                <input type="text" class="btn btn-primary " style="width: 140px;" value="Confirm Sale" @click="SubmitBatches" >
                            </div>
                           
                        </div>
                                           
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { constants } from 'crypto';
    export default {

        mounted() {

            this.getBatches()
            this.getCustomers()

             this.$nextTick(() => {
                 console.log("coco")
             })
            


        },

        data() {

            return {
                error_batch_id:-1,
                 Message:"",
                MessageClass:"text-success",

                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                medicineId: this.$attrs.medicineid,
                route: window.location.pathname,
                search: "",
                selected_column: '',

                filter_flow: 'Ascending',
                filter_by: '0',


                //batches in the cart are with no session ( that means if the page is refreshed the items will vanish)
                batches_in_cart: [],
                batches_in_cart_lenght: 0,


                //displayed batches
                batches: [],
                selected_customer_id: -1,

                new_customer: {
                    
                    },
                customers: [],
                newBatch: {},

                paginationCurrent: 1,

                submitCart: false ,
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
            //when selected_customer_id is changed (a customer is selected) then exit the modal and show theaddNewBatch modal
            selected_customer_id: function () {
                this.MessageClass="text-primary"
                this.Message=""
                $("#modalSelectCustomer").modal("hide")
                $("#modalCart").modal("show")


            },
            selected_column: function(){
                this.getBatches();
            },
            filter_flow : function(){
                 this.getBatches();
            },

            //watch batches_in_cart so that if a new batch is being added to the cart the number of items increases
            batches_in_cart: function (old,val) {
                this.batches_in_cart_lenght = this.batches_in_cart.length


                for(let i = 0 ; i < this.batches_in_cart_lenght ;i++){
                    for(let j = this.batches_in_cart_lenght-1;j>i;j--){
                        if(this.batches_in_cart[i].id == this.batches_in_cart[j].id ){
                            if(this.batches_in_cart[i].quantity < this.batches_in_cart[i].quantity_stock)
                                this.batches_in_cart[i].quantity++;
                            this.batches_in_cart.splice(j,1);
                            return

                        }
                    }
                }
                

            },
            batches: function(){
                        //$('[data-tooltip=tooltip]').tooltip();

            }



        },

        


        updated: function(){$('[data-tooltip=tooltip]').tooltip();}, 
        methods: {

            //select customer :
            selectCustomer: function(){
                this.getCustomers()
                $('#modalSelectCustomer').modal("show");
                $('#modalCart').modal('hide');

            },
            //get customers in the current page 
            getCustomers: function(){
                /** get customers in the current page using the server's API with (axios)
                 * 
                 * 
                 * 
                 */
                axios.get("/api/customers", {
                    params:{
                            
                        search:"",
                        
                        }
                    
                    })
                        .then((response) => {
                        this.customers = response.data.data;
                        
                    }, (error) => {
                        console.log(error);
                    });


                    

            

            },
            addNewCustomer:function(){
                console.log(this.new_customer)
                
                axios.post('/api/customers',this.new_customer)
                    .then((response)=>{

                        switch (response.status) {
                            case 201:

                                this.Message = "Customer was created successfully ."
                                this.MessageClass = "text-success"
                                //clear all inputs 
                                this.customer = {}
                                
                                
                                this.selected_customer_id = response.data.data.id

                                this.new_customer = {}


                                
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
                            this.Message = "Customer Couldn't be Added : "+error.response.statusText
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
            //remove batch from the cart 
            removeBatchFromCart: function(index){
                this.batches_in_cart.splice(index,1)
               /* //search for the batch in batches_in_cart collection
                for (let i = 0; i < this.batches_in_cart.length; i++) {
                    if (batchId == this.batches_in_cart[i].Id)
                        this.batches_in_cart.splice(i,1)
                    
                }*/

            },
            getBatch: function(batchId){
                    this.disable = false
                    this.errorMsg = ""
                    
                    axios.get('/api/batches/'+batchId)
                    .then((Response)=>{
                        Response.data.data.quantity = 1
                          this.batches_in_cart.push(Response.data.data)

                    

                        

                         

                    }).catch(error => {
                        
                        if(error.response){
                            /**
                             * the request was made and the server responded with  a
                             * status code that falls out of the range of 2**
                             *  */
                            
                             switch (error.response.status) {
                            case 404:

                                

                                
                                break;
                        
                            default:
                                break;
                            }   
                        }
                    })


                
               
               

                 
            },
            //get batches in the current page
            getBatches: function(){
            
                axios.get("/api/batches", {
                    params:{
                            
                        search:this.search,
                        selected_column:this.selected_column,
                        filter_flow:this.filter_flow,
                        page:this.paginationCurrent
                        }
                    
                    })
                        .then((response) => {
                        this.batches = response.data.data;
                                                
                    }, (error) => {
                        console.log(error);
                    });

           

             },
            
            
            
            
            addNewBatch: function () {

            },
            SubmitBatches: function () {
                if(this.selected_customer_id == -1){
                    this.Message= "Please Select A Customer to Continue ..."
                    this.MessageClass = "text-danger"

                    $("#modalSelectCustomer").modal("show");
                    $("#modalCart").modal("hide");
                    return;
                }

                if(this.batches_in_cart.length == 0){
                    this.Message= "Please Select A Medicine Batches to Continue the Sale Confirmation ..."
                    this.MessageClass = "text-danger"

                    return;
                }

                
                
                axios.patch('/api/batches/'+this.selected_customer_id,this.batches_in_cart)
                    .then((Response)=>{
                        switch (Response.status) {
                            case 200:


                                this.Message = "Sale Confirmed ."
                                this.MessageClass = "text-success"
                                //clear all inputs 
                                this.batches_in_cart = []
                                $("#modalCart").modal("hide");


                                


                                
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
                           
                             switch (error.response.status) {
                            case 404:

                                

                                
                                break;

                                case 412:

                                    this.Message = "Error!: "+error.response.statusText+" "+error.response.data.message
                                    this.error_batch_id = error.response.data.batch_id
                                    this.MessageClass = "text-danger"
                                    return

                                
                                break;
                        
                            default:
                                break;
                            }   
                             console.log(error.response)
                            this.Message = "Error : "+ error.response.data.errors
                            this.MessageClass = "text-danger"
                        }
                        
                    });
              

            },
            //this function add a new a batch to cart :
            addToCart: function (batchId) {
                this.getBatch(batchId);
               



            },
             //delete medicines 
            deleteBatch: function(batchId){
                 axios.delete('/api/batches/'+batchId).then((response)=>{
                                this.getBatches()

                                for(let i = 0;i<this.batches_in_cart.length;i++){
                                    if(this.batches_in_cart[i].id == batchId)
                                        this.batches_in_cart.splice(i,1);
                                }

                 })
                    .catch(error => {
                        
                        switch (error.response.status) {
                            case 404:

                                //if the medicine is already deleted then refresh current medicines view 
                                this.getMedicines()

                                
                                break;
                        
                            default:
                                break;
                        }
                    });

            },



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
    th{
        border-left:  1px solid  #dee2e6;
        border-right:  1px solid  #dee2e6;

    }

</style>
