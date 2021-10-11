<link rel="stylesheet" href="{{ asset('css/gmap/gmap.css') }}">
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css"
  integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
<script src="https://maps.google.com/maps/api/js?key=AIzaSyBlxhvc_s8Ww9PrL0qKue0vB4nQaU6WYIs&sensor=false"></script>
<link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome/css/fontawesome-all.css')}}">

<script src="{{ asset('js/jquery_3.2.1.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{asset('assets/libs/js/gmaps.min.js')}}"></script>
<script src="{{asset('assets/libs/js/google_map.js')}}"></script>
<script src="{{asset('assets/libs/js/markerwithlabel.js')}}"></script>
{{-- <script src="{{ asset('js/gmap/gmap_view.js') }}"></script> --}}
<style>
  .text-red {
    color: red !important;
    font-size: 1.2rem;
  }
</style>
<style>
  .labels_kzone_img_st {
    width: 38px;
    height: 38px;
    background-repeat: no-repeat;
  }

  .labels_kzone_img_lorry {
    width: 26px;
    height: 35px;
    background-repeat: no-repeat;
  }

  input[type="range"] {
    -webkit-appearance: none;
    width: 100%;
    height: 25px;
    background: #16A085;
    outline: none;
    opacity: 0.7;
    -webkit-transition: .2s;
    transition: opacity .2s;
  }

  input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 32px;
    height: 32px;
    background-image: url("{{ asset('gmap/range_pointer.png') }}");
    cursor: pointer;
  }

  #pano {
    float: left;
    height: 100%;
    width: 30%;
  }

  #routes {
    float: left;
    height: 100%;
    width: 70%;
  }




  .CSS_Table[type=text],
  input[type=date],
  input[type=password],
  input[type=time],
  input[type=number] {
    /* padding: 13px 2px; */
    margin: 2px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    color: #000;
  }

  select {
    /*background: rgba(0,0,0,0.3);*/
    padding: 4px 2px;
    margin: 2px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    color: #000;
    background-color: #fff;
    text-transform: uppercase;
  }

  select option:hover {
    color: #fff;
    background-color: #82E0AA;
    text-transform: uppercase;
  }

  button:hover {
    border-radius: 5px;
  }

  #details {
    background-color: #34495E;
    color: #fff;
    width: 100%;
  }
</style>


<div class="container-fluid dashboard-content ">
  <!-- ============================================================== -->
  <!-- pageheader  -->
  <!-- ============================================================== -->
  <div class="">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
      <div class="page-header">
        <h3 class="pageheader-title">OUTLETS Map </h3>
        <div class="page-breadcrumb">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">

            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link " id="home-tab" data-toggle="" href="/gmapView" role="" aria-controls="home"
        aria-selected="true">DRAW PATH</a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" id="profile-tab" data-toggle="" href="/gmapOutletsView" role="" aria-controls="profile"
        aria-selected="false">OUTLETS</a>
    </li>

  </ul>
  <div class="tab-content" id="myTabContent">
    <!-- ============================================================== -->
    <!-- end pageheader  -->
    <!-- ============================================================== -->
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
      <div class="ecommerce-widget">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

          <div class="row">
            <!-- ============================================================== -->
            <!-- basic map -->
            <!-- ============================================================== -->
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="padding-left: 1px">
              <div class="card">
                <div class="card-header">
                  <div class="form-row">
                    {{-- <div class="col-xl-2 col-lg-3 col-md-12 col-sm-12 col-12 mb-2">
                                            <label for="validationCustom03">ZONE</label>
                                            <select class="form-control form-control-sm" id="zone_selt"
                                                onchange="getRegion(event);">
                                                <option value="0">SELECT</option>
                                                @foreach ($zone_list as $item)
                                                <option value="{{$item['zone_id']}}">{{$item['zone_name']}}</option>
                    @endforeach
                    </select>
                  </div> --}}
                  {{-- <div class="col-xl-2 col-lg-3 col-md-12 col-sm-12 col-12 mb-2">
                                            <label for="validationCustom04">REGION</label>
                                            <select id="region_selt" class="form-control form-control-sm"
                                                onchange="getTer();">
                                            </select>
                                        </div> --}}
                  <div class="col-xl-2 col-lg-3 col-md-12 col-sm-12 col-12 mb-2">
                    <label for="validationCustom05">TERRITORY</label>
                    <select id="territories" class="form-control form-control-sm" onchange="getRoute();">
                    </select>
                  </div>
                  <div class="col-xl-2 col-lg-3 col-md-12 col-sm-12 col-12 mb-2">
                    <label for="validationCustom05">ROUTE</label>
                    <select id="route_selt" class="form-control form-control-sm">
                    </select>
                  </div>
                  <div class="col-xl-2 col-lg-3 col-md-12 col-sm-12 col-12 ">
                    <label for="validationCustom05">&nbsp;</label>
                    <button onclick="set_outlet();" class="form-control btn btn-primary btn-sm">
                      <i class="fas fa-map-pin" style="font-size:16px; opacity: 0.8; color:#99A3A4;"></i>
                      &nbsp; LOCATE OUTLETS
                    </button>
                  </div>
                </div>
              </div>
              <tr>
                <td style="height:620px;">
                  <div id="outlet" style="height:620px;"></div>
                </td>
              </tr>
              <div class="card-footer" style="background-color: #34495E">
                <div class="row" style="margin: 1rem;">
                  <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding-left:0px;text-align: center;">
                    <td style="height:30px; opacity: 0.8; color:#641E16"><img src="{{url('js/gmap/icon/outlet.png')}}">
                    </td>
                    <td style="color:#fff">
                      <label style="color: white"> &nbsp;TOTAL OUTLETS</label> :
                      <input readonly class="CSS_Table" style="text-align:center;font-weight:bold;font-size: initial;"
                        type="text" id="tot_out" />
                    </td>
                  </div>
                  <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding-left:0px;text-align: center;">
                    <td><i class="feather icon-camera" style="font-size:30px; opacity: 0.8; color:#48C9B0"></i></td>
                    <td style="color:#fff">
                      <label style="color: white"> &nbsp;CAPTURED OUTLETS</label> :
                      <input readonly class="CSS_Table" style="text-align:center;font-weight:bold;font-size: initial;"
                        type="text" id="cap_tot_out" />
                    </td>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- ============================================================== -->
        <!-- end basic map -->
        <!-- ============================================================== -->
      </div>
    </div>
  </div>
