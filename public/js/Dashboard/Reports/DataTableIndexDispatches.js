DataTableIndexDispatches();
function DataTableIndexDispatches() {
    $.ajax({
        url: `/Dashboard/Reports/Dispatches/Index`,
        type: 'GET',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            let columns = [];
            response.data.forEach(column => {
                columns.push({
                    'data': column
                });
            });

            $("#dispatches").DataTable({
                ajax: {
                    url: '/Dashboard/Reports/Dispatches/Index/Query',
                    type: 'POST',
                    data: function(request) {
                        request._token = $('meta[name="csrf-token"]').attr('content');
                    },
                    dataSrc: function (responses) {
                        console.log(responses.data);
                        return responses.data;
                    },
                },
                columns: columns,
                dom: 'lBfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            });
        },
        error: function(xhr, textStatus, errorThrown) {

        }
    });
}