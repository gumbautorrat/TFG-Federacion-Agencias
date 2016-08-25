var totalActual;
var totalInmuebles;
var paginador;
var totalPaginas
var itemsPorPagina = 2;
var numerosPorPagina = 3;

function creaPaginador(totalItems,tipo,agencia)
{

	paginador = $(".pagination");

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

	//Hasta aquí es lo que se hace la primera vez que entra en la página

	paginador.find("li .page_link").click(function()
	{
		//controlCargaPagina(tipo,agencia);
		var irpagina =$(this).html().valueOf()-1;
		cargaPagina(irpagina,tipo,agencia);
		return false;
	});

	paginador.find("li .first_link").click(function()
	{
		var irpagina =0;
		cargaPagina(irpagina,tipo,agencia);
		return false;
	});

	paginador.find("li .prev_link").click(function()
	{
		var irpagina =parseInt(paginador.data("pag")) -1;
		cargaPagina(irpagina,tipo,agencia);
		return false;
	});

	paginador.find("li .next_link").click(function()
	{
		var irpagina =parseInt(paginador.data("pag")) +1;
		cargaPagina(irpagina,tipo,agencia);
		return false;
	});

	paginador.find("li .last_link").click(function()
	{
		var irpagina =totalPaginas -1;
		cargaPagina(irpagina,tipo,agencia);
		return false;
	});

	cargaPagina(0,tipo,agencia);

}

function cargaPagina(pagina,tipo,agencia)
{
	//console.log('Tipo : '+tipo+' Agencia : '+agencia);
	var desde = pagina * itemsPorPagina;
	//var id_agencia = 1;

	$.ajax({
		data:{"limit":itemsPorPagina,"offset":desde,"tipo": tipo},
		type:"POST",
		dataType:"json",
		//url:  'http://apirest/inmuebles_paginado/' + id_agencia,
		url:  'http://apirest/inmuebles_paginado_tipo/' + agencia,
	}).done(function(data,textStatus,jqXHR){

	//Controlamos si el nº de inmuebles a mostrar ha cambiado, para actualizar la pagina y tener actualizado el paginador
	controlCargaPagina(tipo,agencia);

		var inmueble = "";

		$("#articulos").html("");

		if(data.length == 0){
			inmueble += "<div id=\"noresult\"><h1>No hay ningún resultado</h1></div>";
		}else{
			$.each(data, function(ind, elem){
			
				//Comentar para que se quite el color evaluacion
				//Si el inmueble es de la agencia
				if(elem.id_agencia == agencia){
					inmueble += "<article class=\"post clearfix articulos_agn2\">";
				}else{//Si el inmueble no es de la agencia (aparece porque es un inmueble compartido)
					inmueble += "<article class=\"post clearfix articulos_agn1\">";
				}

				//Descomentar para que se quede sin color evaluacion
				//inmueble += "<article class=\"post clearfix \">";
				
				inmueble += "<a class=\"thumb pull-left\">";
				inmueble += "<img class=\"img-thumbnail\" src=\""+elem.url+"\" alt=\"\">";
				inmueble += "</a>";
				inmueble += "<h2 class=\"post-title\">";
				inmueble += "<a href=\"vistas/detalle.php?tipo="+elem.id_tipo+"&id_inmueble="+elem.id_inmueble+" \">"+elem.descripcion+"</a>";
				inmueble += "</h2>";
				inmueble += "<p><span class=\"post-fecha\">Ofertada el <b>"+elem.fecha+"</b> en <b>"+elem.localidad+"</b>,<b>"+elem.provincia+"</b>.</span></p>";
				inmueble += "<p class=\"post-contenido text-justify\">"+truncarDescLarga(elem.descripcion_larga)+"</p>";

				inmueble += "<div class=\"miga-de-pan datos_piso\">";
				inmueble +=	"<ol class=\"breadcrumb\">";
				inmueble +=	"<li><b>"+elem.num_metros+"</b>m<sup>2</sup></li>";
				inmueble +=	"<li><b>"+elem.num_habitaciones+"</b> Hab.</li>";
				inmueble +=	"<li><b>"+elem.num_wc+"</b> Baños</li>";
			    inmueble += "</ol>";
			    inmueble += "</div>";

				inmueble += "<div class=\"contenedor-botones \">";
				inmueble += "<a style=\"CURSOR: default\" class=\"btn btn-success tipo\">"+elem.tipo+" <b>"+darFormato(elem.precio)+" €</b>"+"</a>";
				inmueble += "<a href=\"vistas/detalle.php?tipo="+elem.id_tipo+"&id_inmueble="+elem.id_inmueble+" \" class=\"btn btn-warning\">Leer Mas</a>";
				inmueble += "</div>";

				inmueble += "</article>";

			});
		}

		$(inmueble).appendTo($("#articulos"));

	}).fail(function(jqXHR,textStatus,textError){
		alert("Error al realizar la peticion obtenerInmuebles".textError);
	});


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

	//Cuando no hay inmuebles que mostrar quitamos el paginador
	/*if(totalInmuebles == 0){
		$(".pagination").remove();
	}*/


}

function main(agencia,tipo){
	
	$.ajax({
		data:{"tipo":tipo},
		type:"POST",
		dataType:"json",
		url:'http://apirest/num_inmuebles_tipo/' + agencia

		}).done(function(data,textStatus,jqXHR){
			totalInmuebles = data.total;
			totalActual = totalInmuebles;
			creaPaginador(totalInmuebles,tipo,agencia);
		}).fail(function(jqXHR,textStatus,textError){
			alert("Error al realizar la peticion numInmuebles".textError);
	});

}

// Funcion que formatea el precio poniendole un punto en los miles
function darFormato(precio) {

	var number = precio.toString();
	var result = '';

	while(number.length > 3 ){
	 result = '.' + number.substr(number.length - 3) + result;
	 number = number.substring(0, number.length - 3);
	}

	result = number + result;

	return result;
}

// Funcion que trunca la longitud de la descripción del inmueble
function truncarDescLarga(descripcion){
	var result = '';

	if(descripcion.length > 465){
		result = descripcion.substring(0,465) + "...";
	}else{
		result = descripcion;
	}
	
	return result;
}

function controlCargaPagina(tipo,agencia){

	$.ajax({
		data:{"tipo":tipo},
		type:"POST",
		dataType:"json",
		url:'http://apirest/num_inmuebles_tipo/' + agencia

		}).done(function(data,textStatus,jqXHR){
			totalActual = data.total;
			//Si el numero de inmuebles se ha actualizado, actualizamos la pagina cuando cambiemos
			if(totalInmuebles != totalActual){
				location.reload(true);
			}
			
		}).fail(function(jqXHR,textStatus,textError){
			alert("Error al realizar la peticion numInmuebles".textError);
		});

}