</div>
</div>
</div>
{{-- <script src="{{asset ('datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{asset('assets/vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/vendor/datatables/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/vendor/datatables/js/data-table.js')}}"></script>

<script src="{{asset('datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('datatable/js/buttons.print.min.js')}}"></script>
<script src="{{asset('datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{asset('datatable/js/dataTables.rowGroup.min.js')}}"></script>
<script src="{{asset('datatable/js/dataTables.select.min.js')}}"></script>
<script src="{{asset('datatable/js/dataTables.fixedHeader.min.js')}}"></script>

<script src="{{asset('js/select2.min.js') }}"></script>

<script src="{{asset('js/jquery-confirm.min.js')}}"></script>
<script src="{{asset('js/jquery-ui.js')}}"></script> --}}

<script src="{{asset('assets/vendor/bootstrap-select/js/bootstrap-select.js')}}"></script>
<script src="{{asset('assets/libs/js/gmaps.min.js')}}"></script>
<script src="{{asset('assets/libs/js/google_map.js')}}"></script>
<script src="https://maps.google.com/maps/api/js?key=AIzaSyBlxhvc_s8Ww9PrL0qKue0vB4nQaU6WYIs&sensor=false"></script>

<script src="{{asset('assets/libs/js/markerwithlabel.js')}}" type="text/javascript"></script>
<script>
  $(function () {
  loadTsos();
  loadTerritories();
  allRoute();
  // Load_Map();
});

function loadTerritories(){
  $.ajax({
    url: "/api/common/loadTerritories",
    type: "POST",
    headers: { Authorization: "Bearer " + localStorage.getItem("token") },
    data: {},
    success: function (data) {
      var res = data.data;
      $("#territories").empty();
      $("#territories").append('<option value="">All Territory</option>');
      for (var k = 0; k < res.length; k++) {
        $("#territories").append(
          '<option value="' + res[k]["id"] +'">'  +res[k]["territory_name"] + '</option>'
        );
      }
    },
  });
}

function getRoute(){
  var territory_id = $('#territories').val();
  $.ajax({
    url: "/api/common/territory/routes",
    type: "POST",
    headers: { Authorization: "Bearer " + localStorage.getItem("token") },
    data: {
      territory_id:territory_id
    },
    success: function (data) {
      var res = data.data;
      console.log(res);
      $("#route_selt").empty();
      $("#route_selt").append('<option value="">All Routes</option>');
      for (var k = 0; k < res.length; k++) {
        $("#route_selt").append(
          '<option value="' + res[k]["id"] +'">'  +res[k]["route_name"] + '</option>'
        );
      }
    },
  });
}

