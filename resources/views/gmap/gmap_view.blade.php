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
    background-image: url("{{ asset('js/gmap/icon/range_pointer.png') }}");
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

  .footer_font_size {
    font-size: 11px;
  }
</style>
<div class="container-fluid dashboard-content ">
  <!-- ============================================================== -->
  <!-- pageheader  -->
  <!-- ============================================================== -->
  <div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
      <div class="page-header">
        <h3 class="pageheader-title">GPS Map </h3>
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
      <a class="nav-link active" id="home-tab" data-toggle="" href="{{url('/gmapView')}}" role="" aria-controls="home"
        aria-selected="true">DRAW PATH</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="profile-tab" data-toggle="" href="{{url('/gmapOutletsView')}}" role=""
        aria-controls="profile" aria-selected="false">OUTLETS</a>
    </li>

  </ul>
  <div class="tab-content" id="myTabContent">
    <!-- ============================================================== -->
    <!-- end pageheader  -->
    <!-- ============================================================== -->
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
      <div class="ecommerce-widget">
        <div style="width:100%">


          <div class="row">
            <!-- ============================================================== -->
            <!-- basic map -->
            <!-- ============================================================== -->
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="padding-left: 1px">
              <div class="card">
                <div class="card-header">
                  <div class="form-row">
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 mb-2">
                      <label for="validationCustom03">Date</label>
                      <input type="date" class="form-control form-control-sm" id="date" placeholder="date"
                        onchange="loadAttendanceTsos()">
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 mb-2">
                      <label for="validationCustom04">Sales Rep</label>
                      <select class="form-control form-control-sm" id="tso_id" name="tso_id" onchange="getTime();">
                      </select>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 mb-2">
                      <label for="validationCustom05">From</label>
                      <select class="form-control form-control-sm" id="from" name="from">
                        <option value="">SELECT</option>
                      </select>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 mb-2">
                      <label for="validationCustom05">To</label>
                      <select class="form-control form-control-sm" id="to" name="to">
                        <option value="">SELECT</option>
                      </select>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 ">
                      <button class="btn btn-primary btn-sm" type="button" onclick="draw_live_route()">Draw
                        Path</button>
                      <button class="btn btn-secondary btn-sm" type="button" onclick="Load_Map2()">Clear</button>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 ">
                      <i class="far fa-pause-circle"
                        style="font-size:40px; opacity: 0.8; color:#943126; cursor: pointer;" onclick="pause();"
                        id="PauseBtn"> </i>
                      <i class="far fa-play-circle"
                        style="font-size:40px; opacity: 0.8; color:#0B5345; cursor: pointer;" onclick="resume();"
                        id="ResumeBtn"></i>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-12 col-sm-12 col-12 ">
                      <div class="col-xl-12 col-lg-6 form-check-input" id="speed_range"><input type="range"
                          width="200px" id="car_range" name="car_range" max="100" min="1" value="5" onchange="doc();" />
                      </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-12 col-sm-12 col-12 ">
                      <div class="col-xl-12 col-lg-6 form-check-input" id="speed_range">
                        <button id="st_x" onclick="st_view_x()"
                          style="float: right;border:#ABB2B9;background-color:#fff;">
                          <!--<span style="font-size:9px">google streetview captured images</span>-->
                          <i class="fas fa-street-view" style="font-size:25px; color: #0B5345; cursor: pointer;"></i>
                        </button>
                        <button id="st_o" onclick="st_view_o()"
                          style="display:block; float: right;border:#ABB2B9; background-color:#fff;">
                          <!--<span style="font-size:9px">google streetview captured images</span>-->
                          <i class="fa fa-eye-slash" style="font-size:25px; color: #0B5345; cursor: pointer;"></i>
                        </button>
                      </div>
                    </div>
                    {{-- <div style="height:20px;float: right;">
                                            <button id="st_x" onclick="st_view_x()"
                                                style="/* display: none; */float: top;border:#ABB2B9;background-color:#fff;">
                                                <!--<span style="font-size:9px">google streetview captured images</span>-->
                                                <i class="fas fa-street-view"
                                                    style="font-size:25px; color: #0B5345; cursor: pointer;"></i>
                                            </button>
                                            <button id="st_o" onclick="st_view_o()"
                                                style="display:block; float: top;border:#ABB2B9; background-color:#fff;">
                                                <!--<span style="font-size:9px">google streetview captured images</span>-->
                                                <i class="fa fa-eye-slash"
                                                    style="font-size:25px; color: #0B5345; cursor: pointer;"></i>
                                            </button>
                                        </div> --}}
                  </div>
                </div>
                <div class="card-body">
                  <div id="routes" class="gmaps"></div>
                  <div id="pano"></div>
                </div>
                <div class="card-footer" style="background-color: #34495E;margin-bottom:50px;">
                  <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-1 col-lg-1" style="padding-top:25px;max-width: 5%;">
                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <i class="fas fa-business-time" style="font-size:40px; opacity: 0.8;color:#A2D9CE"></i>
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="max-width: 10%;">
                      <div class="row" style="padding-top:10px;color:#fff">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                          <div id="win" style="font-size:24px;text-align:center;font-weight:bold">
                            00:00:00</div>
                        </div>
                      </div>
                      <div class="row" style="padding-top:10px;color:#fff">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                          <div style="font-size:24px;text-align:center;font-weight:bold">
                            <span id="km_ly"> 0.00 km</span>
                          </div>
                          <input id="myValues" style="visibility: hidden;display: none" />
                        </div>
                      </div>
                      <div class="row" style="padding-top:10px;color:#fff">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                          <div style="font-size:17px;text-align:center;font-weight:bold">
                            <span id="mileage"></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                          <div class="col-3">
                            <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="padding-right: 2px">
                                <span class="fas fa-store" style="color:#BB8FCE;font-size:25px; opacity: 0.8"
                                  aria-hidden="true"></span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4" style="padding-right: 2px">
                                <span style="color:#fff" class="footer_font_size">TOTAL
                                  OUTLETS</span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <input type="text" class="form-control" readonly id="tot_outlet"
                                  style="font-weight: bold" />
                              </div>
                            </div>
                          </div>
                          <div class="col-3">
                            <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="padding-right: 2px">
                                <span class="fas fa-map-marker-alt" style="color:pink;font-size:25px; opacity: 0.8"
                                  aria-hidden="true"></span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4" style="padding-right: 2px">
                                <span style="color:#fff" class="footer_font_size">INVOICES</span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <input type="text" class="form-control" readonly id="invoices" />
                              </div>
                            </div>
                          </div>
                          <div class="col-3">
                            <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                <span class="far fa-edit" style="color:#5DADE2;font-size:25px; opacity: 0.8"
                                  aria-hidden="true"></span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                                <span style="color:#fff" class="footer_font_size">FIRST
                                  INV.TIME</span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <input type="text" class="form-control" readonly id="first_invoice" />
                              </div>
                            </div>
                          </div>
                          <div class="col-3">
                            <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                <span class="far fa-calendar-check" style="color:#58D68D;font-size:25px; opacity: 0.8"
                                  aria-hidden="true"></span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                                <span style="color:#fff" class="footer_font_size">DAY
                                  START</span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding: 0;">
                                <input type="text" style="width: 11rem;text-align: left;" class="form-control" readonly
                                  id="st_time" />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row" style="padding-top: 5px">
                          <div class="col-3">
                            <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="padding-right: 2px">
                                <span class="fas fa-store" style="color:#85C1E9;font-size:25px; opacity: 0.8"
                                  aria-hidden="true"></span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4" style="padding-right: 2px">
                                <span style="color:#fff" class="footer_font_size">CAPTURED OUTLETS</span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <input type="text" class="form-control" readonly id="outlet"
                                  style="font-weight: bold" />
                              </div>
                            </div>
                          </div>
                          <div class="col-3">
                            <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="padding-right: 2px">
                                <span class="fas fa-map-marker-alt" style="color:red;font-size:25px; opacity: 0.8"
                                  aria-hidden="true"></span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4" style="padding-right: 2px">
                                <span style="color:#fff;font-size: 9px;">UNPRODUCTIVES</span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <input type="text" class="form-control" readonly id="unpro" />
                              </div>
                            </div>
                          </div>
                          <div class="col-3">
                            <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                <span class="far fa-edit" style="color:#5DADE2;font-size:25px; opacity: 0.8"
                                  aria-hidden="true"></span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                                <span style="color:#fff" class="footer_font_size">LAST
                                  INV.TIME</span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <input type="text" class="form-control" readonly id="last_invoice" />
                              </div>
                            </div>
                          </div>
                          <div class="col-3">
                            <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                <span class="fas fa-calendar-check" style="color:#58D68D;font-size:25px; opacity: 0.8"
                                  aria-hidden="true"></span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                                <span style="color:#fff" class="footer_font_size">DAY
                                  END</span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding: 0;">
                                <input type="text" style="width: 11rem;text-align: left;" class="form-control" readonly
                                  id="ed_time" />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row" style="padding-top: 5px">
                          <div class="col-3">
                            <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="padding-right: 2px">
                                <span class="fas fa-store" style="color:#fff;font-size:25px; opacity: 0.8"
                                  aria-hidden="true"></span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4" style="padding-right: 2px">
                                <span style="color:#fff" class="footer_font_size">MISSED
                                  OUTLETS</span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <input type="text" class="form-control" style="font-weight: bold" readonly
                                  id="missed_out" />
                              </div>
                            </div>
                          </div>
                          <div class="col-3">
                            <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="padding-right: 2px">
                                <span class="fas fa-map-marker-alt" style="color:green;font-size:25px; opacity: 0.8"
                                  aria-hidden="true"></span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4" style="padding-right: 2px">
                                <span style="color:#fff" class="footer_font_size">SALES
                                  ORDERS</span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <input type="text" class="form-control" style="font-weight: bold" readonly
                                  id="sales_orders" />
                              </div>

                            </div>
                          </div>
                          <div class="col-3">
                            <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                <span class="far fa-calendar-check" style="color: #58D68D;font-size:25px; opacity: 0.8"
                                  aria-hidden="true"></span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4" style="padding-right: 2px">
                                <span style="color:#fff" class="footer_font_size">FIRST
                                  SO.TIME</span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <input type="text" class="form-control" readonly style="font-weight: bold"
                                  id="first_so" />
                              </div>
                            </div>

                          </div>
                          <div class="col-3">
                            <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="padding-right: 2px">
                                <span class="fas fa-user-tie" style="color:#58D68D;font-size:25px; opacity: 0.8"
                                  aria-hidden="true"></span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4" style="padding-right: 2px">
                                <span style="color:#fff" class="footer_font_size">REP</span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding: 0;">
                                <input type="text" class="form-control" readonly style="width: 11rem" id="rep_name" />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row" style="padding-top: 5px">
                          <div class="col-3">
                            <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="padding-right: 2px">
                                <span class="fas fa-battery-three-quarters"
                                  style="color:#5DADE2;font-size:25px; opacity: 0.8" aria-hidden="true"></span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4" style="padding-right: 2px">
                                <span style="color:#fff" class="footer_font_size">BATTERY LEVEL</span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <span style="font-size:20px; text-align:center;font-weight:bold;color:#fff; "
                                  id="btlevel">0.00%</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-3">
                            <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="padding-right: 2px">
                                <span class="fas fa-map-marker-alt" style="color:yellow;font-size:25px; opacity: 0.8"
                                  aria-hidden="true"></span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4" style="padding-right: 2px">
                                <span style="color:#fff" class="footer_font_size">CALL
                                  ORDERS</span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <input type="text" class="form-control" style="font-weight: bold" readonly
                                  id="call_orders" />
                              </div>

                            </div>
                          </div>
                          <div class="col-3">
                            <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                <span class="far fa-calendar-check" style="color: #58D68D;font-size:25px; opacity: 0.8"
                                  aria-hidden="true"></span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4" style="padding-right: 2px">
                                <span style="color:#fff;" class="footer_font_size">LAST
                                  SO.TIME</span>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <input type="text" class="form-control" style="font-weight: bold;" readonly
                                  id="last_so" />
                              </div>
                            </div>
                          </div>
                          <div class="col-3">
                            <div class="row">

                            </div>
                          </div>
                        </div>
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
    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
    </div>
  </div>

