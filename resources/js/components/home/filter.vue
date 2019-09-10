<template>
    <div class="">
            <img src="/img/icons/filter.png" width="45" class="rounded-circle border-dark dropdown-toggle"
             data-flip="false" id="dropdownMenuLink" data-toggle="dropdown" aria-expanded="true" 
            data-tooltip="tooltip" data-title="Filter Control" data-placement="right" />

                <div class="filter dropdown-menu"   >
                    <h3 class="text-center ">Filter Control </h3>
                    <hr >
                    <input type="text" class="form-control mb-1" placeholder="Search Keyword ..." v-model="filter.search" >
                    <label style="color:grey;font-size:12px;margin-bottom:-5px;">Expiration Date Range :</label>
                    <rangedate-picker  i18n="EN" @selected="date_slider"
                        :captions="{'title':'Expiration Date Range','ok_button':'Apply'}"></rangedate-picker>
                  <label style="color:grey;font-size:12px;margin-bottom:-5px;">Refund Percentage :
                      <strong class="text-success">{{filter.refund_range_value[0]}}% </strong>,<strong class="text-primary" > {{filter.refund_range_value[1]}}%</strong>
                    </label>
                    <VueRangeSlider actions-keyboard="" v-model="filter.refund_range_value"  :min="refund_min" :max="refund_max"
                        :formatter="refund_range_formatter" :tooltip-merge="refund_tooltipMerge"
                        :enable-cross="price_enableCross">
                    </VueRangeSlider>

                    <label style="color:grey;font-size:12px;margin-bottom:-5px;">Price Range :
                      <strong class="text-success">{{filter.price_range_value[0]}} DA </strong>,<strong class="text-primary" > {{filter.price_range_value[1]}} DA</strong>


                    </label>
                    <VueRangeSlider actions-keyboard="" v-model="filter.price_range_value"  :min="price_min" :max="price_max"
                        :formatter="price_range_formatter" :tooltip-merge="price_tooltipMerge"
                        :enable-cross="price_enableCross">
                    </VueRangeSlider>


                    <label style="color:grey;font-size:12px;margin-bottom:-5px;">Dosage Range :
                      <strong class="text-success">{{filter.dosage_range_value[0]}} mg </strong>,<strong class="text-primary" > {{filter.dosage_range_value[1]}} mg</strong>

                    </label>
                    <VueRangeSlider actions-keyboard="" v-model="filter.dosage_range_value" :min="dosage_min" :max="dosage_max"
                        :formatter="dosage_range_formatter" :tooltip-merge="dosage_tooltipMerge"
                        :enable-cross="dosage_enableCross" :step="0.1">
                    </VueRangeSlider>
                   
                    <label style="color:grey;font-size:12px;margin-bottom:-5px;">Medicines Forms :</label>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" value="comprime" id="comprime" v-model="filter.forms">
                                <label class="form-check-label" for="comprime">Comprimé</label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" value="gelule" id="gelule" v-model="filter.forms">
                                <label class="form-check-label" for="gelule">Gélule</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" value="collyre" id="collyre" v-model="filter.forms">
                                <label class="form-check-label" for="collyre">Collyre</label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" value="sirop" id="sirop" v-model="filter.forms">
                                <label class="form-check-label" for="sirop">Sirop</label>
                            </div>

                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-success w-75" @click="apply_filter">
                            <strong>Apply</strong>
                        </button>
                    </div>

                    
                </div>
                











    </div>

</template>

<script>
    
import VueSlider from 'vue-slider-component'
import 'vue-slider-component/theme/default.css'

    import VueRangedatePicker from 'vue-rangedate-picker'

    export default {
        mounted(){
            //this line prevent the dropdown from disapearning when clicked inside it .
            $(document).on('click', '.dropdown-menu', function (e) {
               e.stopPropagation();
            });

            $(document).on('click', '.calendar-btn-apply', function (e) {
               e.stopPropagation();
            });

            this.apply_filter();
            
        },
        data() {
            return {
                filter:{

                    refund_range_value: [0, 100],
                    price_range_value: [0, 10000],
                    dosage_range_value: [0, 1000],
                    start_date: null,
                    end_date: null,
    
                    forms:[],
                    search:"",
                }
               



            }
        },
        created() {

            this.refund_min = 0
            this.refund_max = 100
            this.refund_enableCross = false
            this.refund_tooltipMerge = false
            this.refund_range_formatter = value => `${value} %`

            this.price_min = 0
            this.price_max = 10000
            this.price_enableCross = false
            this.price_tooltipMerge = false
            this.price_range_formatter = value => `${value} DA`

            this.dosage_min = 0
            this.dosage_max = 1000
            this.dosage_enableCross = false
            this.dosage_tooltipMerge = false
            this.dosage_range_formatter = value => `${value} mg`





        },
        methods: {
           apply_filter:function(){
               this.$emit("filterApplied",this.filter)

           },
            date_slider: function (event) {
                let tmp = new Date(event.start);
                this.filter.start_date = tmp.getFullYear() + "-" + (tmp.getMonth() + 1) + "-" + tmp.getDate()
                tmp = new Date(event.end);
                this.filter.end_date = tmp.getFullYear() + "-" + (tmp.getMonth() + 1) + "-" + tmp.getDate()
            }
        },
        components: {
            "rangedate-picker": VueRangedatePicker,
            "VueRangeSlider": VueSlider,
        }

    }

</script>


<style lang="css" scoped>
    .calendar-root/deep/.input-date {
        width: 100%;
    }

   

    

    
  

</style>

<style scoped>
 .filter{
        width:25%;
        box-shadow: 2px 4px 7px -1px rgba(0,0,0,0.25);
        padding:20px;
        position: fixed;
        margin-left:30px;
        margin-top:-10px;
        z-index: 1;
        

        
    }
img{
    position: fixed;
    padding: 10px;
    background-color: #f7f7f7;
    z-index: 2;
    margin-top:-30px;
}
</style>
