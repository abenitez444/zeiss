// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable({
    "bFilter": true,
    "bPaginate": true,
    "bInfo" : true,
    "lengthChange": true,
    "info": true
  });
});


