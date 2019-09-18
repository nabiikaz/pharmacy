<template>
    <div>

        <div class="table-wrapper">
            <div class="table-title" style="background-color:#272b30;">
                <div class="row">
                    <div class="col-sm-4">
                        <h2>Deliveries <b>Manager</b></h2>
                    </div>
                    
                    
                </div>
            </div>
            <div class="row" v-if="false">
                <div class="col-sm-5">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Suppliers</a>
                        </li>

                       

                        <li class="nav-item">
                            <a class="nav-link " href="/dashboard/purchases">Purchases</a>
                        </li>



                    </ul>

                </div>

                
            </div>
            
            <div class="row">
                 
                <div class="col-lg-5 col-xs-12">
                    <div class="overflow-auto" style="height:363px;">
                         <table class="table table-striped table-hover " style="max-height:363px;" >
                                <thead class="mb-3" >
                                    <tr >
                                        <th class="text-center">Customer Name</th>
                                        <th class="text-center">Balance Due</th>
                                        <th style="width:80px;"></th>
                                    </tr>
                                </thead>

                                <tbody  style="overflow-y:scroll;height:363px;">
                                    <tr v-if="deliveries.length == 1" >
                                        <td style="width:2px;"><br></td>
                                    </tr>
                                    <tr v-for="(delivery,index) in deliveries" :key="index" @click="selected_delivery= delivery" :style="selected_delivery_id == delivery.id ?'color:white;background-color:#6cb2eb':''">
                                        <td class="text-center">{{delivery.customer_name}}</td>
                                        <td class="text-center">{{delivery.total_price}} DA</td>
                                        <td>
                                            <img src="/img/icons/checkmark.png" width="20" alt="" data-tooltip="tooltip" data-title="Confirm Payment" data-placement="top" style="cursor:pointer" @click="confirmPayment(delivery.id)">
                                            <img src="/img/icons/trash.png" width="20" alt="" data-tooltip="tooltip" data-title="Delete Delivery" data-placement="top" style="cursor:pointer">
                                        </td>
                                    </tr>

                                    
                                    

                                </tbody>
                            </table>
                    </div>
                    <span class="text-primary" style="font-size:13px" >Trip Summary : 
                        <strong class="text-success" v-if="routeDistance == 0" >You Have Arrived To The Delivery Destination</strong>
                        <strong v-if="routeDistance > 0">{{routeDistance> 1000 ? parseInt(routeDistance/1000)+' km , '+(parseInt(routeDistance)%1000)+"meters":parseFloat(routeDistance).toFixed(2)+' meters' }}</strong>
                        <strong class="text-danger" v-if="selected_delivery == null">No Delivery Destination Is Selected</strong>
                    </span>
                    

                </div>
                <div class="col-lg-7 col-xs-12">
                    <div id="map">c</div>
                    <div class="row pt-2 justify-content-center">
                            <button class="btn btn-primary w-75" :class="!startDelivery ? 'bg-primary':'bg-danger'" @click="Deliver">
                                {{!startDelivery ? 'Start Delivery':'Stop'}}
                            </button>
                        
                    </div>

                </div>
            </div>
            
            
           
        </div>


    </div>
</template>


<script>
   
