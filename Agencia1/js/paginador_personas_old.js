var usuario;
var pasword;
var nom_agencia;

var permisoEliminar;
var permisoActualizar;

var numRegConsulta;
var paginador;
var totalPaginas
var itemsPorPagina = 5;
var numerosPorPagina = 3;

function creaPaginador(totalItems,user,passwd,tipo_filtro,filtro,agencia,tipo_persona)
{
	paginador = $(".pagination");
	//$(".pagination").hide(); //Escondemos el paginador 
	numRegConsulta = totalItems;

	if(numRegConsulta  == 0 || numRegConsulta <= itemsPorPagina){ // Si no hay ningun resultado a la peticion o los registros obtenidos
																  // son menores a los items por pagina, no creamos el paginador
		cargaPagina(0,user,passwd,tipo_filtro,filtro,agencia,tipo_persona);
	}else{

		totalPaginas = Math.ceil(totalItems/itemsPorPagina);

		$('<li><a href="#" class="first_link">«</a></li>').appendTo(paginador);
		$('<li><a href="#" class="prev_link"><</a></li>').appendTo(paginador);

		var pag = 0;
		while(totalPaginas > pag)
		{
			$('<li><a href="#" class="page_link">'+(pag+1)+'</a></li>').appendTo(paginador);
			pag++;
		}

		if(numerosPorPagina > 1)
		{
			$(".page_link").hide();
			$(".page_link").slice(0,numerosPorPagina).show();
		}

		$('<li><a href="#" class="next_link">></a></li>').appendTo(paginador);
		$('<li><a href="#" class="last_link">»</a></li>').appendTo(paginador);

		paginador.find(".page_link:first").addClass("active");
		paginador.find(".page_link:first").parents("li").addClass("active");

		paginador.find(".prev_link").hide();

		paginador.find("li .page_link").click(function()
		{
			var irpagina =$(this).html().valueOf()-1;
			cargaPagina(irpagina,user,passwd,tipo_filtro,filtro,agencia,tipo_persona);
			return false;
		});

		paginador.find("li .first_link").click(function()
		{
			var irpagina =0;
			cargaPagina(irpagina,user,passwd,tipo_filtro,filtro,agencia,tipo_persona);
			return false;
		});

		paginador.find("li .prev_link").click(function()
		{
			var irpagina =parseInt(paginador.data("pag")) -1;
			cargaPagina(irpagina,user,passwd,tipo_filtro,filtro,agencia,tipo_persona);
			return false;
		});

		paginador.find("li .next_link").click(function()
		{
			var irpagina =parseInt(paginador.data("pag")) +1;
			cargaPagina(irpagina,user,passwd,tipo_filtro,filtro,agencia,tipo_persona);
			return false;
		});

		paginador.find("li .last_link").click(function()
		{
			var irpagina =totalPaginas -1;
			cargaPagina(irpagina,user,passwd,tipo_filtro,filtro,agencia,tipo_persona);
			return false;
		});

		cargaPagina(0,user,passwd,tipo_filtro,filtro,agencia,tipo_persona);
	}

}

