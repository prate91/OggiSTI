$(document).ready(function(){
    var id_evento = getUrlParameter('id_evento');
    var preview = getUrlParameter('preview');
    var id_state = getUrlParameter('id_state');

    if(id_evento){
        var url = "Assets/Api/extractEvent.php";
        //chiamata AJAX
        $.getJSON(url, {"id_evento":id_evento, "id_state":id_state}, function(result){
            $.each(result, function(index, item){
                modificaInfoEvento(item.data_evento, item.titolo_ita, item.abstr_ita, item.desc_ita, item.riferimenti, item.redattore, item.ver_1, item.ver_2, item.fonteimmagine, item.immagine);
                $("#oggiSTI_sopraTitolo").css("visibility", "hidden");
                $("#oggiSTI_giornoDiverso").html("");
                $("#oggiSTI_meseDiverso").html("");
               
                });
        });
    }    
			
});