</div>

<script>
  $(document).ready(function () {
  Load_Map();
  st_view_x();
//   loadTsos();
  loadTerritories();
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

function loadAttendanceTsos(){
    var date = $('#date').val();
    $.ajax({
    url: "/api/gmap/check_in/tsos",
    type: "POST",
    headers: { Authorization: "Bearer " + localStorage.getItem("token") },
    data: {
        date:date
    },
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
    $('#st_time').val("");
    $('#ed_time').val("");
    $("#rep_name").val("");
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
    },
    complete:function(data){
        $('#st_time').val(data[0].check_in_time);
        $('#ed_time').val(data[0].check_out_time);
    }
  });

  $.ajax({
    url: "/api/gmap/tsoRoutingTime",
    type: "GET",
    headers: { Authorization: "Bearer " + localStorage.getItem("token") },
    data: {
      date: date,
      logDate:logDate,
      logoutDate:logoutDate,
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

var map,
  animate_pos,
  response,
  routes_,
  _animate_marker,
  commn_marker,
  int_car = 21;

var _gl_polyn = [];
var _gl_commn = [];
var time = new Array();
var batlevel = new Array();

// map global arrays
var _gl_outlet_markers = [];
var _gl_sales_markers = [];
var _gl_call_outlet_markers = [];
var _gl_unoutlet_markers = [];
var _gl_markers = [];
var _gl_unmarkers = [];

/*Load_Map*/
function Load_Map() {
  document.getElementById("routes").style.height = "580px";
  document.getElementById("pano").style.height = "580px";

  map = new google.maps.Map(document.getElementById("routes"), {
    center: new google.maps.LatLng(7.9, 81),
    zoom: 7.8,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
  });
  const fenway = {
    lat: 42.345573,
    lng: -71.098326,
  };
  const panorama = new google.maps.StreetViewPanorama(
    document.getElementById("pano"),
    {
      position: fenway,
      pov: {
        heading: 34,
        pitch: 10,
      },
    }
  );
  map.setStreetView(panorama);

  //Intialize the Direction Service
  _service = new google.maps.DirectionsService();

  //Intialize the Polyline Service and set map
  _poly = new google.maps.Polyline({ map: map, strokeColor: "#4986E7" });

  $("#PauseBtn").hide();
  $("#ResumeBtn").hide();
  $("#speed_range").hide();
  $("#pano").hide();
  $("#routes").width("100%");
}

function st_view_x() {
  if (typeof routes_ != "undefined") {
    var fenway = new google.maps.LatLng(
      routes_[x_loop].lat,
      routes_[x_loop].lon
    );
  } else {
    const fenway = {
      lat: 42.345573,
      lng: -71.098326,
    };
  }
}

function Load_Map2() {
  i_interval = 0;
  x_loop = 1;
  _km_layer = 0;
  deg = 0;
  lowcg = "{{ asset('js/gmap/icon/car.png') }}";
  lowcgstrok = "#008BFF";
  resume_k = true;
  prev_k = 0;

  extkzone = false;

  map,
    animate_pos,
    response,
    routes_,
    _animate_marker,
    commn_marker,
    (int_car = 21);

  _gl_commn = [];

  // map global arrays
  _gl_sales_markers = [];
  _gl_outlet_markers = [];
  _gl_markers = [];
  _gl_polyn = [];
  map = new google.maps.Map(document.getElementById("routes"), {
    center: new google.maps.LatLng(7.9, 81),
    zoom: 7.8,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
  });

  //Intialize the Direction Service
  _service = new google.maps.DirectionsService();

  //Intialize the Polyline Service and set map
  _poly = new google.maps.Polyline({ map: map, strokeColor: "#4986E7" });

  $("#sales").val("");
}

function draw_live_route() {
  Load_Map2();
  document.getElementById("routes").style.height = "580px";
  $("#speed_range").show();

  var from = $("#from").val();
  var to = $("#to").val();

  var date = $("#date").val();

  var tso = $("#tso_id").val();
  if (date != "") {
    if (tso != 0) {
      set_outlet(date, tso);
      invoice_call_orders(date, tso);
      draw_sales_orders(date, tso, from, to);
      $.ajax({
        type: "post",
        url: "/api/gmap/gpsInfo",
        headers: { Authorization: "Bearer " + localStorage.getItem("token") },
        data: {
          date: date,
          tso: tso,
          from: from,
          to: to,
        },
        success: function (data) {
          routes_ = data;
          var routes_length = routes_.length;

          time = new Array();
          batlevel = new Array();
          $("#PauseBtn").show();

        //   $("#missed_out").val(
        //     parseInt($("#tot_outlet").val()) -
        //       (parseInt($("#invoices").val()) +
        //         parseInt($("#unpro").val()) +
        //         parseInt($("#sales_orders").val()))
        //   );


            var missed_out_k = 0;
            $("#missed_out").val(0);

            var total_out_k = parseInt($("#tot_outlet").val());
            var invoice_k = parseInt($("#invoices").val());
            var unpro_k = parseInt($("#unpro").val());
            var sales_k = parseInt($("#sales_orders").val());

            missed_out_k = (total_out_k - (invoice_k+unpro_k+sales_k));
            $("#missed_out").val(missed_out_k);


          /*
           * set first marker
           */
          if (routes_length > 0) {
            var kzone_start_mkr = new MarkerWithLabel({
              position: new google.maps.LatLng(
                routes_[0].lat,
                routes_[0].lon
              ),
              draggable: false,
              icon: "{{asset('js/gmap/icon/start_map.png')}}",
              map: map,
              labelAnchor: new google.maps.Point(20, 40),
              labelClass: "labels_kzone_img_st", // the CSS class for the label
              labelVisible: true,
              kzone_id: "kzone_st" + routes_[0].id,
              kzone_rotate: "0",
            });

            _gl_polyn.push(kzone_start_mkr);

            animate_pos = new MarkerWithLabel({
              position: new google.maps.LatLng(
                routes_[0].lat,
                routes_[0].lon
              ),
              draggable: false,
              icon: "{{asset('js/gmap/icon/1.png')}}",
              map: map,
              labelAnchor: new google.maps.Point(5, 22),
              labelClass: "labels_kzone_img_lorry", // the CSS class for the label
              labelVisible: true,
              kzone_id: "kzone_run" + routes_[0].id,
              kzone_image: "{{ asset('js/gmap/icon/car.png') }}",
              kzone_rotate: "0",
            });
            //rotation
            _gl_polyn.push(animate_pos);

            map.panTo(
              new google.maps.LatLng(routes_[0].lat, routes_[0].lon)
            );

            // const fenway = {
            //     lat: routes_[0].gps_lat,
            //     lng: routes_[0].gps_long
            // };
            // const panorama = new google.maps.StreetViewPanorama(
            //     document.getElementById("pano"),
            //     {
            //         position: fenway,
            //         pov: {
            //             heading: 34,
            //             pitch: 10,
            //         }
            //     }
            // );
            // map.setStreetView(panorama);

            live_tracking_draw_object();

            for (var i = 0; i < routes_length; i++) {
              time.push(routes_[i].app_time);
              batlevel.push(routes_[i].bat_level);
            }



          } else {


            swal({
              title: "Error!",
              text: "Data Not Found !",
              icon:"error",
              confirmButtonClass: "btn btn-success",
            });
          }
        },
        error: function () {},
      });
    } else {
      swal({
        title: "Error!",
        icon:"error",
        text: "Please Select Tso!",
        confirmButtonClass: "btn btn-success",
      });
    }
  } else {
    swal({
      title: "Error!",
      icon: "error",
      text: "Select Date & Tso !",
      confirmButtonClass: "btn btn-success",
    });
  }
}

function set_outlet(date, tso) {
    $.ajax({
        type: 'get',
        url:"/api/gmap/outletInfo",
        headers: { Authorization: "Bearer " + localStorage.getItem("token") },
        data: {
            date: date,
            tso: tso
        },
        success: function(data){
            console.log(data);
            if(date!=''){
                var main_data = JSON.parse(data);
                var stations = main_data['edited_outlets'];
                var no_of_edited_outlet = stations.length;
                var total_out = main_data['all_outlets'];
                var missed_out_count = main_data['missed_outlets'];

                $('#outlet').val(no_of_edited_outlet);
                $('#tot_outlet').val(total_out);
                _gl_outlet_markers = [];
                if (typeof stations != "undefined") {
                    for (var i = 0; i < stations.length; i++) {
                        console.log(stations);
                        var outlet_marker = new google.maps.Marker({
                            position: new google.maps.LatLng(stations[i].lat, stations[i].lon),
                            icon: "{{asset('js/gmap/icon/shop.png')}}",
                            map: map
                        });
                        _gl_outlet_markers.push({get_marker: outlet_marker});
                        _gl_markers.push(outlet_marker);

                        var infowindow = new google.maps.InfoWindow();
                        var con_url = "";
                            if (stations[i].image === null) {
                                con_url = "No image found";
                            } else {
                                var APP_URL = "{{url('/')}}";
                                var ur =APP_URL+stations[i].image;
                                console.log(ur);
                                con_url = '<img src='+ur+' width="100" height="100"/>';
                            }
                        var content = '<table style="color:black;font-size: 10px;font-weight: bold">'
                                        + '<caption style="background:#341b5c;color: #fff;font-weight:bold;font-size: 13px;text-align:center">'
                                        + 'Outlet Details'
                                        + '</caption>'
                                        + '<tr>'
                                        + '<td style="">No</td>'
                                        + '<td style="">:</td>'
                                        + '<td style="">' + (i + 1) + '</td>'
                                        + '</tr>'
                                        + '<tr>'
                                        + '<td>Name</td>'
                                        + '<td>:</td>'
                                        + '<td>' + stations[i].name + '</td>'
                                        + '</tr>'
                                        + '<tr>'
                                        + '<td>Address</td>'
                                        + '<td>:</td>'
                                        + '<td>' + stations[i].address + '</td>'
                                        + '</tr>'
                                        + '<tr>'
                                        + '<td>Owner</td>'
                                        + '<td>:</td>'
                                        + '<td>' + stations[i].owner + '</td>'
                                        + '</tr>'
                                        + '<tr>'
                                        + '<td>Tel-No</td>'
                                        + '<td>:</td>'
                                        + '<td>' + stations[i].tel + '</td>'
                                        + '</tr>'
                                        + '<tr>'
                                        + '<td>Latitude</td>'
                                        + '<td>:</td>'
                                        + '<td>' + stations[i].lat + '</td>'
                                        + '</tr>'
                                        + '<tr>'
                                        + '<td>Longitude</td>'
                                        + '<td>:</td>'
                                        + '<td>' + stations[i].lon + '</td>'
                                        + '</tr>'
                                        +'<tr>'
                                        +'<td>Outlet Image</td>'
                                        +'<td>:</td>'
                                        +'<td>' +con_url + '</td>'
                                        +'</tr>'
                                        + '</table>';

                        google.maps.event.addListener(outlet_marker, 'mouseover', (function (outlet_marker, content, infowindow) {
                            return function () {
                                infowindow.setContent(content);
                                infowindow.open(map, outlet_marker);
                            };
                        })(outlet_marker, content, infowindow));

                        google.maps.event.addListener(outlet_marker, 'mouseout', (function (outlet_marker, content, infowindow) {
                            return function () {
                                infowindow.close();
                            };
                        })(outlet_marker, content, infowindow));

                    }
                    console.log(_gl_outlet_markers);
                }
            }
        }
    });
}

var i_interval = 0;
var x_loop = 1;
var _km_layer = 0;
var car_spped = 250;
var deg = 0;
var lowcg = "{{ asset('js/gmap/icon/car.png') }}";
var lowcgstrok = "#008BFF";
var resume_k = true;
var prev_k = 0;
var streetVIewPositions = [];
var extkzone = false;

function live_tracking_draw_object() {


    $("#missed_out").val(0);
  i_interval = window.setTimeout(live_tracking_draw_object, car_spped);

  if (resume_k == true) {
      if (typeof routes_[x_loop + 1] != 'undefined') {
          if (extkzone == true) {
              lowcg = "{{ asset('js/gmap/icon/yellow.png') }}";
          } else {
              var map_polyn = new google.maps.Polyline({
                  path: [
                      new google.maps.LatLng(routes_[x_loop].lat,
                              routes_[x_loop].lon),
                      new google.maps.LatLng(routes_[x_loop + 1].lat,
                              routes_[x_loop + 1].lon),
                  ],
                  map: map,
                  strokeColor: '#0B5345',
                  strokeWeight: 4
              });

              lowcgstrok = "#008BFF";
              _gl_polyn.push(map_polyn);
              lowcg = "{{ asset('js/gmap/icon/car.png') }}";
              kzone_default_path = lowcg;

              kzone_rotate = routes_[x_loop].bearing;

              animate_pos.setPosition(
                      new google.maps.LatLng(routes_[x_loop + 1].lat,
                              routes_[x_loop + 1].lon));

              map.panTo(new google.maps.LatLng(routes_[x_loop + 1].lat,
                      routes_[x_loop + 1].lon));

              map.setOptions({zoom: 17});
              streetVIewPositions.push({lat: routes_[x_loop + 1].lat,
              lng: routes_[x_loop + 1].lon});

              _km_layer += (google.maps.geometry.spherical.computeDistanceBetween(
                      new google.maps.LatLng(routes_[x_loop].lat,
                              routes_[x_loop].lon)
                      , new google.maps.LatLng(routes_[x_loop + 1].lat,
                              routes_[x_loop + 1].lon)) / 1000);

              $('#km_ly').text(_km_layer.toFixed(2) + ' km');
              $("#win").text(time[x_loop]);
              $("#btlevel").text(batlevel[x_loop] + "%");
          }

          prev_k = x_loop;
          x_loop++;
      }
  }
}

function pause() {
  resume_k = false;
  // const fenway = {
  // lat: routes_[x_loop + 1].gps_lat,
  // lng: routes_[x_loop + 1].gps_long
  // };
  var fenway = new google.maps.LatLng(
    routes_[x_loop + 1].lat,
    routes_[x_loop + 1].lon
  );
  const panorama = new google.maps.StreetViewPanorama(
    document.getElementById("pano"),
    {
      position: fenway,
      pov: {
        heading: 34,
        pitch: 10,
      },
    }
  );
  map.setStreetView(panorama);
  $("#pano").show();
  $("#routes").width("70%");
  $("#PauseBtn").hide();
  $("#ResumeBtn").show();
}
function resume() {
  map.setOptions({ streetViewControl: false });
  $("#pano").hide();
  $("#routes").width("100%");
  $("#ResumeBtn").hide();
  $("#PauseBtn").show();
  resume_k = true;
  ikk = prev_k;
  live_tracking_draw_object();
}
function volatile_car() {
  var v_car = parseInt($("#car_range").val());
  car_spped = parseInt(int_car - v_car) * 50;

  break_exsist = 0;
}
function doc() {
  volatile_car();
}
function st_view_o() {
  $("#pano").hide();
  $("#routes").width("100%");
  $("#st_x").show();
//   $("#st_o").hide();
}

function draw_sales_orders(date, tso, from, to) {
    $('#sales_orders').val("");
    $.ajax({
        type: 'get',
        url: "/api/gmap/invoiceInfo",
        headers: { Authorization: "Bearer " + localStorage.getItem("token") },
        data: {
            date: date,
            tso: tso,
            from: from,
            to: to
        },
        success: function(data){
            var invoice_data = data;
            var no_of_sales_order = invoice_data.length;
            console.log(data);
            if(date!=''){
                var stations = data;
                _gl_sales_markers = [];
                if (typeof stations != "undefined") {
                    for (var i = 0; i < stations.length; i++) {
                        var sales_markers = new google.maps.Marker({
                            position: new google.maps.LatLng(stations[i].lat, stations[i].lon),
                            icon: "{{asset('js/gmap/icon/Pink-icon.png')}}",
                            map: map
                        });
                        _gl_sales_markers.push({get_marker: sales_markers});
                        _gl_markers.push(sales_markers);

                        var infowindow = new google.maps.InfoWindow();

                        var content = '<table style="color:black;font-size: 10px;font-weight: bold">'
                                        + '<caption style="background:#341b5c;color: #fff;font-weight:bold;font-size: 13px;text-align:center">'
                                        + 'Invoice Details'
                                        + '</caption>'
                                        + '<tr>'
                                        + '<td style="">INV No</td>'
                                        + '<td style="">:</td>'
                                        + '<td style="">' + stations[i].invoice_no + '</td>'
                                        + '</tr>'
                                        + '<tr>'
                                        + '<td>Customer Name</td>'
                                        + '<td>:</td>'
                                        + '<td>' + stations[i].cus_name + '</td>'
                                        + '</tr>'
                                        + '<tr>'
                                        + '<td>Date</td>'
                                        + '<td>:</td>'
                                        + '<td>' + stations[i].inv_date + '</td>'
                                        + '</tr>'
                                        + '<tr>'
                                        + '<td>Time</td>'
                                        + '<td>:</td>'
                                        + '<td>' + stations[i].inv_time + '</td>'
                                        + '</tr>'
                                        + '<tr>'
                                        + '<td>Invoice Amount</td>'
                                        + '<td>:</td>'
                                        + '<td>' + stations[i].net_amount + '</td>'
                                        + '</tr>'
                                        + '</table>';

                        google.maps.event.addListener(sales_markers, 'mouseover', (function (sales_markers, content, infowindow) {
                            return function () {
                                infowindow.setContent(content);
                                infowindow.open(map, sales_markers);
                            };
                        })(sales_markers, content, infowindow));

                        google.maps.event.addListener(sales_markers, 'mouseout', (function (sales_markers, content, infowindow) {
                            return function () {
                                infowindow.close();
                            };
                        })(sales_markers, content, infowindow));
                    }
                }
            }
            $('#invoices').val(no_of_sales_order);
            $('#last_invoice').val(invoice_data[no_of_sales_order - 1].inv_time);
            $('#first_invoice').val(invoice_data[0].inv_time);
        },
    });
    $.ajax({
        type: 'get',
        url:"/api/gmap/salesOrderInfo",
        headers: { Authorization: "Bearer " + localStorage.getItem("token") },
        data: {
            date: date,
            tso: tso,
            from: from,
            to: to
        },
        success: function(data){
            var invoice_data = data;
            console.log(invoice_data);
            var no_of_sales_order = invoice_data.length;
            if(date!=''){
                var stations = data;
                console.log("sales_orders :- "+stations);
                _gl_sales_markers = [];
                if (typeof stations != "undefined") {
                    for (var i = 0; i < stations.length; i++) {
                        var sales_markers = new google.maps.Marker({
                            position: new google.maps.LatLng(stations[i].lat, stations[i].lon),
                            icon: "{{asset('js/gmap/icon/green-marker.png')}}",
                            map: map
                        });
                        _gl_sales_markers.push({get_marker: sales_markers});
                        _gl_markers.push(sales_markers);

                        var infowindow = new google.maps.InfoWindow();

                        var content = '<table style="color:black;font-size: 10px;font-weight: bold">'
                                        + '<caption style="background:#341b5c;color: #fff;font-weight:bold;font-size: 13px;text-align:center">'
                                        + 'Sales Orders Details'
                                        + '</caption>'
                                        + '<tr>'
                                        + '<td style="">SO No</td>'
                                        + '<td style="">:</td>'
                                        + '<td style="">' + stations[i].order_no + '</td>'
                                        + '</tr>'
                                        + '<tr>'
                                        + '<td>Customer Name</td>'
                                        + '<td>:</td>'
                                        + '<td>' + stations[i].cus_name + '</td>'
                                        + '</tr>'
                                        + '<tr>'
                                        + '<td>Date</td>'
                                        + '<td>:</td>'
                                        + '<td>' + stations[i].s_tab_date + '</td>'
                                        + '</tr>'
                                        + '<tr>'
                                        + '<td>Time</td>'
                                        + '<td>:</td>'
                                        + '<td>' + stations[i].s_time + '</td>'
                                        + '</tr>'
                                        + '<tr>'
                                        + '<td>Order Amount</td>'
                                        + '<td>:</td>'
                                        + '<td>' + stations[i].net_amount + '</td>'
                                        + '</tr>'
                                        + '</table>';

                        google.maps.event.addListener(sales_markers, 'mouseover', (function (sales_markers, content, infowindow) {
                            return function () {
                                infowindow.setContent(content);
                                infowindow.open(map, sales_markers);
                            };
                        })(sales_markers, content, infowindow));

                        google.maps.event.addListener(sales_markers, 'mouseout', (function (sales_markers, content, infowindow) {
                            return function () {
                                infowindow.close();
                            };
                        })(sales_markers, content, infowindow));
                    }
                }
            }
            $('#sales_orders').val(no_of_sales_order);
            $('#last_so').val(invoice_data[no_of_sales_order - 1].s_time);
            $('#first_so').val(invoice_data[0].s_time);
        },
    });
}

function invoice_call_orders(date, tso) {
    // $.ajax({
    //     type: 'get',
    //     url: "/api/gmap/callOrderInfo",
    //     headers: { Authorization: "Bearer " + localStorage.getItem("token") },
    //     data: {
    //         date: date,
    //         rep: rep
    //     },
    //     success: function(data){
    //         var invoice_data = data;
    //         var no_of_sales_order = invoice_data.length;

    //         if(date!=''){
    //             var stations = data;
    //             _gl_call_outlet_markers = [];
    //             if (typeof stations != "undefined") {
    //                 for (var i = 0; i < stations.length; i++) {
    //                     var call_outlet_markers = new google.maps.Marker({
    //                         position: new google.maps.LatLng(stations[i].latitude, stations[i].longitude),
    //                         icon: "{{asset('gmap/yellow2.png')}}",
    //                         map: map
    //                     });
    //                     _gl_call_outlet_markers.push({get_marker: call_outlet_markers});
    //                     _gl_markers.push(call_outlet_markers);

    //                     var infowindow = new google.maps.InfoWindow();

    //                     var content = '<table style="color:black;font-size: 10px;font-weight: bold">'
    //                                     + '<caption style="background:#341b5c;color: #fff;font-weight:bold;font-size: 13px;text-align:center">'
    //                                     + 'Unplanned Visit Details'
    //                                     + '</caption>'
    //                                     + '<tr>'
    //                                     + '<td style="">SO No</td>'
    //                                     + '<td style="">:</td>'
    //                                     + '<td style="">' + stations[i].s_no + '</td>'
    //                                     + '</tr>'
    //                                     + '<tr>'
    //                                     + '<td>Date</td>'
    //                                     + '<td>:</td>'
    //                                     + '<td>' + stations[i].s_tab_date + '</td>'
    //                                     + '</tr>'
    //                                     + '<tr>'
    //                                     + '<td>Time</td>'
    //                                     + '<td>:</td>'
    //                                     + '<td>' + stations[i].s_tab_time + '</td>'
    //                                     + '</tr>'
    //                                     + '<tr>'
    //                                     + '<td>Order Amount</td>'
    //                                     + '<td>:</td>'
    //                                     + '<td>' + stations[i].s_order_amt + '</td>'
    //                                     + '</tr>'
    //                                     + '</table>';

    //                     google.maps.event.addListener(call_outlet_markers, 'mouseover', (function (call_outlet_markers, content, infowindow) {
    //                         return function () {
    //                             infowindow.setContent(content);
    //                             infowindow.open(map, call_outlet_markers);
    //                         };
    //                     })(call_outlet_markers, content, infowindow));

    //                     google.maps.event.addListener(call_outlet_markers, 'mouseout', (function (call_outlet_markers, content, infowindow) {
    //                         return function () {
    //                             infowindow.close();
    //                         };
    //                     })(call_outlet_markers, content, infowindow));
    //                 }
    //             }
    //         }
    //         $('#call').val(no_of_sales_order);
    //     }
    // });
    $.ajax({
        type: 'get',
        url: "/api/gmap/unproductiveVisits",
        headers: { Authorization: "Bearer " + localStorage.getItem("token") },
        data: {
            date: date,
            tso: tso
        },
        success: function(data){
            var invoice_data = data;
            var no_of_sales_order = invoice_data.length;
            if(date!=''){
                var stations = data;
                _gl_unoutlet_markers = [];
                if (typeof stations != "undefined") {
                    for (var i = 0; i < stations.length; i++) {
                        console.log("unpor_gps"+stations[i].unpro_latitude);
                        var unoutlet_markers = new google.maps.Marker({
                            position: new google.maps.LatLng(stations[i].unpro_latitude, stations[i].unpro_longitude),
                            icon: "{{asset('js/gmap/icon/red_icon.png')}}",
                            map: map
                        });
                        _gl_unoutlet_markers.push({get_marker: unoutlet_markers});
                        _gl_markers.push(unoutlet_markers);

                        var infowindow = new google.maps.InfoWindow();

                        var content = '<table style="color:black;font-size: 10px;font-weight: bold">'
                                        + '<caption style="background:#341b5c;color: #fff;font-weight:bold;font-size: 13px;text-align:center">'
                                        + 'Unproductive Details'
                                        + '</caption>'
                                        // + '<tr>'
                                        // + '<td style="">Unproductive No</td>'
                                        // + '<td style="">:</td>'
                                        // + '<td style="">' + stations[i].unpro_no + '</td>'
                                        // + '</tr>'
                                        + '<tr>'
                                        + '<td>Date</td>'
                                        + '<td>:</td>'
                                        + '<td>' + stations[i].unpro_date + '</td>'
                                        + '</tr>'
                                        + '<tr>'
                                        + '<td>Time</td>'
                                        + '<td>:</td>'
                                        + '<td>' + stations[i].unpro_time + '</td>'
                                        + '</tr>'
                                        + '<tr>'
                                        + '<td>Customer Name</td>'
                                        + '<td>:</td>'
                                        + '<td>' + stations[i].cus_name + '</td>'
                                        + '</tr>'
                                        + '<tr>'
                                        + '<td>Reason Name</td>'
                                        + '<td>:</td>'
                                        + '<td>' + stations[i].reason_name + '</td>'
                                        + '</tr>'
                                        + '</table>';

                        google.maps.event.addListener(unoutlet_markers, 'mouseover', (function (unoutlet_markers, content, infowindow) {
                            return function () {
                                infowindow.setContent(content);
                                infowindow.open(map, unoutlet_markers);
                            };
                        })(unoutlet_markers, content, infowindow));

                        google.maps.event.addListener(unoutlet_markers, 'mouseout', (function (unoutlet_markers, content, infowindow) {
                            return function () {
                                infowindow.close();
                            };
                        })(unoutlet_markers, content, infowindow));
                    }
                }
            }
            $('#unpro').val(no_of_sales_order);
            $('#call_orders').val(0);
        }
    });
}


</script>