function allRoute(){
  $.ajax({
    url: "/api/common/loadRoutes",
    type: "POST",
    headers: { Authorization: "Bearer " + localStorage.getItem("token") },
    data: {},
    success: function (data) {
      var res = data.data;
      console.log(res);
      $("#route_selt").empty();
      $("#route_selt").append('<option value="">All Routes</option>');
      for (var k = 0; k < res.length; k++) {
        $("#route_selt").append(
          '<option value="' + res[k]["id"] +'">'  +res[k]["route_name"] + '</option>'
        );
      }
    },
  });
}


function loadTsos() {
  $.ajax({
    url: "/api/common/loadTsos",
    type: "POST",
    headers: { Authorization: "Bearer " + localStorage.getItem("token") },
    data: {},
    success: function (data) {
      var res = data.data;
      $("#tso_id").empty();
      $("#tso_id").append('<option value="">All Tsos</option>');
      for (var k = 0; k < res.length; k++) {
        $("#tso_id").append(
          '<option value="' +
            res[k]["u_id"] +
            '">' +
            res[k]["fname"] +
            " " +
            res[k]["lname"] +
            "</option>"
        );
      }
    },
  });
}

function getTime() {
  $("#from").find("option:gt(0)").remove();
  $("#to").find("option:gt(0)").remove();
  var rep_name = $("#tso_id option:selected").text();
  $("#rep_name").val(rep_name);
  var date = $("#date").val();
  var tso = $("#tso_id").val();
  $.ajax({
    url:"/api/gmap/tsoRepAttendance",
    type:"GET",
    headers: { Authorization: "Bearer " + localStorage.getItem("token") },
    data:{
      date: date,
      tso: tso,
    },
    success:function(data){
      $('#st_time').val(data[0].check_in_time);
      $('#ed_time').val(data[0].check_out_time);
      // $('#mileage').text(data.check_in_mileage);
    }
  });


  $.ajax({
    url: "/api/gmap/tsoRoutingTime",
    type: "GET",
    headers: { Authorization: "Bearer " + localStorage.getItem("token") },
    data: {
      date: date,
      tso: tso,
    },
    success: function (data) {
      $("#from").empty();
      $("#to").empty();

      $("#from").append('<option value="">SELECT</option>');
      $("#to").append('<option value="">SELECT</option>');
      for (var i = 0; i < data.length; i++) {
        $("#from").append(
          "<option value=" +
            data[i].gps_timestamp +
            ">" +
            data[i].gps_timestamp +
            "</option>"
        );
        $("#to").append(
          "<option value=" +
            data[i].gps_timestamp +
            ">" +
            data[i].gps_timestamp +
            "</option>"
        );
      }
    },
  });
}
</script>
<script>
  // map main global varibles
    var _map, _path, _service, _poly, _animate_marker, _km_layer = 0,
        _point_start, _point_end,
        _sales_markers, _map_polyn, int_car = 21;

    var car_spped = 250;
    var ikk = 0;
    var i_interval = 0;
    var break_exsist = 1;
    var timeee;
    var down = 0;
    var sss = 0;
    var total_out = 0;
    var product_out = 0;
    var unprodut_out = 0;
    var missed_out = 0;
    var prev_k = -1;
    /*
     *
     * start and end points
     */
    var start_marker;
    var end_marker;


    // map global arrays
    var time = new Array();;
    var _gl_sales_markers = [];
    var _gl_outlet_markers = [];
    var _gl_markers = [];
    var _gl_polyn = [];

    //routes_length, lat_lng, routes_
    var mroutes_length = 0;
    var mlat_lng = new Array();
    var mroutes_;
    var map_polyn;

    var map;
    var rep, date;
    var bounds = [];
    var drawIndex;
    //check box
    var nearDealers = [];
    var productives = [];
    var unproductives = [];
    var distance;
    var duration;
    var startAddrs;
    var _mapOptions;
    var resume_k = true;
