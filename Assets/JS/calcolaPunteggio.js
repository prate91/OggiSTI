function calcolaOrdinaleGiorno(giorno, mese){
    switch (mese) {
        case 1:
            return ordinale=giorno-1;
        case 2:
            return ordinale=31+giorno-1;
        case 3:
            return ordinale=60+giorno-1;
        case 4:
            return ordinale=91+giorno-1;
        case 5:
            return ordinale=121+giorno-1;
        case 6:
            return ordinale=152+giorno-1;
        case 7:
            return ordinale=182+giorno-1;
        case 8:
            return ordinale=213+giorno-1;
        case 9:
            return ordinale=244+giorno-1;
        case 10:
            return ordinale=274+giorno-1;
        case 11:
            return ordinale=305+giorno-1;
        case 12:
            return ordinale=335+giorno-1;
    }
}

// Funzione che calcola la distanza dalla data più vicina inferiore
function cercaInferiore(giorniAnno, data){
        var i=1;
        while(i<10){
            if(data+1-i==0){
                data=data+366;
            }
            if(giorniAnno[data-i]==1){
                return i;
            }
        i=i+1;
           
        }
    return i;
}
// Funzione che calcola la distanza dalla data più vicina superiore
function cercaSuperiore(giorniAnno, data){
       i=1;
        while(i<10){
            if(data-1+i==365){
                data=data-366;
            }
            if(giorniAnno[data+i]==1){
                return i;
            }
        i=i+1;
           
        }
    return i;
}


$(document).ready(function(){
    
    var giorniAnno= new Array();
    for (var i = 0; i <= 365; i++) {
        giorniAnno[i]=0;
    }
    console.log(giorniAnno);
    var arrayDate = new Array();
    var url = "../asset/api/estraiDateEventi.php";
    //chiamata AJAX
    $.getJSON(url, function(result){
        $.each(result, function(index, item){
            g=parseInt(item.giorno);
            m=parseInt(item.mese);
            arrayDate[arrayDate.length]=calcolaOrdinaleGiorno(g, m);    
        });
    });
            
    console.log(arrayDate);
    var cazzo = arrayDate[136];
    console.log(cazzo);
    for (var x = 0; x <= 365; x++) {
        for (var j = 0; j < arrayDate.length; j++) {
            if(x==arrayDate[j]){
                giorniAnno[x]=1;
            }
        }
    }
    console.log(giorniAnno);
    
    
    
});