function cargaPagina(pagina,user,passwd,tipo_filtro,filtro,agencia,tipo_persona){

	var desde = pagina * itemsPorPagina;

	var url;

	if(tipo_persona == 1){
		url = 'http://apirest/propietarios_paginado_filtro/';
	}else if(tipo_persona == 2){
		url = 'http://apirest/clientes_paginado_filtro/';
	}else if(tipo_persona == 3){
		url = 'http://apirest/usuarios_paginado_filtro/';
	}

	$.ajax({
		data: {user: user, 
			   pass: passwd,
			   agencia: agencia,
			   limit: itemsPorPagina,
			   offset: desde,
			   tipo_filtro: tipo_filtro,
			   filtro: filtro},
		type:"POST",
		dataType:"json",
		url:  url
	}).done(function(data,textStatus,jqXHR){

		var authOk;

		if(data.message == "Autentication Failure"){
			authOk = 0;		
		}else{
			authOk = 1;
		}

		if(authOk){

			$("#miTabla").html("");

			$.each(data, function(ind, elem){

				addRowTable(elem,tipo_persona);

			});

			//Si el numero de registros devueltos en la consulta es mayor al numero de items por pagina mostramos el paginador
			/*if(numRegConsulta > itemsPorPagina){
				$(".pagination").show();
			}*/

		}else{
			//$(".pagination").hide();
			var tabla = "propietarios";
			if(tipo_persona == 2){
				tabla = "clientes";
			}else if(tipo_persona == 3){
				tabla = "usuarios";
			} 

			mostrarMensaje("Usted no tiene permisos para listar "+tabla,"../../index.php");
			
		}	

	}).fail(function(jqXHR,textStatus,textError){
		mostrarMensaje("Error al realizar la peticion ".textError,"../../index.php");

	});
			
	if(numRegConsulta > itemsPorPagina){ // Si no hay ningun resultado a la peticion o los registros obtenidos
										 // son menores a los items por pagina, no creamos el paginador
		if(pagina >= 1)
		{
			paginador.find(".prev_link").show();

		}
		else
		{
			paginador.find(".prev_link").hide();
		}


		if(pagina <(totalPaginas- numerosPorPagina))
		{
			paginador.find(".next_link").show();
		}else
		{
			paginador.find(".next_link").hide();
		}

		paginador.data("pag",pagina);

		if(numerosPorPagina>1)
		{
			$(".page_link").hide();
			if(pagina < (totalPaginas- numerosPorPagina))
			{
				$(".page_link").slice(pagina,numerosPorPagina + pagina).show();
			}
			else{
				if(totalPaginas > numerosPorPagina)
					$(".page_link").slice(totalPaginas- numerosPorPagina).show();
				else
					$(".page_link").slice(0).show();

			}
		}

		paginador.children().removeClass("active");
		paginador.children().eq(pagina+2).addClass("active");

	}

}

//Funcion que crea el paginador y las filas de la tabla que corresponda
function main(user,passwd,tipo_filtro,filtro,agencia,tipo_persona){
			
		if(tipo_persona == 1){
			permisoExiste('propietarios/actualizar',user,1);
			permisoExiste('propietarios/eliminar',user,2);
		}else if(tipo_persona == 2){
			permisoExiste('clientes/actualizar',user,1);
			permisoExiste('clientes/eliminar',user,2);
		}else if(tipo_persona == 3){
			permisoExiste('usuarios/actualizar',user,1);
			permisoExiste('usuarios/eliminar',user,2);
		}

	var url;  // url que pedira el numero de filas que tendra la tabla a mostrar

	if(tipo_persona == 1){
		url = 'http://apirest/num_propietarios_filtro/';
	}else if(tipo_persona == 2){
		url = 'http://apirest/num_clientes_filtro/';
	}else{
		url = 'http://apirest/num_usuarios_filtro/';
	}

	usuario     = user;
	pasword     = passwd;
	nom_agencia = agencia;

	$.ajax({
		data: {tipo_filtro: tipo_filtro, 
			   filtro: filtro,agencia: agencia},
		type:"POST",
		dataType:"json",
		url:  url
		}).done(function(data,textStatus,jqXHR){

			var total = data.total;

			creaPaginador(total,user,passwd,tipo_filtro,filtro,agencia,tipo_persona);


		}).fail(function(jqXHR,textStatus,textError){
			alert("Error al realizar la peticion ".textError);

	});

}

