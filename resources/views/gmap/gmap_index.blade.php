<link rel="stylesheet" href="{{ asset('css/gmap/gmap.css') }}">
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">

<script src="{{ asset('js/jquery_3.2.1.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
{{-- <script src="{{ asset('js/gmap/gmap_view.js') }}"></script> --}}
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/FileSaver.js') }}"></script>
<script src="{{ asset('js/gmap/tabs.js') }}"></script>
<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyCsAQASjLDim87C0ojgFxIY3rB2ZHMwq5M" ></script>
<script>
    $(function () {
  loadTsos();
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
</script>

<div class="container">

    <div class="content">
        <div id="tabs">
            <ul>
                <li><a href="#" rel="/gmapView" class="selected"  onclick="loadit(this)" onload="loadit(this)">DRAW PATH</a></li>
                <li><a href="#" rel="/gmapOutletsView" onClick="loadit(this)">OUTLETS</a></li>

                <li style="
                    border:none;
                    text-align:center;
                    color:#17202A;
                    float: right;
                    /*margin:0px 300px 0px 200px;*/
                    font-weight: bold;
                    font: 100%/1.4 Verdana, Arial, Helvetica, sans-serif;
                    letter-spacing: 1px;
                    text-transform: uppercase;
                    text-shadow: 1px 1px #000;
                    font-size: 18px;">
                    <i class="fas fa-map-marked-alt" style="font-size:20px; color:#1ABC9C"></i>&nbsp; GPS TRACKING</li>

            </ul>
            <center>
                {{-- <iframe style="height: 855px;width:110%" id="container"></iframe> --}}
            </center>
        </div>
        <!-- InstanceEndEditable -->
    </div>
