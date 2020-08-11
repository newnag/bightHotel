$(function () { 
    orderTable = $('#carrier-grid').DataTable({     
    "scrollX": true,
    "processing": true, 
    "serverSide": true,
    "ajax": {
        url: "ajax/ajax.carrier.php",
        "data": function(d) {
          d.type = 'publish';
          d.date = $('#add-date-display').val();
          d.action = "order_carrier_publsih"; 
        },
        type: "post",
        error: function(){					
          $(".employee-grid-error").html("");
          $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3"></th></tr></tbody>');
          $("#employee-grid_processing").css("display","none"); 
        } 
    }, 

    "columnDefs": [{
      targets: [2,5,6],
      orderable: false
    }],
    "pageLength": 50,
    "columns": [
      { "width": "17%", "targets": 0 },
      { "width": "10%", "targets": 1 },
      { "width": "14%", "targets": 2 },
      { "width": "17%", "targets": 3 },
      { "width": "15%", "targets": 4 },
      { "width": "10%", "targets": 5 },
      { "width": "20%", "targets": 6 }
    ],
    "fixedColumns": true,
    "order": [[ 4, "desc" ]] 
  });
});