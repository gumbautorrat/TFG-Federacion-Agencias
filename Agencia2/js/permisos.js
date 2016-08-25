
	function main(){
		
		var user  = $('select#usuario').val();
		var table = $('select#tabla').val();

		/*Cargamos los datos de la tabla que muestra los permisos
		  del usuario y tabla cargados por defecto */
		obtenerPermisosUsuario();

	}

	//Cada vez que cambie el usuario seleccionado
	$('select#usuario').on('change',function(){
		//var user  = $(this).val();
		//var table = $('select#tabla').val();
		obtenerPermisosUsuario();
	});

	//Cada vez que cambie la tabla seleccionada
	$('select#tabla').on('change',function(){
		//var table = $(this).val();
		//var user  = $('select#usuario').val();
		obtenerPermisosUsuario();
	});

	/*Cada vez que pulsemos en el boton quitar/añadir 
	  permisos de inserciones en la tabla seleccionada */
	$("#btn-add").click(function() {
		var textoBtn = $("#btn-add").text();
		var table    = $('select#tabla').val();
		var permiso  = table+"/insertar"; 

		if(textoBtn == " Añadir"){
			addDelPermisosUsuario(1,permiso,"add");
			/*addIcoPermOk("add-est");
			addBtnQuitar("add");*/
		}else if(textoBtn == " Quitar"){
			addDelPermisosUsuario(0,permiso,"add");
			/*addIcoPermNOk("add-est");
			addBtnAdd("add");*/
		}
	});

	/*Cada vez que pulsemos en el boton quitar/añadir 
	  permisos de actualizaciones en la tabla seleccionada */
	$( "#btn-upd" ).click(function() {
		var textoBtn = $("#btn-upd").text();
		var table    = $('select#tabla').val();
		var permiso  = table+"/actualizar"; 

		if(textoBtn == " Añadir"){
			addDelPermisosUsuario(1,permiso,"upd");
			/*addIcoPermOk("upd-est");
			addBtnQuitar("upd");*/
		}else if(textoBtn == " Quitar"){
			addDelPermisosUsuario(0,permiso,"upd");
			/*addIcoPermNOk("upd-est");
			addBtnAdd("upd");*/
		}
	});

	/*Cada vez que pulsemos en el boton quitar/añadir 
	  permisos de borrados en la tabla seleccionada */
	$( "#btn-del" ).click(function() {
		var textoBtn = $("#btn-del").text();
		var table    = $('select#tabla').val();
		var permiso  = table+"/eliminar"; 

		if(textoBtn == " Añadir"){
			addDelPermisosUsuario(1,permiso,"del");
			/*addIcoPermOk("del-est");
			addBtnQuitar("del");*/
		}else if(textoBtn == " Quitar"){
			addDelPermisosUsuario(0,permiso,"del");
			/*addIcoPermNOk("del-est");
			addBtnAdd("del");*/
		}
	});

	/*Cada vez que pulsemos en el boton quitar/añadir 
	  permisos de listados de la tabla seleccionada */
	$( "#btn-list" ).click(function() {
		var textoBtn = $("#btn-list").text();
		var table    = $('select#tabla').val();
		var permiso  = table+"_paginado_filtro";

		if(textoBtn == " Añadir"){
			addDelPermisosUsuario(1,permiso,"list");
			/*addIcoPermOk("list-est");
			addBtnQuitar("list");*/
		}else if(textoBtn == " Quitar"){
			addDelPermisosUsuario(0,permiso,"list");
			/*addIcoPermNOk("list-est");
			addBtnAdd("list");*/
		}
	});

	/* Funcion que crea un boton de color rojo con el texto Quitar y el icono (X) en la columna modificar de la fila que corresponda
	al id pasado como parametro */
	function addBtnQuitar(id){
		//Al boton con el identificador igual al parametro id, le machacamos lo que tenga dentro poniendole un span con el icono más y el texto Quitar 
		$("#btn-"+id).html("<span id=\""+id+"\" ></span> Quitar");
		$("#"+id).addClass("glyphicon glyphicon-remove");
		//Si antes el boton tenia la clase succes (color verde) se la quitamos y ponemos la danger (color rojo)
		//COLOR ROJO ELIMINAR PERMISO
		$("#btn-"+id).removeClass("btn-success");
		$("#btn-"+id).addClass("btn-danger");

		/*$("#btn-"+id).removeClass("btn-danger");
		$("#btn-"+id).addClass("btn-success");*/
		
	}

	/* Funcion que crea un boton de color verde con el texto Añadir y el icono (+) en la columna modificar de la fila que corresponda
	al id pasado como parametro*/
	function addBtnAdd(id){
		$("#btn-"+id).html("<span id=\""+id+"\" ></span> Añadir");
		$("#"+id).addClass("glyphicon glyphicon-plus");
		//COLOR ROJO ELIMINAR PERMISO
		$("#btn-"+id).removeClass("btn-danger");
		$("#btn-"+id).addClass("btn-success");

		/*$("#btn-"+id).removeClass("btn-success");
		$("#btn-"+id).addClass("btn-danger");*/
		
	}

	/* Funcion que añade el icono que representa el que el usuario tiene permisos */
	function addIcoPermOk(id){
		$("#"+id).removeClass("glyphicon glyphicon-minus");
		$("#"+id).addClass("glyphicon glyphicon-ok");
	}

	/* Funcion que añade el icono que representa el que el usuario no tiene permisos */
	function addIcoPermNOk(id){
		$("#"+id).removeClass("glyphicon glyphicon-ok");
		$("#"+id).addClass("glyphicon glyphicon-minus");
	}

	/* Funcion que hace una peticion a un servicio web que devuelve 
	   los permisos del usuario y tabla que esten seleccionados */
	function obtenerPermisosUsuario(){

		var user  = $('select#usuario').val();
		var table = $('select#tabla').val();

		$.ajax({

			data: { user: usuario, 
					pass: pasword,
					agencia: agencia,
					usuario: user,
					tabla: table},
					url:  'http://apirest/permisos/tabla/',
					type: 'POST',

			success: function(data, status, jqXHR) {
				console.log(data);

				if(data.message == 'Autentication Failure'){
					mostrarMensaje("No estas autorizado para la gestión de permisos","../../index.php");
				}else if(data.message == 'OK'){
					mostrarPermisos(data);
				}else{
					mostrarMensajePermisos("No estas autorizado para añadir/eliminar permisos a ese usuario");
				}

			},

			error: function (jqXHR, status) { 
				mostrarMensaje("Fallo en la petición al Servicio Web","../../index.php");
			}
			
		});

	}

	/*Funcion que recoge los datos devueltos por el servicio web
	  y dibuja los iconos y botones que correspondan */
	function mostrarPermisos(data){

		if(data.add == 1){
			addIcoPermOk("add-est");
			addBtnQuitar("add");
		}else{
			addIcoPermNOk("add-est");
			addBtnAdd("add");
		}
		if(data.upd == 1){
			addIcoPermOk("upd-est");
			addBtnQuitar("upd");
		}else{
			addIcoPermNOk("upd-est");
			addBtnAdd("upd");
		}
		if(data.del == 1){
			addIcoPermOk("del-est");
			addBtnQuitar("del");
		}else{
			addIcoPermNOk("del-est");
			addBtnAdd("del");
		}
		if(data.list == 1){
			addIcoPermOk("list-est");
			addBtnQuitar("list");
		}else{
			addIcoPermNOk("list-est");
			addBtnAdd("list");
		}

		/*Una vez dibujados los permisos del usuario, 
		el selector de usuario obtiene el foco */
		$('select#usuario').focus(); 

	}

	/* Funcion que hace una peticion a un servicio web para poner
	   o quitar permisos a un usuario determinado*/
	function addDelPermisosUsuario(accion,permiso,boton){

		var user  = $('select#usuario').val();
		//alert("Usuario "+usuario+" Pass "+ pasword +" Agencia "+agencia+" User "+user+" Accion "+accion+" Permiso "+permiso);

		$.ajax({

			data: { user: usuario, 
					pass: pasword,
					agencia: agencia,
					usuario: user,
					permiso: permiso,
					accion: accion},
					url:  'http://apirest/permisos/addDel/',
					type: 'POST',

			success: function(data, status, jqXHR) {
				console.log(data);

				if(data.message == 'Autentication Failure'){
					mostrarMensajePermisos("Ha habido un fallo de Autenticacion");
				}else if(data.message == 'NOK'){
					mostrarMensajePermisos("No estas autorizado para añadir/eliminar permisos a ese usuario");
				}else{
					if(data.message == 'add OK' || data.message == 'del OK'){
						refreshBtnPermiso(accion,boton);
					}else if(data.message == 'add NOK' || data.message == 'del NOK'){
						//mostrarMensajePermisos("Alguien ha cambiado este mismo permiso antes de que tú.");
						//refreshBtnPermiso(accion,boton);//Si al modificar un permiso solo queremos actualizar el boton pulsado sin actualizar los demas.
						obtenerPermisosUsuario();//Si queremos aprovechar para refrescar el estado de todos los botones de permisos de la tabla seleccionada.
					}else{
						mostrarMensajePermisos(data.message); //Mostramos el mensaje de error cuando se produzca
					}
				}

			},

			error: function (jqXHR, status) {
				console.log(jqXHR);          
				//console.log("Fallo en la petición al Servicio Web");
			}
			
		});

	}

	//Actulializamos el boton de permiso una vez hemos presionado en el, para quitar/añadir permisos a alguna tabla
	function refreshBtnPermiso(accion,boton){

		if(boton == 'add'){

			if(accion == 1){
				addIcoPermOk("add-est");
				addBtnQuitar("add");
			}else{
				addIcoPermNOk("add-est");
				addBtnAdd("add");
			}

		}else if(boton == 'upd'){

			if(accion == 1){
				addIcoPermOk("upd-est");
				addBtnQuitar("upd");
			}else{
				addIcoPermNOk("upd-est");
				addBtnAdd("upd");
			}

		}else if(boton == 'del'){

			if(accion == 1){
				addIcoPermOk("del-est");
				addBtnQuitar("del");
			}else{
				addIcoPermNOk("del-est");
				addBtnAdd("del");
			}

		}else{

			if(accion == 1){
				addIcoPermOk("list-est");
				addBtnQuitar("list");
			}else{
				addIcoPermNOk("list-est");
				addBtnAdd("list");
			}

		}

	}