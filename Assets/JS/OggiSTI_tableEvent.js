$(document).ready(function(){
    var eventId = getUrlParameter('eventId');
    var preview = getUrlParameter('preview');
    var stateId = getUrlParameter('stateId');

    if(eventId){
        var url = "../Api/extractEvent.php";
        //chiamata AJAX
        $.getJSON(url, {"eventId":eventId, "stateId":stateId}, function(result){
            $.each(result, function(index, item){
                intestazione_tabella += "<tr><td>Evento:</td><td id='idEvento'>"+ item.Id + "</td></tr>" +
        "<tr><td>Data:</td><td>"+ item.Date +"</td></tr>" +
        "<tr><td>Titolo:</td><td>"+ item.ItaTitle +"</td></tr>" +
        "<tr><td>Title:</td><td>"+ item.EngTitle + "</td></tr>" +
        "<tr><td>Immagine:</td><td><img id='oggiSTI_immagineEvento' src='../"+ item.Image +"' alt='Nessuna immagine'/></td></tr>" +
        "<tr><td>Link immagine:</td><td>"+ item.Image +"</td></tr>" +
        "<tr><td>Fonte immagine:</td><td>"+ item.ImageCaption +"</td></tr>" +
        "<tr><td>Link icona:</td><td>"+ item.Icon +"</td></tr>" +
        "<tr><td>Descrizione Breve:</td><td>"+ item.ItaAbstract +"</td></tr>" +
        "<tr><td>Brief description:</td><td>"+ item.EngAbstract +"</td></tr>" +
        "<tr><td>Descrizione:</td><td>"+ item.ItaDescription +"</td></tr>" +
        "<tr><td>Description:</td><td>"+ item.EngDescription +"</td></tr>" +
        "<tr><td>Riferimenti:</td><td>"+ item.TextReferences +"</td></tr>" +
        "<tr><td>Keywords:</td><td>"+ item.Keywords +"</td></tr>" +
        "<tr><td>Redattore:</td><td>"+ item.Editors +"</td></tr>" +
        "<tr><td>Verifica 1:</td><td>"+ item.Reviser_1 +"</td></tr>" +
        "<tr><td>Verifica 2:</td><td>"+ item.Reviser_2 +"</td></tr>" +
        "<tr><td>Stato:</td><td>"+ item.State +"</td></tr>" +
        "<tr><td>Salvato:</td><td>"+ item.Saved +"</td></tr>" +
        "<tr><td>Usato:</td><td>"+ item.usato +" volta/e</td></tr>" +
        "<tr class='rigaCommento'><td>Commento:</td><td>"+ item.Comment +"</td></tr>";
            $("#eventoAperto").html(intestazione_tabella);
                });
        });
    }    
});