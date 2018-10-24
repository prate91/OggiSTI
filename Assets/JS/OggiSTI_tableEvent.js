$(document).ready(function(){
    var id_evento = getUrlParameter('id_evento');
    var preview = getUrlParameter('preview');
    var id_state = getUrlParameter('id_state');

    if(id_evento){
        var url = "../Api/extractEvent.php";
        //chiamata AJAX
        $.getJSON(url, {"id_evento":id_evento, "id_state":id_state}, function(result){
            $.each(result, function(index, item){
                intestazione_tabella += "<tr><td>Evento:</td><td id='idEvento'>"+ item.id_evento + "</td></tr>" +
        "<tr><td>Data:</td><td>"+ item.data_evento +"</td></tr>" +
        "<tr><td>Titolo:</td><td>"+ item.titolo_ita +"</td></tr>" +
        "<tr><td>Title:</td><td>"+ item.titolo_eng + "</td></tr>" +
        "<tr><td>Immagine:</td><td><img id='oggiSTI_immagineEvento' src='../"+ item.immagine +"' alt='Nessuna immagine'/></td></tr>" +
        "<tr><td>Link immagine:</td><td>"+ item.immagine +"</td></tr>" +
        "<tr><td>Fonte immagine:</td><td>"+ item.fonteimmagine +"</td></tr>" +
        "<tr><td>Link icona:</td><td>"+ item.icona +"</td></tr>" +
        "<tr><td>Descrizione Breve:</td><td>"+ item.abstr_ita +"</td></tr>" +
        "<tr><td>Brief description:</td><td>"+ item.abstr_eng +"</td></tr>" +
        "<tr><td>Descrizione:</td><td>"+ item.desc_ita +"</td></tr>" +
        "<tr><td>Description:</td><td>"+ item.desc_eng +"</td></tr>" +
        "<tr><td>Riferimenti:</td><td>"+ item.riferimenti +"</td></tr>" +
        "<tr><td>Keywords:</td><td>"+ item.keywords +"</td></tr>" +
        "<tr><td>Redattore:</td><td>"+ item.redattore +"</td></tr>" +
        "<tr><td>Verifica 1:</td><td>"+ item.ver_1 +"</td></tr>" +
        "<tr><td>Verifica 2:</td><td>"+ item.ver_2 +"</td></tr>" +
        "<tr><td>Stato:</td><td>"+ item.stato +"</td></tr>" +
        "<tr><td>Salvato:</td><td>"+ item.commento +"</td></tr>" +
        "<tr><td>Usato:</td><td>"+ item.usato +" volta/e</td></tr>" +
        "<tr class='rigaCommento'><td>Commento:</td><td>"+ item.commento +"</td></tr>";
            $("#eventoAperto").html(intestazione_tabella);
                });
        });
    }    
});