</script>
<script>
  $(document).ready(function() {
        console.log('c');
        Load_Map();
    });

    var styledMapType = new google.maps.StyledMapType(
        [{
                elementType: 'geometry',
                stylers: [{
                    color: '#ECECE0'
                }]
            },
            {
                featureType: 'poi',
                elementType: 'geometry',
                stylers: [{
                    color: '#82E0AA'
                }]
            },

            {
                featureType: 'road.highway',
                elementType: 'geometry',
                stylers: [{
                    color: '#EC7063'
                }]
            },
            {
                featureType: 'road.arterial',
                elementType: 'geometry',
                stylers: [{
                    color: '#808B96'
                }]
            },
            {
                featureType: 'road.local',
                elementType: 'geometry',
                stylers: [{
                    color: '#AEB6BF'
                }]
            },
            {
                featureType: 'water',
                elementType: 'geometry.fill',
                stylers: [{
                    color: '#6DBCCC'
                }]
            },
        ], {
            name: 'Styled Map'
        });

    /*Load_Map*/
    function Load_Map() {
        console.log(document.getElementById('outlet'));
        _map = new google.maps.Map(document.getElementById('outlet'), {
            center: new google.maps.LatLng(7.9, 81),
            zoom: 7.6,
            mapTypeIds: ['roadmap', 'satellite', 'hybrid', 'terrain',
                'styled_map'
            ]
        });
        _map.mapTypes.set('styled_map', styledMapType);
        _map.setMapTypeId('styled_map');
    }


    function set_outlet() {
        var territoryId = $('#territories').val();
        var routeId = $('#route_selt').val();
        $.ajax({
            type: 'GET',
            url: "/api/gmap/outlets/outletDetails",
            headers: { Authorization: "Bearer " + localStorage.getItem("token") },
            data: {
                territoryId: territoryId,
                routeId: routeId
            },
            success: function(data) {
                console.log(data);
                var stringified = JSON.stringify(data);
                var invoice_data = JSON.parse(stringified);
                if (_gl_markers.length > 0) {
                    for (var i = 0; i < _gl_markers.length; i++) {
                        _gl_markers[i].setMap(null);
                    }
                }
                _gl_outlet_markers = [];
                var no_of_outlet = invoice_data.length;
                var tot_captured_out = 0;
                $.each(invoice_data, function (index, item) {
                if(item.lat && item.lon&& item.image !=null){
                    tot_captured_out++;
                }
            });
                $('#tot_out').val(no_of_outlet);
                $('#cap_tot_out').val(tot_captured_out);
                if (typeof invoice_data != "undefined") {
                        for (var x = 0; x < invoice_data.length; x++) {
                            if(invoice_data[x].lat){
                                console.log(invoice_data[x].lat);
                                var outlet_marker = new google.maps.Marker({
                                    position: new google.maps.LatLng(invoice_data[x].lat, invoice_data[x].lon),
                                    icon: "{{url('js/gmap/icon/yellow2.png')}}",
                                    map: _map
                                });
                                console.log(outlet_marker);
                                _gl_outlet_markers.push({
                                    get_marker: outlet_marker
                                });
                                _gl_markers.push(outlet_marker);
                                var infowindow = new google.maps.InfoWindow();

                                var con_url = "";
                                if (invoice_data[x].image === null) {
                                    con_url = "No image found";
                                } else {
                                    var APP_URL = "{{url('/')}}";
                                    var ur =APP_URL+invoice_data[x].image;
                                    con_url = '<img src='+ur+' width="100" height="100"/>';
                                }
                                var content = '<table style="color: #0D47A1;font-weight:bold">' +
                                    '<tr>' +
                                    '<td>' +
                                    '<h2 style="background:#996600;color: white;font-weight:bold">' +
                                    'Outlet Details' +
                                    '</h2>' +
                                    '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                    '<td>Retailer Name</td>' +
                                    '<td>:</td>' +
                                    '<td>' + invoice_data[x].name + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                    '<td>Address</td>' +
                                    '<td>:</td>' +
                                    '<td>' + invoice_data[x].address + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                    '<td>Owner Name</td>' +
                                    '<td>:</td>' +
                                    '<td>' + invoice_data[x].owner + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                    '<td>Telephone No</td>' +
                                    '<td>:</td>' +
                                    '<td>' + invoice_data[x].tel + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                    '<td>Latitude</td>' +
                                    '<td>:</td>' +
                                    '<td>' + invoice_data[x].lat + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                    '<td>Longitude</td>' +
                                    '<td>:</td>' +
                                    '<td>' + invoice_data[x].lon + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                    '<td>Outlet Image</td>' +
                                    '<td>:</td>' +
                                    '<td>' + con_url + '</td>' +
                                    '</tr>' +
                                    '</table>';

                                google.maps.event.addListener(outlet_marker, 'mouseover', (function(outlet_marker, content, infowindow) {
                                    return function() {
                                        infowindow.setContent(content);
                                        infowindow.open(map, outlet_marker);
                                    };
                                })(outlet_marker, content, infowindow));

                                google.maps.event.addListener(outlet_marker, 'mouseout', (function(outlet_marker, content, infowindow) {
                                    return function() {

                                        infowindow.close();
                                    };
                                })(outlet_marker, content, infowindow));
                            }
                        }
                }
            },
            error: function() {
            }
        });
    }




</script>
