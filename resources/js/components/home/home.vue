<template>
    <div>
        <div class="container">
            <home_navbar></home_navbar>




        </div>
        <div style="background-color:#f8f9fa;" class=" p-2">
            <div class=" container p-2">
                 <span v-if="medicines_meta != null" style="color:gray;font-size:12px;margin-left:30px;">
                <strong class="text-primary">{{medicines_meta.total}}</strong> matches has been found 
                <span  v-if="medicines_meta.total > 0">
                      / Showing  <b class="text-primary">{{medicines_meta.per_page}}</b> Medicines Per Page
                </span>
                <span v-if="medicines_meta.from != null && medicines_meta.to != null">
                     / From <b class="text-primary">{{medicines_meta.from}}</b> To <b class="text-primary">{{medicines_meta.to}}</b>
                </span>
            </span>
                
            </div>

        </div>
        <home_filter @filterApplied="filterApplied"></home_filter>


        <div class="container">
           <div class="d-flex justify-content-center loading-icon" v-if="getMedicines_status"  >

            <img  src="/img/icons/load.gif" style="position:absolute;padding-top:100px;z-index:10;" >
           </div>
            <div class="row" v-if="medicines !=null">

                <div class="col-lg-4 " v-for="(medicine,index) in medicines" :key="index">
                    <div class="panel  Item" id="527" :title="medicine.medicine_name + medicine.family">

                        <div class="panel-body Item_Panel_Picture text-center">
                            <img :src="'/img/medicines/'+medicine.Img">
                        </div>

                        <div class="panel-body Item_Panel_Body ">
                            
                                
                                <span class=" Item_Panel_Name text-center ellipis text-primary" style="">{{medicine.medicine_name}} / {{ medicine.dosage }} mg</span>
                                <span class=" Item_Panel_Name text-center ellipis text-danger" style="">{{medicine.family}}</span>
                            <div class="text-center" style="border:0px solid grey;margin-bottom: 5px;">
                                <span class="text-center Item_Panel_Price ">

                                    
                                    <span style="font-size: 15px;color: #29cf42;">{{medicine.unit_price}} DA</span>
                                </span>

                            </div>
                            


                        </div>

                        <div class="d-flex justify-content-center">
                            <button class="add_to_cart text-center" @click="addToCart(medicine.id)">
                                ADD TO CART
                            </button>
                        </div>
                    </div>

                </div>

            </div>


            <div  class="d-flex justify-content-center" >

                <ul class="pagination" v-if="medicines_meta != null" >
                    <li class="page-item disabled"><a href="#"  @click="(paginationCurrent > 1)? paginationCurrent--:true; " >Previous</a></li>
                    <li class="page-item" v-if="paginationCurrent > 2"><a href="#" @click="paginationCurrent -=2 " class="page-link">{{paginationCurrent -2}}</a></li>
                    <li class="page-item" v-if="paginationCurrent > 1"><a href="#" @click="paginationCurrent--" class="page-link">{{paginationCurrent -1}}</a></li>
                    <li class="page-item active"><a href="#" class="page-link">{{paginationCurrent}}</a></li>
                    <li class="page-item"><a href="#" v-if="paginationCurrent+1 <= medicines_meta.last_page" @click="paginationCurrent++ " class="page-link">{{paginationCurrent+1}}</a></li>
                    <li class="page-item"><a href="#" v-if="paginationCurrent+2 <= medicines_meta.last_page" @click="paginationCurrent+=2 " class="page-link">{{paginationCurrent+2}}</a></li>
                    <li class="page-item" ><a href="#" @click="(paginationCurrent+1 <= medicines_meta.last_page )?paginationCurrent++:'' " class="page-link">Next</a></li>
                </ul>
            </div>
        </div>

    </div>


</template>

