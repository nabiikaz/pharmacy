<template>

    <div>

            <div class="modal fade " id="CartModal" tabindex="-1" role="dialog" >
                     
            <div class="modal-dialog modal-lg">

                <div class="modal-content">
                    <div class="modal-header ">
                        <div class="w-100 text-center">
                            <h3 class="modal-title ">Customer Coordinates</h3>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="CartModalBody">
                               <div id="map" ></div>
                        


                    </div>
                    <div class="modal-footer">
                         
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>


<script>
import { timeout } from 'q';

export default {
    name:"geoCoordinates",
    props:["coord_input","unique_id","focusMark"],
    mounted() {
        this.init_map()
        var self = this
        $("#CartModal").mouseover(function(){
            if(self.map != null)
                self.map.getViewPort().resize()
        })

        
        
        
        
      
            
        
    },

    data(){
        return {
            apikey:'rGOxW4XB821m0ZE1YmUg3G9DzmwVjU57MflxIQqCdZk',
            coord : null,
            markerObject:null,
            map:null,
        } 
    },
    watch:{
        coord: function(new_coord){
            
            this.$emit("coordUpdate",new_coord.lat+","+new_coord.lng);
            if(this.map.getObjects().length > 0)
                this.map.removeObject(this.markerObject)

            var Marker = new H.map.Marker(new_coord)
            if(this.map != null)
                this.markerObject = this.map.addObject(Marker)

            if(this.focusMark)
                this.map.getViewModel().setLookAtData({position:new_coord},true)
            //raise the coord event
            
            
            
                



        },
        coord_input: function(new_coord){
            

            if(!RegExp("[-+]?[0-9]*\.?[0-9]+,[-+]?[0-9]*\.?[0-9]+").test(new_coord)){
                if(this.map.getObjects().length > 0)
                    this.map.removeObject(this.markerObject)
                return;
            }
            
            let tmp = new_coord.split(",")
            var lat = parseFloat(tmp[0])
            var lng  = parseFloat(tmp[1])
            
            this.coord = {lat:lat,lng:lng}
        },
        
    },
    methods:{
        
        setUpClickListener(map) {
            // Attach an event listener to map display
            // obtain the coordinates and display in an alert box.
           var self = this;
           
           map.addEventListener('tap', function (evt) {
               self.coord = map.screenToGeo(evt.currentPointer.viewportX,evt.currentPointer.viewportY); 
            })
        },

        init_map(){
            
            document.getElementById('map').innerHTML = ""
                        /**
             * Boilerplate map initialization code starts below:
             */

            //Step 1: initialize communication with the platform
            // In your own code, replace variable window.apikey with your own apikey
            var platform = new H.service.Platform({
            apikey: 'rGOxW4XB821m0ZE1YmUg3G9DzmwVjU57MflxIQqCdZk'
            });
            var defaultLayers = platform.createDefaultLayers();

            //Step 2: initialize a map
            this.map = new H.Map(document.getElementById('map'),
            defaultLayers.vector.normal.map,{
            center: {lat: 34.900934001110215, lng: -1.3523289793706876},
            zoom: 10,
           
            pixelRatio: window.devicePixelRatio || 1
            });
            // add a resize listener to make sure that the map occupies the whole container
            window.addEventListener('resize', () => this.map.getViewPort().resize());

            
            //Step 3: make the map interactive
            // MapEvents enables the event system
            // Behavior implements default interactions for pan/zoom (also on mobile touch environments)
            var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(this.map));

            
            this.setUpClickListener(this.map)

            //this resiez is key to make the map show when embeded in modals or popups
            
            // Helper for logging events
        }
        
        
       
    }

    
}
</script>



<style scoped>
    @import url("https://js.api.here.com/v3/3.1/mapsjs-ui.css");

#map {
    width: 100%;
    height: 400px;
    background: grey;
}


</style>

