<template>




    <div>
        <input id="address" type="text" class="form-control " name="address" required autocomplete="address" v-model="address">
        <div class="row pt-2" @click="showMap" style="cursor:pointer">
            <img src="/img/icons/marker.png" width="30" height="30" data-tooltip="tooltip"
                data-title="Click Here To Point the Geo Coordinates">
            <span style="font-size:13px;color:gray;padding-top:15px;" v-html="prettyGeoCoordinates"
                data-tooltip="tooltip" data-deplacement="right" data-title="">


            </span>
            <input type="text" name="geo_coord" v-model="geo_coord" hidden>
            <geoCoordinates :title="'Residence Coordinates'" :modalId="geoModal" :focusMark="true"
                :coord_input="geo_coord" @coordUpdate="coordinatesUpdated"></geoCoordinates>

        </div>
    </div>






</template>

<script>
    export default {

        data() {
            return {
                geoModal: "geoModalCreate",
                geo_coord: "",
                address:"",
            }
        },
        computed: {
            prettyGeoCoordinates: function () {
                let lat = 0,
                    lng = 0



                if (RegExp("[-+]?[0-9]*\.?[0-9]+,[-+]?[0-9]*\.?[0-9]+").test(this.geo_coord)) {
                    lat = this.geo_coord.split(",")[0]
                    lng = this.geo_coord.split(",")[1]
                } else {
                    this.geo_coord = ""
                    return "Point Us To Your Residence's Geo Coordinates so that <br>you would benefit from our shipping Service."

                }

                let result = ""
                if (lat >= 0)
                    result = lat + "<strong class='text-primary'> N </strong>"
                else
                    result = Math.abs(lat) + "<strong class='text-danger'> S </strong>"

                if (lng >= 0)
                    result += lng + "<strong class='text-primary'> E </strong>"
                else
                    result += Math.abs(lng) + "<strong class='text-danger'> W </strong>"

                return result;

            }
        },
        methods: {
            coordinatesUpdated: function (e) {
                if (RegExp("[-+]?[0-9]*\.?[0-9]+,[-+]?[0-9]*\.?[0-9]+").test(e)) {
                    this.geo_coord = e

                    //we correct the address with the the nearest address to the geo coordinates
                    var platform = new H.service.Platform({
                        /*"app_id": "APP-ID-HERE",
                        "app_code": "APP-CODE-HERE"*/
                        apikey: 'rGOxW4XB821m0ZE1YmUg3G9DzmwVjU57MflxIQqCdZk'
                    });
                    var geocoder = platform.getGeocodingService();


                    geocoder.reverseGeocode({
                        language: "en",
                        mode: "retrieveAddresses",
                        maxresults: 1,
                        prox: this.geo_coord
                    }, data => {
                        let address = data.Response.View[0].Result[0].Location.Address;
                        
                        this.address = address.State+", "+((address.County == address.City)? "":address.County)+" "+ address.Label;

                    }, error => {
                        console.error(error);
                    });


                }
            },
            showMap: function () {
                $("#" + this.geoModal).modal("show")

            },
        }

    }

</script>

<style scoped>



</style>
