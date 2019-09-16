<template>
    <div>

        <div class="table-wrapper">
            <div class="table-title" style="background-color:#272b30;">
                <div class="row">
                    <div class="col-sm-4">
                        <h2>Deliveries <b>Manager</b></h2>
                    </div>
                    <div class="col-sm-3">
                        <input class="form-control form-control-dark w-100" type="text" placeholder="Search"
                            aria-label="Search" v-model="search">

                    </div>
                    <div class="col-sm-1 ml-0 pl-0">
                        <img class="" style="cursor:pointer;color:white;" :src="'/img/icons/'+filter_flow+'.png'" width="38"
                                @click="(filter_flow == 'Ascending' )? filter_flow='Descending':filter_flow='Ascending'"
                                :title="filter_flow">
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
                <div class="col-5">
                    <div class="overflow-auto" style="height:363px;">
                         <table class="table table-striped table-hover " style="max-height:363px;" >
                                <thead  >
                                    <tr >
                                        <th class="text-center">Customer Name</th>
                                        <th class="text-center">Balance Due</th>
                                        <th style="width:80px;"></th>
                                    </tr>
                                </thead>

                                <tbody  style="overflow-y:scroll;height:3px;">
                                    <tr v-for="(delivery,index) in deliveries" :key="index" @click="selected_delivery= delivery">
                                        <td class="text-center">{{delivery.customer_name}}</td>
                                        <td class="text-center">{{delivery.total_price}}</td>
                                        <td>
                                            <img src="/img/icons/checkmark.png" width="20" alt="" data-tooltip="tooltip" data-title="Confirm Payment" data-placement="top" style="cursor:pointer">
                                            <img src="/img/icons/trash.png" width="20" alt="" data-tooltip="tooltip" data-title="Delete Delivery" data-placement="top" style="cursor:pointer">
                                        </td>
                                    </tr>
                                    

                                </tbody>
                            </table>
                    </div>
                    
                    <div class="row d-flex justify-content-center align-bottom">

                    <button class="btn btn-primary w-75 " @click="sortDeliveries">Sort By Closest</button>
                    </div>

                </div>
                <div class="col-7">
                    <div id="map">c</div>

                </div>
            </div>
            <span>GPS Coordinates : {{gps.lat+" , "+gps.lng}}</span>
           
        </div>


    </div>
</template>