//Funcion que se encarga de eliminar
function eliminar(id,nombre,apell1,apell2,tipo_persona){
	
	//Ponemos el mensaje que queremos que nos muestre el dialogo oculto que se mostrara cuando presionemos el boton eliminar
	$("#mensaje").text("¿ Seguro de quieres eliminar a "+nombre+" "+apell1+" "+apell2+" ?");

	// Ventana de dialogo que nos pide que le confirmemos que queremos borrar el registro seleccionado
	$( "#dialog" ).dialog({
        modal: true,
        buttons: {
            "Sí": function() {
                $( this ).dialog( "close" );

	                $.ajax({
					    data: {user: usuario, 
					   		   pass: pasword,
					   		   agencia: nom_agencia,
					   		   id: id},
						url: "eliminar.php",
						type: 'POST',
						dataType:"json",
						success: function(data, status, jqXHR) {

							if(data.message == "Autentication Failure"){
								if(tipo_persona == 1){
									mostrarMensajeEliminar("No tienes permisos para eliminar propietarios o el propietario que intentas eliminar no es de tu agencia");
								}else if(tipo_persona == 2){
									mostrarMensajeEliminar("No tienes permisos para eliminar clientes o el cliente que intentas eliminar no es de tu agencia");
								}else{
									mostrarMensajeEliminar("No tienes permisos para eliminar usuarios o el usuario que intentas eliminar no es de tu agencia");
								}
								
					 		}else if(data.message == "Operation Succesful"){
					 			//$("#"+id_prop).remove();
					 			location.reload(true); // recargamos la pagina
					 			//alert("El propietario ha sido eliminado correctamente");
					 		}else{
					 			mostrarMensajeEliminar(data.message);
					 		}

						},
						error: function (jqXHR, status) {
							mostrarMensajeEliminar("Error con la petición al Servicio Web");
						}
					});
                    
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
                //Si cancela la acción no hacemos nada.
            }
        }
    });

}

