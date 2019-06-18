$(() => {
    var current_page=1;
    var sort_by = "id";
    var sort_order_asc = true;
    
    $('#taskcontainer').ready(() => {
        mainReload();
    });
    
    mainReload = () => {
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        $.ajax({
            type: 'get',
            url: '/beejee/controller/tasks.php',
            data: {
                'current_page': current_page,
                'sort_by': sort_by,
                'sort_order_asc': sort_order_asc
            },
            success: results => {
                $('#taskcontainer').html(results);
            },
            error: () => {
                alert('Load error');
            }
        });
    }

    $('#taskcontainer').on("click","#admin-login-btn", () => {
      
        var formData = [];
        formData.username = $('#admin-username').val();
        formData.password = $('#admin-password').val(); 
        
        $.ajax({
        type: 'post',
        url: '/beejee/controller/login.php',
        data: {
                'username':formData.username,
                'password':formData.password,
              },
        success: function(results) {
            $('#admin-login-modal').modal('hide');
            mainReload();
        },
        error: function() {
            alert('Login error');
        }
        });
    });
    
    $('#taskcontainer').on("click","#admin-logout-btn", () => {
        $.ajax({
        type: 'post',
        url: '/beejee/controller/logout.php',
        success: function(results) {
            mainReload();
        },
        error: function() {
            alert('Login error');
        }
        });
    });


    $('#taskcontainer').on("click","#add-task-btn-submit", () => {
      
        var formData = [];
        formData.username = $('#username').val();
        formData.email    = $('#email').val(); 
        formData.text     = $('#task-text').val(); 
        formData.completed= 0; 
        
        $.ajax({
        type: 'post',
        url: '/beejee/controller/task_add.php',
        data: {'username':formData.username,
                'email':formData.email,
                'text':formData.text,
                'completed':formData.completed
              },
        success: function(results) {
            alert(results);
            $('#add-task-modal').modal('hide');
            mainReload();
        },
        error: function() {
            alert('Write error');
        }
        });
    });

    $('#taskcontainer').on("click","#edit-task-btn-submit", () => {
      
        var formData = [];
        formData.id       = $('#id-edit').val();
        formData.username = $('#username-edit').val();
        formData.email    = $('#email-edit').val(); 
        formData.text     = $('#task-text-edit').val(); 
        formData.completed= $('#completed-edit').val(); 
        formData.completed= $('#completed-edit').prop("checked") ? 1 : 0;
        $.ajax({
        type: 'post',
        url: '/beejee/controller/task_edit.php',
        data: { 
                'id':formData.id,
                'username':formData.username,
                'email':formData.email,
                'text':formData.text,
                'completed':formData.completed
              },
        success: function(results) {
            alert(results);
            $('#edit-task-modal').modal('hide');
            mainReload();
        },
        error: function() {
            alert('Write error');
        }
        });
    });

    $('#taskcontainer').on("click", "#page-prev", (e) => {
        if(current_page>1)
        {
            current_page--;
            mainReload();  
        }
     })
     $('#taskcontainer').on("click", "#page-next", (e) => {
        var last_page = $('.page-link').length - 2;
        if(current_page<last_page)
        {
            current_page++;
            mainReload();
        }  
     })

    $('#taskcontainer').on("click", "#page-link", (e) => {
            current_page = $(e.target).data("page");
            mainReload();  
    })

    $('#taskcontainer').on("click", "#sort-by-btn", (e) => {
        if(sort_by == $(e.target).data("sortby"))
        {
            sort_order_asc = !sort_order_asc;
        }
        else {
            sort_by = $(e.target).data("sortby");
            sort_order_asc = true;
        }
        mainReload();  
    })

    $('#taskcontainer').on("click", "#task-edit-btn", function (e) {
        $('#id-edit').val($(e.currentTarget).data("id"));
        $('#username-edit').val($(e.currentTarget).data("username"));
        $('#email-edit').val($(e.currentTarget).data("email"));
        $('#task-text-edit').val($(e.currentTarget).data("text"));
        $('#completed-edit').val($(e.currentTarget).data("completed"));
        if($(e.currentTarget).data("completed") !=0 ) {
            $('#completed-edit').prop("checked", true);
        }
        if($(e.currentTarget).data("completed") ==0 ) {
            $('#completed-edit').prop("checked", false);
        }
        
    })

});