<template>
    <div>
        <div class="row pt-4 pb-4   ">
            <div class="col-3 site-logo ">
                <a href="/" class="text-"> PHARMA-SINA </a>
            </div>
            <div class="col-6 pt-2 d-flex justify-content-center">
                <ul>
                    <li class="navbar-item active">
                        <a href="/">home</a>
                    </li>

                    <li class="navbar-item ">
                        <a href="#">services</a>
                    </li>

                    <li class="navbar-item ">
                        <a href="/about">about</a>
                    </li>

                    <li class="navbar-item ">
                        <a href="/contact">contact</a>
                    </li>


                </ul>

            </div>


            <div class="col-3 site-logo d-flex justify-content-end ">

                <a href="login" style="display:none" ref="login_ref"></a>
                <div style="cursor:pointer " @click="$refs.login_ref.click()" >

                    <img src="/img/icons/login.png" width="30" data-tooltip="tooltip" data-placement="left"
                        data-title="Login / Register">
                </div>
               
         <!--   <div class="dropdown-menu dropdown-menu-right" style="width:250px;padding: 10px; padding-bottom: 10px;">
                <h3 class="text-center" style="color:gray">Login</h3>
                <form method="POST" action="login">
                <input type="hidden" name="_token" :value="crsf">
                    <input class="form-control login" type="text" name="email" placeholder="Username..">
                    <input class="form-control login mt-2" type="password" name="password" placeholder="Password..">
                    <span class="text-left" style="color:gray;font-size:12px; margin-top:-10px;">
                        Don't Have An Account ! <a href="/register" style="font-size:8px;color:#3490dc;" >Click Here</a>
                    
                    </span>
                    <input class="btn btn-primary w-100 mt-4 " type="submit" name="submit" value="Login">
                </form>
            </div>

        -->
        
                <div class="ml-2 mr-4 " style="cursor:pointer" data-toggle="modal" data-target="#CartModal">

                    <img src="/img/icons/add_to_cart.png" width="30" data-tooltip="tooltip" data-placement="right"
                        data-title="Click To View Cart">
                    <span class="cart_items_count bg-success text-white ">{{medicines.length}}</span>
                </div>

            </div>
        </div>


        <!-- Extra large modal -->


        <div class="modal fade " id="CartModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">

                <div class="modal-content">
                    <div class="modal-header ">
                        <div class="w-100 text-center">
                            <h3 class="modal-title ">Medicines Cart</h3>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="container mb-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                   <!-- <th scope="col"> </th>-->
                                                    <th scope="col" class="text-center">Medicine</th>
                                                    <th scope="col" style="width:20px;">Available</th>
                                                    <th scope="col" style="width:8px;" class="text-center">Quantity</th>
                                                    <th scope="col" style="width:100px;" class="text-right">Price</th>
                                                    <th style="width:8px;"> </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(medicine,index) in medicines" :key="index">
                                                   <!-- <td><img src="https://dummyimage.com/50x50/55595c/fff" /> </td>-->
                                                    <td class="text-center">{{medicine.name}}</td>
                                                    <td>{{medicine.status}}</td>
                                                    <td style="width:130px;">
                                                        <input class="form-control" type="number" step="1" :value="medicine.quantity" 
                                                                :min="1" :max="medicine.max" @change="event => pushToCart(medicine.id,event)" />
                                                    </td>
                                                    <td class="text-right">{{medicine.price}} DA</td>
                                                    <td class="text-right" style="width:8px;">
                                                        <a href="#" class="delete" title="" data-tooltip="tooltip" data-original-title="Delete" @click="deleteMedicine(medicine.id)">
                                                            <img src="/img/icons/trash.png" width="24">
                                                        </a>
                                                    </td>
                                                </tr>


                                                
                                               
                                                
                                            </tbody>
                                        </table>

                                    </div>
                                        <div class="row" style="margin-right:50px;">
                                            <div class="col-10 text-right">Sub-Total</div>
                                            <div class="col-2 text-right pr-2">{{invoice.sub_total}} DA</div>
                                        </div>

                                        <div class="row" style="margin-right:50px;">
                                            <div class="col-10 text-right">Taxes</div>
                                            <div class="col-2 text-right pr-2">{{invoice.tax}} DA</div>
                                        </div>

                                        <div class="row" style="margin-right:50px;">
                                            <div class="col-10 text-right">Shipping</div>
                                            <div class="col-2 text-right pr-2">{{invoice.shipping}} DA</div>
                                        </div>

                                        <div class="row mt-2" style="margin-right:50px;">
                                            <div class="col-10 text-right text-primary"><strong>Total</strong></div>
                                            <div class="col-2 text-right pr-2 text-primary">{{invoice.total}} DA</div>
                                        </div>
                                </div>
                               
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                         <div class="col ">
                                    <div class="row">
                                        <div class="col-sm-12  col-md-6">
                                            <button class="btn btn-block btn-primary "  data-dismiss="modal">Continue Shopping</button>
                                        </div>
                                        <div class="col-sm-12 col-md-6 text-right">
                                            <button
                                                class="btn btn-block btn-success ">Checkout</button>
                                        </div>
                                    </div>
                                </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

</template>

<script>
    export default {
        props:["medicine_id"],
        mounted(){
            this.getMedicines()
            
        },
        data(){
            return {
                
                crsf:$("meta[name='csrf-token']").attr("content"),
                medicines:[],
                invoice:{
                    sub_total:0,
                    tax:0,
                    shipping:0,
                    total:0,
                },

            }
        },
        watch:{
            medicine_id: function(){
                //console.log(this.medicine_id);
                this.pushToCart(this.medicine_id);

            },
           
        },
        methods:{
            pushToCart: function(id,event=null){
               let quantity=1;
               if (event != null) {
                   quantity = parseInt(event.target.value)
                }

                axios.post('/api/cart',{id:id,quantity:quantity})
                    .then((Response)=>{
                       
                        this.getMedicines()

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
           
            deleteMedicine : function(id){
                //remove the item form the session cart
                axios.delete('/api/cart/'+id)
                    .then((Response)=>{
                       
                          this.getMedicines()

                    

                        

                         

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

            /**
             * get medicines stored in the redis session
             */
            getMedicines: function(){

                 axios.get('/api/cart')
                    .then((Response)=>{
                       
                          this.medicines = Response.data.data
                          this.invoice = Response.data.invoice

                    

                        

                         

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

            }

        }

    }

</script>



<style scoped>
    .cart_items_count {
        border-radius: 4px;
        padding: 4px;

    }

    .navbar-item {
        padding: 10px;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #25262a;
        font-size: 15px;
        display: inline;
        text-decoration: none !important;
    }

    .navbar-item>a {
        color: #25262a;
        text-decoration-line: none;
    }

    .navbar-item.active {
        border-bottom: 2px solid;
        font-weight: 600;

    }

    .site-logo a {
        text-transform: uppercase;
        letter-spacing: .2em;
        font-size: 22px;
        color: #000 !important;
        font-weight: 900
    }

    .site-logo a:hover {
        text-decoration: none
    }

</style>