// Funcion que se encarga de contruir las filas de la tabla
function addRowTable(elem,tipo_persona){

	var actualizar;
	var eliminar;
	var color_actualizar = "color:#428BCA;";
	var color_eliminar   = "btn-default";

	if(  permisoEliminar == 0)  color_actualizar = "color:#d9534f;";
	if(permisoActualizar == 0)  color_eliminar   = "btn-danger";

	if(tipo_persona == 1){
		actualizar = "<div class=\"actualizar\"><label><a style=\""+color_actualizar+"\" class=\"actualizar\" href=\"actualizar.php?id="+elem.id_propietario+"&id_agencia="+elem.id_agencia+"&dni="+elem.dni+"&nombre="+elem.nombre+"&apell1="+elem.primer_apellido+"&apell2="+elem.segundo_apellido+"&direccion="+elem.direccion+"&localidad="+elem.localidad+"&provincia="+elem.provincia+"&telefono="+elem.telefono+"&email="+elem.email+"\">actualizar</a></label></div>";
		eliminar = "<div class=\"eliminar\"><button type=\"button\" class=\"btn "+color_eliminar+"\" onclick=\"eliminar("+elem.id_propietario+",'"+elem.nombre+"','"+elem.primer_apellido+"','"+elem.segundo_apellido+"',"+tipo_persona+");\"><span class=\"glyphicon glyphicon-trash\"></span></button></div>";
	}else if(tipo_persona == 2){
		actualizar = "<div class=\"actualizar\"><label><a style=\""+color_actualizar+"\" class=\"actualizar\" href=\"actualizar.php?id="+elem.id_cliente+"&id_agencia="+elem.id_agencia+"&dni="+elem.dni+"&nombre="+elem.nombre+"&apell1="+elem.primer_apellido+"&apell2="+elem.segundo_apellido+"&direccion="+elem.direccion+"&localidad="+elem.localidad+"&provincia="+elem.provincia+"&telefono="+elem.telefono+"&email="+elem.email+"\">actualizar</a></label></div>";
		eliminar = "<div class=\"eliminar\"><button type=\"button\" class=\"btn "+color_eliminar+"\" onclick=\"eliminar("+elem.id_cliente+",'"+elem.nombre+"','"+elem.primer_apellido+"','"+elem.segundo_apellido+"',"+tipo_persona+");\"><span class=\"glyphicon glyphicon-trash\"></span></button></div>";
	}else{
		actualizar = "<div class=\"actualizar\"><label><a style=\""+color_actualizar+"\" class=\"actualizar\" href=\"actualizar.php?id="+elem.id_usuario+"&id_agencia="+elem.id_agencia+"&usuario="+elem.usuario+"&dni="+elem.dni+"&nombre="+elem.nombre+"&apell1="+elem.primer_apellido+"&apell2="+elem.segundo_apellido+"&direccion="+elem.direccion+"&localidad="+elem.localidad+"&provincia="+elem.provincia+"&telefono="+elem.telefono+"&email="+elem.email+"\">actualizar</a></label></div>";
		eliminar = "<div class=\"eliminar\"><button type=\"button\" class=\"btn "+color_eliminar+"\" onclick=\"eliminar("+elem.id_usuario+",'"+elem.nombre+"','"+elem.primer_apellido+"','"+elem.segundo_apellido+"',"+tipo_persona+");\"><span class=\"glyphicon glyphicon-trash\"></span></button></div>";
	}

	var color_fila;
	var id_agencia;

	if(nom_agencia == "Agencia_A"){
		id_agencia = 1;
	}else{
		id_agencia = 2;
	}

	/* Si nos hemos logeado en modo agencia, coloreamos en verde las filas que sean de la agencia, 
       si en cambio lo hemos hecho en modo federacion, coloreamos en verde todas las filas. */
	if(elem.id_agencia == id_agencia || nom_agencia == "Federacion"){
		color_fila = "class = \"success\" ";
	}else{
		color_fila = "";
	}
	
	if(tipo_persona == 1){ // PROPIETARIOS
		$("<tr "+color_fila+" id="+elem.id_propietario+">"+
		 "<td>"+elem.id_propietario+"</td>"+
		 "<td>"+elem.id_agencia+"</td>"+
		 "<td>"+elem.dni+"</td>"+
		 "<td>"+elem.nombre+"</td>"+
		 "<td>"+elem.primer_apellido+"</td>"+
		 "<td>"+elem.segundo_apellido+"</td>"+
		 "<td>"+elem.direccion+"</td>"+
		 "<td>"+elem.localidad+"</td>"+
		 "<td>"+elem.provincia+"</td>"+
		 "<td>"+elem.telefono+"</td>"+
		 "<td>"+elem.email+"</td>"+
		 "<td>"+actualizar+"</td>"+
		 "<td>"+eliminar+"</td>"+
	  "</tr>").appendTo($("#miTabla"));
	}else if(tipo_persona == 2){ // CLIENTES
		$("<tr "+color_fila+" id="+elem.id_cliente+">"+
		 "<td>"+elem.id_cliente+"</td>"+
		 "<td>"+elem.id_agencia+"</td>"+
		 "<td>"+elem.dni+"</td>"+
		 "<td>"+elem.nombre+"</td>"+
		 "<td>"+elem.primer_apellido+"</td>"+
		 "<td>"+elem.segundo_apellido+"</td>"+
		 "<td>"+elem.direccion+"</td>"+
		 "<td>"+elem.localidad+"</td>"+
		 "<td>"+elem.provincia+"</td>"+
		 "<td>"+elem.telefono+"</td>"+
		 "<td>"+elem.email+"</td>"+
		 "<td>"+actualizar+"</td>"+
		 "<td>"+eliminar+"</td>"+
	  "</tr>").appendTo($("#miTabla"));
	}else{ //USUARIOS
		$("<tr "+color_fila+"id="+elem.id_usuario+">"+
		 "<td>"+elem.id_usuario+"</td>"+
		 "<td>"+elem.id_agencia+"</td>"+
		 "<td>"+elem.usuario+"</td>"+
		 "<td>"+elem.dni+"</td>"+
		 "<td>"+elem.nombre+"</td>"+
		 "<td>"+elem.primer_apellido+"</td>"+
		 "<td>"+elem.segundo_apellido+"</td>"+
		 "<td>"+elem.localidad+"</td>"+
		 "<td>"+elem.provincia+"</td>"+
		 "<td>"+elem.telefono+"</td>"+
		 "<td>"+elem.email+"</td>"+
		 "<td>"+actualizar+"</td>"+
		 "<td>"+eliminar+"</td>"+
	  "</tr>").appendTo($("#miTabla"));
	}
	
}

// Funcion que hace una peticion a un servicio web, que devuelve el resultado de si un usuario tiene o no un permiso determinado
function permisoExiste(servicio,user,tipo){

	var url = 'http://apirest/permisos/existe/'+user;

	$.ajax({
		data: {servicio: servicio},
		type:"POST",
		dataType:"json",
		url:  url
		}).done(function(data,textStatus,jqXHR){
			if(tipo == 1){
				permisoEliminar = data.existe;
			}else{
				permisoActualizar = data.existe;
			}
		}).fail(function(jqXHR,textStatus,textError){
			alert("Error al realizar la peticion ".textError);
		});

}