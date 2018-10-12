{
    let $routes = $('#routes');
    let route = {
        list: $routes.data('urlList'),
        delete: $routes.data('urlDelete') + '/',
        edit: $routes.data('urlEdit') + '/'
    };
    
    let $blogTable = $('#blogTable');
    let blogTable = $blogTable.DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "ajax": route.list,
        "columnDefs": [ {
            "targets": -1,
            "data": null,
            "defaultContent": '<div class="text-center"> <button class="btn btn-xs btn-warning"><i class="fa fa-pencil editItem" aria-hidden="true"></i></button> <button class="btn btn-xs btn-danger deleteItem"><i class="fa fa-remove" title="Remove"></i></button> </div>'
        } ]
    });

    setInterval( () => {
        blogTable.ajax.reload( null, false ); // user paging is not reset on reload
    }, 10000 );
    
    $blogTable.on('click', '.editItem', function() {
        let $row = $(this).parents('tr');
        let data = blogTable.row( $row ).data();
        
        window.location.href = route.edit + data[0];
    });

    $blogTable.on('click', '.deleteItem', function() {
        let $row = $(this).parents('tr');
        let data = blogTable.row( $row ).data();
        console.log(data);
        
        $.ajax(
            route.delete + data[0],
            {
                method: 'POST',
                error: function (jqXHR, status) {
                    alert(status);
                },
                complete: function () {
                    blogTable.ajax.reload();
                }
            }
            );
    });
}