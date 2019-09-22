<template>
    <div class="mt-2">
        <div class="row mb-3">

            <div class="col-xl-3 col-ml-3 col-md-6 mt-5 ">
                <div class="single-report product-sold-report">
                    <div class="s-sale-inner  mb-3 pl-3 pr-3 pt--30 ">
                        <div class="s-report-title d-flex justify-content-between">
                            <h4 class="header-title mb-0">Product Sold</h4>
                            

                            <select class="custome-select border-0 pr-3" v-model="main_statistics.controllers.product_sold"  >
                                <option selected  value="today">Today</option>
                                <option  value="last_week" >Last 7 Days</option>
                                <option  value="last_month" >Last Month</option>
                                <option  value="last_year" >Last Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="row pb--20 ">
                        <span class="w-100 text-center">
                            <h1 class="report-value ">
                                {{main_statistics_product_sold}}
                            </h1>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-ml-3 col-md-6 mt-5">
                <div class="single-report gross-profit-report">
                    <div class="s-sale-inner  mb-3 pl-3 pr-3 pt--30 ">
                        <div class="s-report-title d-flex justify-content-between">
                            <h4 class="header-title mb-0">Gross Profit</h4>
                            

                            <select class="custome-select border-0 pr-3"  v-model="main_statistics.controllers.gross_profit" >
                                <option selected  value="today">Today</option>
                                <option  value="last_week" >Last 7 Days</option>
                                <option  value="last_month" >Last Month</option>
                                <option  value="last_year" >Last Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="row pb--20 ">
                        <span class="w-100 text-center ">
                            <h1 class="report-value ">
                                {{main_statistics_gross_profit}} DA
                            </h1>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-ml-3 col-md-6 mt-5">
                <div class="single-report orders-report">
                    <div class="s-sale-inner  mb-3 pl-3 pr-3 pt--30 ">
                        <div class="s-report-title d-flex justify-content-between">
                            <h4 class="header-title mb-0">Delivery Orders</h4>
                            

                            <select class="custome-select border-0 pr-3" v-model="main_statistics.controllers.delivery_orders" >
                                <option selected  value="today">Today</option>
                                <option  value="last_week" >Last 7 Days</option>
                                <option  value="last_month" >Last Month</option>
                                <option  value="last_year" >Last Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="row pb--20 ">
                        <span class="w-100 text-center ">
                            <h1 class="report-value ">
                                {{main_statistics_delivery_orders}}
                            </h1>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-ml-3 col-md-6 mt-5">
                <div class="single-report new-customers-report">
                    <div class="s-sale-inner  mb-3 pl-3 pr-3 pt--30 ">
                        <div class="s-report-title d-flex justify-content-between">
                            <h4 class="header-title mb-0">Customers</h4>
                            

                            <select class="custome-select border-0 pr-3" v-model="main_statistics.controllers.customers"  >
                                <option selected  value="today">Today</option>
                                <option  value="last_week" >Last 7 Days</option>
                                <option  value="last_month" >Last Month</option>
                                <option  value="last_year" >Last Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="row pb--20 ">
                        <span class="w-100 text-center ">
                            <h1 class="report-value ">
                                {{main_statistics_customers}} 
                            </h1>
                        </span>
                    </div>
                </div>
            </div>
            


        </div>


        <div class="row">
            <h4 class="pl-5  " style="color:gray">Histogram of sales numbers of the last month :  </h4>
            
                <canvas id="histogram_1" height="150"></canvas>

        </div>

        <div class="row">
            <h4 class="pl-5  " style="color:gray">Histogram of sales numbers of the last month :  </h4>
            
                <canvas id="histogram_2" height="150"></canvas>

        </div>

    </div>
</template>