<script>
    import editsupplier from './suppliers/editSupplier.vue'
    import addsupplier from './suppliers/addSupplier.vue'
    

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
                selected_delivery:null,
                deliveries:[],
                deliveries_coords:[],
                search:"",
                gps:{
                    lat:0,
                    lng:0
                },

                gps_marker:null,
                gps_marker_icon_svg : `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="200" height="40" viewBox="0 0 263.335 263.335" style="enable-background:new 0 0 263.335 263.335;" xml:space="preserve">
                                    <g>
                                        <g xmlns="http://www.w3.org/2000/svg">
                                            <path d="M40.479,159.021c21.032,39.992,49.879,74.22,85.732,101.756c0.656,0.747,1.473,1.382,2.394,1.839   c0.838-0.396,1.57-0.962,2.178-1.647c80.218-61.433,95.861-125.824,96.44-128.34c2.366-9.017,3.57-18.055,3.57-26.864    C237.389,47.429,189.957,0,131.665,0C73.369,0,25.946,47.424,25.946,105.723c0,8.636,1.148,17.469,3.412,26.28" fill="{{gps_marker_color}}"/>
                                        <text x="80" y="130" font-family="sans-serif" font-size="5em" fill="white">{{gps_marker_text}}</text>
                                        </g>
                                    </g></svg>`,
              
                
                filter_flow:"Ascending",

                current_destination:null,
                destination_marker:null,
                destination_marker_icon_svg : `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="200" height="40" viewBox="0 0 263.335 263.335" style="enable-background:new 0 0 263.335 263.335;" xml:space="preserve">
                                    <g>
                                        <g xmlns="http://www.w3.org/2000/svg">
                                            <path d="M40.479,159.021c21.032,39.992,49.879,74.22,85.732,101.756c0.656,0.747,1.473,1.382,2.394,1.839   c0.838-0.396,1.57-0.962,2.178-1.647c80.218-61.433,95.861-125.824,96.44-128.34c2.366-9.017,3.57-18.055,3.57-26.864    C237.389,47.429,189.957,0,131.665,0C73.369,0,25.946,47.424,25.946,105.723c0,8.636,1.148,17.469,3.412,26.28" fill="{{gps_marker_color}}"/>
                                        <text x="80" y="130" font-family="sans-serif" font-size="5em" fill="white">{{gps_marker_text}}</text>
                                        </g>
                                    </g></svg>`,
              
                polylineObject:null,
               

            }
        },
        watch:{
             search : function(val){
            this.paginationCurrent = 1

               

            },
           selected_delivery: function(){
               this.destinationSelected()
           },
            filter_flow : function(){
                 this.getSuppliers();
            },
            
        },
        



        updated: function(){$('[data-tooltip=tooltip]').tooltip();}, 
        methods: {
            sortDeliveries: function(){
                console.log(this.deliveries_coords)
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
                                console.log(data.Response.View[0].Result[0].Location.Address.Label);
                                //alert("The nearest address to your location is:\n" + data.Response.View[0].Result[0].Location.Address.Label);
                            }, error => {
                                console.error(error);
                            }
                        );


                        this.gps = {lat:position.coords.latitude ,
                                    lng:position.coords.longitude}


                        //draw gps marker 
                        //first we need to remove the marker from the previous position if it exist
                        if(this.map.getObjects().length > 0)
                            if(this.gps_marker != null)
                                this.map.removeObject(this.gps_marker)

                        //2ndly draw the gps_marker 
                        

                        var gpsMarkerIcon = new H.map.Icon(
                                            this.gps_marker_icon_svg.replace('{{gps_marker_color}}', '#e3342f').replace('{{gps_marker_text}}', 'gps')
                                            )
                        this.gps_marker = new H.map.Marker(this.gps,
                                        {icon: gpsMarkerIcon});

                        this.map.addObject(this.gps_marker);

                        //focus on the gps coordinates 
                        this.map.getViewModel().setLookAtData({position:this.gps},true)
                        this.destinationSelected()



                    });
                } else {
                    console.error("Geolocation is not supported by this browser!");
                }
            },
            drawDestinationMarker: function(){
                //first we need to remove the marker from the previous position if it exist
                        if(this.map.getObjects().length > 0)
                            if(this.destination_marker != null)
                                this.map.removeObject(this.destination_marker)

                        //2ndly draw the gps_marker 
                        

                        var destinationMarkerIcon = new H.map.Icon(
                                            this.destination_marker_icon_svg.replace('{{gps_marker_color}}', 'green').replace('{{gps_marker_text}}', 'Cus')
                                            )
                        this.destination_marker = new H.map.Marker(this.current_destination,
                                        {icon: destinationMarkerIcon});

                        this.map.addObject(this.destination_marker);

                        //focus on the gps coordinates 
                        this.map.getViewModel().setLookAtData({position:this.current_destination},true)
            },
            destinationSelected : function(){
                console.log(this.map)
                //this.map.removeAll()
                this.init_gpsTracking()
                if(this.selected_delivery == null)
                    return;
                console.log(this.selected_delivery.geo_coord)
                if(!RegExp("[-+]?[0-9]*\.?[0-9]+,[-+]?[0-9]*\.?[0-9]+").test(this.selected_delivery.geo_coord)){
                    alert("This Customer Does Not Have Geo Coordinates ,, Calling Customer for guidance is Advised Tel:"+this.selected_delivery.tel)
                    return;
                }
                this.current_destination = this.deliveries_coords[this.selected_delivery.id]
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
                    waypoint1: destination  // Friedrichstraße Railway Station i.e "12.54641,11.54641"
                    };


                    router.calculateRoute(
                        routeRequestParams,
                        this.onSuccess,
                        this.onError
                    );
                    
            },
            onSuccess: function(result) {
                var route = result.response.route[0];
                
                /*
                * The styling of the route response on the map is entirely under the developer's control.
                * A representitive styling can be found the full JS + HTML code of this example
                * in the functions below:
                */
                this.addRouteShapeToMap(route);
                /*addManueversToMap(route);

                addWaypointsToPanel(route.waypoint);
                addManueversToPanel(route);
                addSummaryToPanel(route.summary);*/
                // ... etc.
            },

            onError: function(error) {
                alert('Can\'t reach the remote server');
            },

            addRouteShapeToMap: function(route){
                var lineString = new H.geo.LineString(),
                    routeShape = route.shape,
                    polyline;

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
                // And zoom to its bounding rectangle
                this.map.getViewModel().setLookAtData({
                    bounds: polyline.getBoundingBox()
                    });
            }
                
          
        },
        components: {
            
            
        }

    }

</script>


<style scoped>
tbody tr, tbody td{
    
    
}
    #map,#_map{
        width: 100%;
    height: 400px;
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
