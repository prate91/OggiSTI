var intestazione_tabella="<thead><tr><th>id</th><th>Titolo</th><th>Data</th><th>Stato</th></tr></thead>";	

$(document).ready(function(){
		intestazione_tabella="<thead><tr><th>id</th><th>Titolo</th><th>Data</th><th>Stato</th></tr></thead>";	
		var url = "asset/api/estraiListaEventi.php";
  			//chiamata AJAX
  			$.getJSON(url, function(result){
  					$.each(result, function(index, item){
  							var riga="<tr><td>"+item.id_evento+"</td>"+
  							"<td>"+item.titolo_ita+"</td>"+
  							"<td>"+item.data_evento+"</td>"+
  							"<td>"+item.stato+"</td>"+
							"<td><button type='button' id='button"+item.id_evento+"' class='btn btn-default'>Modifica</button></td></tr>";
  							intestazione_tabella+=riga;
  							$("#listaEventi").html(intestazione_tabella);
  					});
  			});
  			$("#applica").click(function() {
  			var controlla = $(this).html();
  			if (controlla=="Applica"){
  				$(this).html("Modifica");
  				$(".form-control").prop("disabled", true);
  				$("#invia").removeClass("hide");
  			}
  			if (controlla=="Modifica"){
  				$(this).html("Applica");
  				$(".form-control").prop("disabled", false);
  				$("#invia").addClass("hide");
  			}
  			
  		
  			  });
			  $( "table" ).on( "click", ".btn.btn-default", function() {
				$( this ).after( "<p>Another paragraph! " + (++count) + "</p>" );
			});
			/*$(".btn.btn-default").click(function() {
				$("#listaEventi").hide();
			});*/
}); 