<script>
    import chart from 'chart.js'
    export default {

        mounted() {
           this.Listen();
           this.chartInit();
           
        },
        data() {
            return {
                listener:{
                    subscription:{
                        subscribed:false
                    }
                },
                
                main_statistics:{
                    controllers:{ //0 => today , 1 => last 7 days , 2 => last month , 3 => last year
                        product_sold:'today',
                        gross_profit:'today',
                        delivery_orders:'today',
                        customers:'today',
                        
                    },
                   
                    model:{
                        product_sold:{//unit is N/A
                            today:0,
                            last_week:12,
                            last_month:325,
                            last_year:145,
                        },
                        gross_profit:{//unit is DA
                           today:0,
                            last_week:12,
                            last_month:325,
                            last_year:145,
                        },
                        delivery_orders:{
                           today:0,
                            last_week:12,
                            last_month:325,
                            last_year:145,
                        },
                        customers:{
                            today:0,
                            last_week:12,
                            last_month:45,
                            last_year:145,
                        }
                    }
                    
                },
                chart :null,
                histo1_model:{
                   
                                labels:[],
                                datasets: [
                                    {
                                        label: "Number Of Sales",
                                        backgroundColor:[
                                            "#191970",
                                            "#191970",
                                            "#000080",
                                            "#000080",
                                            "#000080",
                                            "#6495ED",
                                            "#6495ED",
                                            "#483D8B",
                                            "#483D8B",
                                            "#6A5ACD",
                                            "#6A5ACD",
                                            "#7B68EE",
                                            "#7B68EE",
                                            "#A52A2A",
                                            "#E9967A",
                                            "#FFA07A",
                                            "#FFA07A",
                                            "#FFA500",
                                            "#FF8C00",
                                            "#FF8C00",
                                            "#FF7F50",
                                            "#F08080",
                                            "#F08080",
                                            "#FF6347",
                                            "#FF4500",
                                            "#FF4500",
                                            "#FF0000",
                                            "#FF69B4",
                                            "#FF69B4",
                                            "#FF1493",
                                        ],
                                        data:[]
                                    }
                                ]
                        
                    
                },
                histo2_model:{
                   
                                labels:[],
                                datasets: [
                                    {
                                        label: "Profit",
                                        backgroundColor:[
                                            "#191970",
                                            "#191970",
                                            "#000080",
                                            "#000080",
                                            "#000080",
                                            "#6495ED",
                                            "#6495ED",
                                            "#483D8B",
                                            "#483D8B",
                                            "#6A5ACD",
                                            "#6A5ACD",
                                            "#7B68EE",
                                            "#7B68EE",
                                            "#A52A2A",
                                            "#E9967A",
                                            "#FFA07A",
                                            "#FFA07A",
                                            "#FFA500",
                                            "#FF8C00",
                                            "#FF8C00",
                                            "#FF7F50",
                                            "#F08080",
                                            "#F08080",
                                            "#FF6347",
                                            "#FF4500",
                                            "#FF4500",
                                            "#FF0000",
                                            "#FF69B4",
                                            "#FF69B4",
                                            "#FF1493",
                                        ],
                                        data:[]
                                    }
                                ]
                        
                    
                }
               


            }
        },
        watch: {
           'listener.subscription.subscribed' :function () {
               if(this.listener.subscription.subscribed)
                this.init_event()
           }
        },
        computed:{
            main_statistics_product_sold:function(){
                var option = this.main_statistics.controllers.product_sold //get the select option from the binding object property
               return this.main_statistics.model.product_sold[option];
            },
            main_statistics_gross_profit:function(){
               var option = this.main_statistics.controllers.gross_profit //get the select option from the binding object property
               return this.main_statistics.model.gross_profit[option];
            },
            main_statistics_delivery_orders:function(){
               var option = this.main_statistics.controllers.delivery_orders //get the select option from the binding object property
               return this.main_statistics.model.delivery_orders[option];
            },
            main_statistics_customers:function(){
              var option = this.main_statistics.controllers.customers //get the select option from the binding object property
               return this.main_statistics.model.customers[option];
            }
        },





        methods: {

            /**
             * initiate event 
             */
            init_event: function(){
                axios.get("/api/init_stat_event/").then((response)=>{
                   // console.log("1")
                });
            },
            Listen: function(){ 
                
               this.listener = Echo.channel('statistics').listen("InventoryUpdate",(e)=>{
                   console.log(e);
                   this.main_statistics.model = e.main_statistics;
                   this.histo1_model.datasets[0].data = e.histo1.data;
                   this.histo1_model.labels = e.histo1.dates;

                   this.histo2_model.datasets[0].data = e.histo2.data;
                   this.histo2_model.labels = e.histo2.dates;
                   this.chartInit();
               })
               

            

            },
            
            chartInit: function(){
                var histogram_1 = document.getElementById("histogram_1").getContext('2d'),options = {}
                    var lineChart = new Chart(histogram_1,{
                                        type: "bar",
                                        data: this.histo1_model, 
                                    });
                var histogram_2 = document.getElementById("histogram_2").getContext('2d'),options = {}
                    var lineChart2 = new Chart(histogram_2,{
                                        type: "bar",
                                        data: this.histo2_model, 
                                    });
                
            }

              
        }
    }

</script>

<style scoped>


.single-report {
    overflow: hidden;
    position: relative;
    margin:5px;
    cursor: pointer;
}
.single-report:hover/deep/{
    margin:0px;
}
.gross-profit-report,.gross-profit-report/deep/.custome-select{
    background: #e91e63;
    
}

.product-sold-report,.product-sold-report/deep/.custome-select{
    background: #9c27b0;
    
}

.orders-report,.orders-report/deep/.custome-select{
    background: #673ab7;

}

.new-customers-report,.new-customers-report/deep/.custome-select{
    background: #3f51b5;

}

.single-report:hover/deep/.report-value{
    color: #000000;
}

.report-value{
    color: #0000009c;
}


.sales-style-two .single-report {
    background: #fff;
    overflow: hidden;
    position: relative;
    padding: 10px 15px 10px;
}

.s-report-title {
        margin-bottom: 19px;
    }
   
    .header-title {
    font-family: 'Lato', sans-serif;
    font-size: 18px;
    font-weight: 600;
    letter-spacing: 0;
    color: white;
    text-transform: capitalize;
    margin-bottom: 17px;
}

.s-report-title .header-title {
        font-size: 15px;
    }
.custome-select {
    margin-right: -30px;
width: 110px;
    font-size: 13px;
    color: white;
    font-weight: 500;
    letter-spacing: 0;
}

.custome-select>option{

    color:gray;
    background-color: white;
}




</style>