<script>
    export default {
        mounted(){
            this.getMedicines();
        },
        data() {
            return {
                filter:null,
                paginationCurrent : 1,
                medicines: null,

                medicines_meta:null,
                getMedicines_status:true,


            }
        },
        watch:{
            filter : function(filter){
                this.getMedicines()
            },
             paginationCurrent : function(page){
                    this.getMedicines();
            },
            
        },



        methods: {
            filterApplied: function (filter) {
                this.filter = filter;
                this.getMedicines()

                
            },
            addToCart: function(id){

            },
            /**
             * get medicines from the server's api
             */
            getMedicines: function(){
            let filter = this.filter
            this.getMedicines_status = true;
            axios.get("/api/medicines_", {
                
                params:{
                        
                    filter,
                    page:this.paginationCurrent
                    }
                   
                })
                    .then((response) => {
                    this.getMedicines_status = false;
                    this.medicines = response.data.data;
                    this.medicines_meta = response.data.meta;
                    

                    
                }, (error) => {
                    console.log(error);
                });

           

        },
        },
        components: {}

    }

</script>
<style scoped>

    .loading-icon{
        display: none;
        
        
        
    }



    .Item {
        width: 250px;
        margin: 30px;

        border-radius: 5px;
        -webkit-box-shadow: 0px 0px 1px 0px #5d5e75;
        -moz-box-shadow: 0px 0px 1px 0px #5d5e75;
        box-shadow: 0px 0px 1px 0px #5d5e75;

    }


    .Item_Panel_Picture {
        padding: 10%;
        margin: 10px;
        margin-bottom: 0px;
        height: 250px;
        border: 0px solid black;
    }

    .Item_Panel_Picture img {
        max-height: 100%;
        max-width: 100%;
        height: 100%;
        width: 100%;
    }


    .Item_Panel_Body {
        border: 0px solid black;
        padding: 0px;
        padding-left: 15px;
        padding-right: 15px;
        margin: 0;
    }

    .Item_Panel_Price {
        font-size: 15px;
        color: orange;

        width: 200px;
    }



    /****** text wrapper class*******/
    .ellipis {
        overflow: hidden;
        -ms-text-overflow: ellipsis;
        -o-text-overflow: ellipsis;
        text-overflow: ellipsis;
        display: block;
        white-space: nowrap;
    }



    .Item_Panel_Name {
        font-family: Oxygen, Helvetica, Arial, tahoma, Microsoft YaHei, Hiragino Sans GB, sans-serif;
        /*margin-left: 41px;
        margin-right: 41px;
        width: 150px;
        font-weight: bold;
        font-size: 16px;*/
        color:gray;


    }


    .add_to_cart {
        background-color: #00a8ff;
        outline: none;
        color: #fff;
        font-size: 14px;
        height: auto;
        font-weight: normal;
        padding: 3px;

        border: 0px;

        border-color: #59B2E6;
        height: 25px;
        width: 228px;
        background: lightseagreen;
        margin: 0px;

        border: 0px;

        box-shadow: 0 0 1px #ccc;
        margin-bottom: 5px;
        /*-webkit-transition-duration: 0.3s;
    -webkit-box-shadow: 0px 0px 0 0 #31708f inset , 0px 0px 0 0 #31708f inset;*/
    }

    .add_to_cart:hover {
        box-shadow: 114px 0px 0 0 #29cf42 inset, -114px 0px 0 0 #29cf42 inset;

    }

    .items_display .row .col-xs-4 {
        margin-left: 0px;
    }



    .discount {
        width: 100px;
        position: absolute;
        transform: rotate(20deg);
        margin-left: 0px;
        margin-top: 8px;
        z-index: 5;
        color: white;


    }

    .discount span {
        position: absolute;
        margin-top: 33px;
        margin-left: 18px;
        z-index: 10;
        font-weight: 700;
        width: 100px;
        text-align: center;
        transform: rotate(27deg);

    }


    .discount img {
        width: 120px;
        transform: rotate(20deg);
        -webkit-filter: drop-shadow(3px 3px 3px #333);
        filter: drop-shadow(3px 3px 3px #333);

    }


    .item_rattings {
        display: block;
        margin-bottom: -5px;
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

</style>
