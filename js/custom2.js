   var currLat;
   var curLong;
   var radius;
   var cultural;
   var texts = [];
   var tags = [];


   function getTags() {
       $.ajax({
           url: 'php/get_all_tags.php', //get from API all possible tags
           type: 'GET',
           dataType: '',
           success: function(data) {
               var parsed = $.parseJSON(data);
               $.each(parsed.all_tags, function(index, val) { //creates array that tags plugin uses
                   tags.push({
                       id: val.tag_public_id,
                       toString: function() {
                           return val.tag_name;
                       }
                   });
               });
           }

       });
       $('#tags').tagSelector(tags, 'tags'); //execute and render tag addition area
   }

   function success(position) {
       $('#locating').hide();
       currLat = position.coords.latitude;
       currLong = position.coords.longitude;
       $('#latitude').val(currLat);
       $('#longitude').val(currLong);
   }

   function error(position) {
       $('#locating').hide();
       alert('Sorry...we can\'t find your location. Please manually enter the values.');
   }

   function geoIP() {
       if (navigator.geolocation) {
           navigator.geolocation.getCurrentPosition(success, error);
       } else {
           error('not supported');
           alert('Sorry...we can\'t find your location. Please manually enter the values.');
       }
   }

   function runSearch(clickedLatitude, clickedLongitude) {
       //AJAX to PHP
       $.ajax({
           url: 'assets/php/get_nearby.php',
           type: 'POST',
           dataType: 'JSON',
           data: {
               latitude: clickedLatitude,
               longitude: clickedLongitude
           },
           success: function(data) {}
       }).done(function() {
           console.log("success");
       }).fail(function() {
           console.log("failed");
       }).always(function() {
           console.log("complete");
       });
   }
   

   function runQuery() {
       $.ajax({
           url: 'php/get_nearby.php',
           type: 'POST',
           dataType: 'JSON',
           data: {
               latitude: currLat,
               longitude: currLong,
               radius: radius,
               cultural: cultural,
               tags: texts
           },
           success: function(data) {
               $('#searchingnow').hide();
               $('#searchArea').hide();
               $('#searchagain').show();
               final_data = data.data;
               var results = $("#results_datatable").DataTable({
                   "data": final_data,
                   "paging": true,
                   "dom": '<"top">fBt<"bottom">p<"clear">',
                   "pageLength": 10,
                   "order": [],
                   "columns": [{
                       "data": "id",
                       "searchable": true,
                       "width": "10%",
                       "className": "lang_body_2",
                       "title": "Canonical ID"
                   }, {
                       "data": "name",
                       "searchable": true,
                       "width": "20%",
                       "className": "lang_body_2",
                       "title": "Name"
                   }, {
                       "data": "description",
                       "searchable": true,
                       "sortable": true,
                       "width": "70%",
                       "className": "lang_body_2",
                       "title": "Description"
                   }, ],
                   "drawCallback": function(settings, json) {
                       $("#imloading").hide();
                       //function here like: drawme();
                   }
               });
           }
       });
   }
   $(document).ready(function() {
       getTags()
       $('#newSearch').click(function(event) {
           location.reload();
       });
       $('#goCurrent').click(function(event) {
           $('#locating').show();
           geoIP();
       });
       $('#geocode').click(function(event) {
           $('#locating2').show()
           var address = $('#address').val();
           $.ajax({
               url: 'https://www.mapquestapi.com/geocoding/v1/address?key=HKl46IPy8LeIaG2JVq55dyV7VVALRShA',
               type: 'POST',
               dataType: 'JSON',
               data: {
                   location: address
               },
               success: function(data) {
                   $('#locating2').hide();
                   currLong = data.results[0].locations[0].latLng.lng;
                   currLat = data.results[0].locations[0].latLng.lat;
                   $('#latitude').val(currLat);
                   $('#longitude').val(currLong);
               }
           })
       });
       $('#goSearch').click(function(event) {
           texts = $(".tag input").map(function() {
               return $(this).val();
           }).get();
           currLat = $('#latitude').val();
           currLong = $('#longitude').val();
           radius = $('#radius').val();
           cultural = $('#cultural').val();
           $('#searchingnow').show();
           runQuery()
       });
   });