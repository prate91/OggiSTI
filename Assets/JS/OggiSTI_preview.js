$(document).ready(function () {
    var eventId = getUrlParameter('eventId');
    var preview = getUrlParameter('preview');
    var stateId = getUrlParameter('stateId');

    if (eventId) {
        var url = "Assets/Api/Select/extractEvent.php";
        //chiamata AJAX
        $.getJSON(url, { "eventId": eventId, "stateId": stateId }, function (result) {
            $.each(result, function (index, item) {
                modificaInfoEvento(item.Date, item.ItaTitle, item.ItaAbstract, item.ItaDescription, item.TextReferences, item.Editors, item.Reviser_1, item.Reviser_2, item.ImageCaption, item.Image);
                $("#oggiSTI_sopraTitolo").css("visibility", "hidden");
                $("#oggiSTI_giornoDiverso").html("");
                $("#oggiSTI_meseDiverso").html("");

            });
        });
    }

});