var tableUsers;
$( document ).ready(function() {
	 tableUsers = $('#data-table-users').DataTable({
		 "responsive": true,
		 "ajax": {
			 "url": "/GolgoFramework/api/Users?op=read&sop=loadAll",
	         "dataSrc": "result"
	     },
	     "columns": [
	                 { "data": "" },
	                 { "data": "email" },
	                 { "data": "telephone" },
	                 { "data": "userName" },
	                 { "data": "userType" },
	                 { "data": "dateCreated" },
	                 { "data": "" },
	             ],
		 "columnDefs": [ {
	          "targets": 'no-sort',
	          "orderable": false,
	    	}, {
	        	"render": function ( data, type, row ) {
	        		var html = '<button class="mdl-button mdl-js-button mdl-button--icon js-view-user" data-id="' + row.id+ '" title="Ver/Editar">\
									<i class="material-icons">search</i>\
								</button>';
					if(row.isActive == 1) {
						html += '<button class="mdl-button mdl-js-button mdl-button--icon js-block-user" data-id="' + row.id + '"" title="Bloquear">\
						  		<i class="material-icons js-is-active">lock_open</i>\
								</button>';
   					} else {
   						html += '<button class="mdl-button mdl-js-button mdl-button--icon js-unblock-user" data-id="' + row.id + '"" title="Desbloquear">\
				  				<i class="material-icons js-is-active">lock</i>\
								</button>';
   					}
					html += '<button class="mdl-button mdl-js-button mdl-button--icon js-delete-user" data-id="' + row.id + '" title="Borrar"">\
					  <i class="material-icons">delete</i>\
					</button>';
	            return html;
	        	},
	        	"targets": 6
	    	},
	    	{
	        	"render": function ( data, type, row ) {
	        		var html = row.name +" "+ row.firstName
	        		return html;
	        	},
	        	"targets": 0
	    	}
	    ],
	    "oLanguage": {
	        "sLoadingRecords": '<div class="mdl-progress mdl-js-progress mdl-progress__indeterminate"></div>',
	        "sProcessing": "Procesando...",
	        "sLengthMenu": "Mostrar _MENU_ registros",
	        "sZeroRecords": "No se encontraron resultados",
	        "sInfo": "Mostrando desde _START_ hasta _END_ de _TOTAL_ registros",
	        "sInfoEmpty": "Mostrando desde _START_ hasta _END_ de _TOTAL_ registros",
	        "sInfoFiltered": "(filtrado de un total de _MAX_ líneas)",
	        "sInfoPostFix": "",
	        "sSearch": "Buscar:",
	        "sUrl": "",
		    "oPaginate": {
		    "sFirst":    "Primero",
		    "sPrevious": "Anterior",
		    "sNext":     "Siguiente",
		    "sLast":     "Último"
		    }
	      },
        "fnRowCallback": function( nRow, aData ) {
    	  	var isActive = aData.isActive; // ID is returned by the server as part of the data
    	  	var $nRow = $(nRow); // cache the row wrapped up in jQuery
    	  	if (isActive == 0) {
    	  	  $nRow.addClass("mdl-color--red-50");
    	 	}
    	  	return nRow;
    	 }
	    
	 });
	 
	 $('#js-new-user').on('click',function(e) {
		e.preventDefault();
		getChunkUsuario(0,false);
	 });
	
	 function getChunkUsuario(id,isAdmin) {
			var url;
			if (id == 0)
				var url = "/GolgoFramework/chunks/usuarios";
			else
				url = "/GolgoFramework/chunks/usuarios?id=" + id;
			$.ajax({
				url : url,
				type : 'GET',
				beforeSend : function() {
					showChunkLoading($('#js-usuarios-chunk-holder'))
				},
				complete : function() {
					hideChunkLoading($('#js-usuarios-chunk-holder'))
				},
				success : function(response) {
					if (response && response != null) {
						$('#js-usuarios-chunk-holder').html(response);
						initChunkUsuarios(isAdmin);
					}
					componentHandler.upgradeDom();
				},
				error : function(xhr) {
					handleAjaxError(xhr);
				}
			});
		}
	 
	 
	 $('#data-table-users').on('click','.js-view-user',function(e) {
		var userid = ($(this).data('id'));
		getChunkUsuario(userid,false);
	 });
	 
	 $('#data-table-users').on('click','.js-delete-user',function(e) {
		 var modelId = $(this).data('id');
		 var row = $(this).closest('tr');
		 swal({ 
			  title: "¿Eliminar Usuario?",   
		         text: "La cuenta del usuario será eliminada y se borrarán todos los registros y datos asociados a la cuenta.",   
		         type: "warning",   
		         showCancelButton: true,   
		         confirmButtonColor: "#DD6B55",   
		         confirmButtonText: "Eliminar usuario",
		         cancelButtonText: "Cancelar",
		         showLoaderOnConfirm: true,
		         allowOutsideClick: false,
	     }).then(function(){
	    	 setTimeout(function(){
	    		 
	    		 var postData = {
							op: "delete",
							id: modelId
						};
	    		 
		    	 $.ajax({
		 	        url: "/GolgoFramework/api/Users",
		 	        type: 'POST',
		 	        data: postData,
		 	        success: function (data) {
		 	        	if(data.result != undefined  && data.result == true) {
		 	        		swal("¡Listo!", "Usuario eliminado", "success");
		 	        		row.remove();
		 	        	} else {
		 	        		swal("¡Error!", "No se pudo eliminar en estos momentos...", "error");
		 	        	}
		 	        },
		 	       error: function(xhr) {
		 	    	  handleAjaxError(xhr)
		 	        }
		 	    });
		    	  }, 100);
	     });
	 });
	 $('#data-table-users').on('click','.js-block-user',function(e) {
		 
		 var id = $(this).data('id');
		 var lock = $(this).find('.js-is-active');
		 var button = $(this);
		 var row = $(this).closest('tr');
		 swal({   
			 title: "¿Bloquear Usuario?",   
	         text: "La cuenta de usuario quedará bloqueada y no se podrá acceder, no se borrará ningún dato.",   
	         type: "warning",   
	         showCancelButton: true,   
	         confirmButtonColor: "#DD6B55",   
	         confirmButtonText: "Bloquear usuario",
	         cancelButtonText: "Cancelar",
	         showLoaderOnConfirm: true,
	         allowOutsideClick: false,
	     }).then(function(){
	    		 console.log("wahhhhat")
	    		 
	    		 		var postData = {
							op: "update",
							sop: "block",
							id: id
						};
	    		 
				    	 $.ajax({
				 	        url: "/GolgoFramework/api/Users" ,
				 	        type: 'POST',
				 	       data: postData,
				 	        success: function (data) {
				 	        	if(data.result != undefined && data.result == true) {
				 	        		swal("¡Listo!", "Usuario Bloqueado", "success"); 
				 	        		button.removeClass('js-block-user');
				 	        		button.attr("title", "Desbloquear");
				 	        		 button.addClass('js-unblock-user');
				 	        		 row.addClass("mdl-color--red-50");
				 	        		lock.html('lock');
				 	        	} else {
				 	        		swal("¡Error!", "No se pudo bloquear al usuario en estos momentos...", "error");
				 	        	}
				 	        },
				 	        error: function(xhr) {
				 	        	handleAjaxError(xhr)
				 	        },
				 	    });
	     });
		 
		 
	 });
	 
	 $('#data-table-users').on('click','.js-unblock-user',function(e) {
		 
		 var id = $(this).data('id');
		 var lock = $(this).find('.js-is-active');
		 var button = $(this);
		 var row = $(this).closest('tr');
		 swal({   
			 title: "¿Desbloquear Usuario?",   
	         text: "El usuario volverá a estar activo y funcionar con normalidad.",   
	         type: "warning",   
	         showCancelButton: true,   
	         confirmButtonColor: "#DD6B55",   
	         confirmButtonText: "Desbloquear usuario",
	         cancelButtonText: "Cancelar",
	         closeOnConfirm: false,
	         showLoaderOnConfirm: true,
	         allowOutsideClick: false,
	     }).then(function(){
	    	 setTimeout(function(){      
	    		 
	    		 var postData = {
							op: "update",
							sop: "unblock",
							id: id
						};
	    		 
		    	 $.ajax({
		 	        url: "/GolgoFramework/api/Users",
		 	        type: 'POST',
		 	       data: postData,
		 	        success: function (data) {
		 	        	if(data.result != undefined && data.result == true) {
		 	        		swal("¡Listo!", "Usuario desbloqueado", "success");
		 	        		button.attr("title", "Bloquear");
		 	        		 button.removeClass('js-unblock-user');
		 	        		 button.addClass('js-block-user');
		 	        		 lock.html('lock_open');
		 	        		row.removeClass("mdl-color--red-50");
		 	        	} else {
		 	        		swal("¡Error!", "No se pudo desbloquear al usuario en estos momentos...", "error");
		 	        	}
		 	        },
		 	       error: function(xhr) {
		 	    	  handleAjaxError(xhr)
		 	       }
		 	    });
		    	  }, 100);
	     });
	 });
	
	 
	
	 
 });
 