import { timeout } from 'q'
    

    export default {
        mounted() {
            //display deliveries 
            this.getDeliveries()
            //initiate the map //////////////////////////////////
            this.init_map()
            var self = this
            $("#"+this.modalId).mouseover(function(){
                if(self.map != null)
                    self.map.getViewPort().resize()
            })

            ///////////////////////////////////////////////////////


            //initiate GPS real-time Tracking 
            this.init_gpsTracking()
           
            
        
            
            
            
        },

        data() {
            return {
                selected_delivery_id:-1,
                selected_delivery:null,
                deliveries:[],
                deliveries_coords:[],
                search:"",
                gps:{
                    lat:0,
                    lng:0
                },

                gps_marker:null,
               
              
                
                filter_flow:"Ascending",

                current_destination:null,
                destination_marker:null,
                
              
                polylineObject:null,

                startDelivery:false,
                gpsSimulation:true,
                gpsSimulationTimers:[],
                gpsSimulationSpeed:1000, //1000 ms between two coordinates
                gpsMarkerOnRouteZoom:false,
                routeShape:null,
                routeDistance:"N/A",
                routeShape_lastPoint:-1,
                gps_marker_object:null,
               

            }
        },
        watch:{
             search : function(val){
            this.paginationCurrent = 1

               

            },
           selected_delivery: function(){
                /**
                 * disable the zoom on the gps marker so that when the delivery stops the view chanages to bound both 
                 * the gps marker and the selected delivery marker 
                 * 
                 **/
                
               if(this.selected_delivery == null)
                    return
                this.selected_delivery_id = this.selected_delivery.id
                this.gpsMarkerOnRouteZoom =false
                this.destinationSelected()
           },
            filter_flow : function(){
               
            },
            startDelivery: function(){
                if(this.routeShape == null){
                    alert("Please Choose A Delivery Destination With A Valid Geo Coordinates")
                    return;
                }
                if(this.startDelivery){ //will start the delivery
                        //if gpsSimulation is true then start the simulation

                        if(this.gpsSimulation)
                            this.startGpsSimulation();
                }else{ //stop delivery
                

                 //activate the zoom on the gps marker so that when the gps stops the view stays where it left
                this.gpsMarkerOnRouteZoom = true
                        if(this.gpsSimulation)
                            this.stopGpsSimulation();

                        this.destinationSelected()
                }
            },
            gpsSimulationSpeed : function(){

                if(this.gpsSimulation){
                    this.stopGpsSimulation()
                    //this.startGpsSimulation()
                }

            },
            routeShape_lastPoint:function(){
                
            },
            routeShape: function(){
                //calculate the current Route Distance between where the last point of Route Visisted to the last point of the this.routeShape
                this.getCurrentRouteDistance()
            },
            
            gps :function(){
                console.log(this.gps)
            }
        },
        computed:{
            routeDistancee:function(){

            }
        },



        updated: function(){$('[data-tooltip=tooltip]').tooltip();}, 
        methods: {
            confirmPayment: function(delivery_id){
                axios.patch('/api/deliveries/'+delivery_id)
                    .then((Response)=>{
                        
                       //refresh Deliveries Display
                         this.getDeliveries();
                

                         

                    }).catch(error => {
                        
                        if(error.response){
                            /**
                             * the request was made and the server responded with  a
                             * status code that falls out of the range of 2**
                             *  */
                            console.log(error.response)
                            this.Message = "Error : "+ error.response.data.errors
                            this.MessageClass = "text-danger"
                             switch (error.response.status) {
                            case 404:

                                

                                
                                break;
                        
                            default:
                                break;
                            }   
                        }
                        
                    });
            },
           
            getDeliveries: function(){
                axios.get("/api/deliveries", {
                    params:{
                            
                        
                        }
                    
                    })
                        .then((response) => {
                        this.deliveries = response.data.deliveries ;
                        this.deliveries_coords = response.data.geo_coords ;
                        
                    }, (error) => {
                        console.log(error);
                    });
            },
            init_map(){
            
                document.getElementById('map').innerHTML = ""
                            /**
                 * Boilerplate map initialization code starts below:
                 */

                //Step 1: initialize communication with the platform
                // In your own code, replace variable window.apikey with your own apikey
                this.platform = new H.service.Platform({
                    apikey: 'rGOxW4XB821m0ZE1YmUg3G9DzmwVjU57MflxIQqCdZk'
                });
                var defaultLayers = this.platform.createDefaultLayers();

                //Step 2: initialize a map
                this.map = new H.Map(document.getElementById('map'),
                defaultLayers.raster.satellite.map,{
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

                
                //this.setUpClickListener(this.map)

                //this resiez is key to make the map show when embeded in modals or popups
                
                // Helper for logging events
        
        
            },
            init_gpsTracking(){
                var platform = new H.service.Platform({
                    apikey: 'rGOxW4XB821m0ZE1YmUg3G9DzmwVjU57MflxIQqCdZk'
                });

                var geocoder = platform.getGeocodingService();
                if(navigator.geolocation) {
                    //now we are watching the User GPS Changes
                    navigator.geolocation.watchPosition(position => {
                        //get the address of the where the gps is pointing to 
                        geocoder.reverseGeocode(
                            {
                                language:"en",
                                mode: "retrieveAddresses",
                                maxresults: 2,
                                prox: position.coords.latitude + "," + position.coords.longitude
                            }, data => {
                                //console.log(data.Response.View[0].Result[0].Location.Address.Label);
                                //alert("The nearest address to your location is:\n" + data.Response.View[0].Result[0].Location.Address.Label);
                            }, error => {
                                console.error(error);
                            }
                        );


                        this.gps = {lat:position.coords.latitude ,
                                    lng:position.coords.longitude}


                        
                        this.destinationSelected()
                        

                        



                    });
                } else {
                    console.error("Geolocation is not supported by this browser!");
                }
            },
            drawGpsMarker: function(){
                //draw gps marker 
                        //first we need to remove the marker from the previous position if it exist
                        if(this.map.getObjects().length > 0)
                            if(this.gps_marker != null){
                                
                                this.gps_marker = null
                            }

                        //2ndly draw the gps_marker 
                        
                        var gpsMarkerIcon = new H.map.Icon("/img/icons/delivery_marker.png",{size: {w: 56, h: 56}});      

                        
                        this.gps_marker = new H.map.Marker(this.gps,
                                        {icon: gpsMarkerIcon});

                        this.gps_marker_object = this.map.addObject(this.gps_marker);

                        //focus on the gps coordinates 
                        this.map.getViewModel().setLookAtData({position:this.gps,zoom:15},true)

            },
            drawDestinationMarker: function(){
                if(this.current_destination == null)
                            return;
                //first we need to remove the marker from the previous position if it exist
                        if(this.map.getObjects().length > 0)
                            if(this.destination_marker != null)
                                this.map.removeObject(this.destination_marker)

                        //2ndly draw the gps_marker 
                        
                        var destinationMarkerIcon = new H.map.Icon("/img/icons/destination_marker.png",{size: {w: 56, h: 56}});      
                        
                       
                        this.destination_marker = new H.map.Marker(this.current_destination,
                                        {icon: destinationMarkerIcon});

                        this.map.addObject(this.destination_marker);

                        //focus on the gps coordinates 
                        this.map.getViewModel().setLookAtData({position:this.current_destination},true)
            },
            clearMap: function(){
                var mapObjects = null
                if(this.map != null){
                    mapObjects = this.map.getObjects()
                    if(mapObjects.length > 0){
                      
                        this.map.removeObjects(mapObjects) 
                       
                        
                    }

                }
            },
            destinationSelected : function(){
                
              if(this.selected_delivery == null){
                  //if no delivery is selected then  [clear the map] and redraw the GPS Marker on the current GPS Coordinates
                    this.clearMap()
                    this.drawGpsMarker()
              }
                    

                this.clearMap()
                this.drawDestinationMarker()
                
                this.drawGpsMarker()
                        
                

                //this.init_gpsTracking()
                if(this.selected_delivery == null)
                    return;
               
                //console.log(this.selected_delivery.geo_coord)
                if(!RegExp("[-+]?[0-9]*\.?[0-9]+,[-+]?[0-9]*\.?[0-9]+").test(this.selected_delivery.geo_coord)){
                    alert("This Customer Does Not Have Geo Coordinates ,, Calling Customer for guidance is Advised Tel:"+this.selected_delivery.tel)
                    return;
                }
                
                var coord_str_split = this.selected_delivery.geo_coord.split(",")
                this.current_destination = {lat:parseFloat(coord_str_split[0]),lng:parseFloat(coord_str_split[1])}
                
                this.drawDestinationMarker();
                this.calculateRouteFromAtoB(this.selected_delivery.geo_coord,this.selected_delivery.tel)
            },


            calculateRouteFromAtoB : function (destination,tel='N/A') {
                if(!RegExp("[-+]?[0-9]*\.?[0-9]+,[-+]?[0-9]*\.?[0-9]+").test(destination)){
                    alert("This Customer Does Not Have Geo Coordinates ,, Calling Customer for guidance is Advised Tel:"+tel)
                    return;
                }
                
                var router = this.platform.getRoutingService(),
                    routeRequestParams = {
                    mode: 'fastest;car',
                    representation: 'display',
                    routeattributes : 'waypoints,summary,shape,legs',
                    maneuverattributes: 'direction,action',
                    waypoint0: this.gps.lat+","+this.gps.lng, // Brandenburg Gate
                    waypoint1: destination,  // Friedrichstraße Railway Station i.e "12.54641,11.54641"
                    //waypoint2: "34.989863041487766,-1.6352269285894379"  // Friedrichstraße Railway Station i.e "12.54641,11.54641"
                    };


                    router.calculateRoute(
                        routeRequestParams,
                        this.onSuccess,
                        this.onError
                    );
                    
            },
            onSuccess: function(result) {
                var route = result.response.route[0];
                console.log("Route Calculation result")
                console.log(result.response)
                
                /*
                * The styling of the route response on the map is entirely under the developer's control.
                * A representitive styling can be found the full JS + HTML code of this example
                * in the functions below:
                */
                this.addRouteShapeToMap(route.shape);
                /*addManueversToMap(route);

                addWaypointsToPanel(route.waypoint);
                addManueversToPanel(route);
                addSummaryToPanel(route.summary);*/
                // ... etc.
            },

            onError: function(error) {
                alert('Can\'t reach the remote server');
            },

            addRouteShapeToMap: function(routeShape){
                var lineString = new H.geo.LineString(),
                    //routeShape = route.shape,
                    polyline,
                    lookAtDataOptions

                //save the routeShape coordinates For later Moving on Road Simulations
                this.routeShape = routeShape
                //console.log(route)

                routeShape.forEach(function(point) {
                    var parts = point.split(',');
                    lineString.pushLatLngAlt(parts[0], parts[1]);
                });

                polyline = new H.map.Polyline(lineString, {
                    style: {
                    lineWidth: 4,
                    strokeColor: 'rgba(0, 128, 255, 0.7)'
                    }
                });

                
                
                // Add the polyline to the map
                this.polylineObject = this.map.addObject(polyline);
                lookAtDataOptions = {bounds: polyline.getBoundingBox()}
                if(this.gpsMarkerOnRouteZoom)
                lookAtDataOptions = {position:this.gps,zoom:15}
                
                    

                // And zoom to its bounding rectangle
                this.map.getViewModel().setLookAtData(lookAtDataOptions,true);
            },
            getCurrentRouteDistance: function() {
                if(this.routeShape == null)
                    return;
                if(this.routeShape.length == 1)
                {
                    
                this.routeDistance = 0;
                return

                }
                
                var lineString = new H.geo.LineString(),
                    polyline

                this.routeShape.forEach(function(point) {
                    var parts = point.split(',');
                    lineString.pushLatLngAlt(parts[0], parts[1]);
                });

                polyline = new H.map.Polyline(lineString);


                const geometry = polyline.getGeometry();
                let distance = 0;
                let last = geometry.extractPoint(0);
                for (let i=1; i < geometry.getPointCount(); i++) {
                    const point = geometry.extractPoint(i);
                    distance += last.distance(point);
                    last = point;
                }
                if (polyline.isClosed()) {
                    distance += last.distance(geometry.extractPoint(0));
                }
                //console.log((distance/1000).toFixed(3))
                this.routeDistance = distance;
            },


            Deliver : function(){
                if(this.routeShape == null){
                    alert("Please Choose A Delivery Destination With A Valid Geo Coordinates")
                    return;
                }
                
                if(this.startDelivery){
                    
                    this.startDelivery = false
                }else{
                    this.startDelivery = true
                }
            },
            startGpsSimulation: function(){
               
                var current_coord_index = 0
                
                
                this.routeShape_lastPoint = this.routeShape.length
                this.routeShape.forEach((gpsCoordinates,index) => {
                   var timer = setTimeout(() => {
                                //first we must remove the marker on last coordinates
                                if(this.gps_marker_object != null)
                                        this.map.removeObject(this.gps_marker_object)
                                let tmp = gpsCoordinates.split(",")
                                    this.gps.lat = parseFloat(tmp[0])
                                    this.gps.lng  = parseFloat(tmp[1])
                                    
                                    this.drawGpsMarker()
                                    
                                    //remove the last geo position that has been drawn
                                    this.routeShape.splice(index,1)
                                    
                                    /*if(this.polylineObject != null){
                                        
                                        //this.addRouteShapeToMap(this.routeShape)
                                    }
                                    
                                        
                                    console.log(this.routeShape.length);*/


                                    
                                
                                
                             }, (index++)*this.gpsSimulationSpeed);
                    //save the timer 
                    this.gpsSimulationTimers.push(timer)
                        
                })

            },
            stopGpsSimulation: function(){
                //to stop gps simulation we only need to remove all the timers created when startGpsSimulation
                this.gpsSimulationTimers.forEach(timer => {
                    clearTimeout(timer)
                })
                this.gpsSimulationTimers = []
               


            },
           

          
          
        },
        components: {
            
            
        }

    }

</script>


<style scoped>

    #map,#_map{
        width: 100%;
    height: 363px;
    background: grey;
    }
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
