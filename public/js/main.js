


let view = $('#executionView')
function loading(){
    view.empty()
    view.append(
        '<h3>Exécution en cours ... </h3>' +
        '<div class="spinner-border text-info" role="status">' +
        '  <span class="visually-hidden">Loading...</span>' +
        '</div>'
    )
}

$(document).ready(function(){

    $('#buttonInsert').click(function(e) {
        e.preventDefault();
        loading();
        $.ajax({
            type:'POST',
            url: "../php/mainController.php",
            data:{
                ajaxPost : 'Insert'
            },
            success: function (data) {
                console.log(data)
                data = JSON.parse(data)

                view.empty()
                view.append(
                    "<h3>Fichier csv importé en base de données avec succès !</h3><br>"
                )
                if (data != null && data !== ''){
                    view.append(
                        "<p>Anomalie détectée dans le fichier cvs </p>"
                    )
                }
                for (let i=0; i<=data.length-1;i++){
                    view.append(
                        "<p>"+data[i]+"</p>"
                    )
                }
            },
            error: function (){
                alert("Erreur lors de l'insertion des données")
            }
        })
    });

    $('#buttonDelete').click(function(e) {
        e.preventDefault();
        loading();
        $.ajax({
            type:'POST',
            url: "../php/mainController.php",
            data:{
                ajaxPost : 'Delete'
            },
            success: function (data) {
                view.empty()
                view.append(
                    "<p>Base de données vidé avec succès !</p>"
                )
            },
            error: function (){
                alert("Erreur lors de la suppression des données")
            }
        })
    });

    $('#buttonCallAfterDate').click(function(e) {
        e.preventDefault();
        loading();
        $.ajax({
            type:'POST',
            url: "../php/mainController.php",
            data:{
                ajaxPost : 'CallAfterDate'
            },
            success: function (data) {
                data =JSON.parse(data)
                if (data['hours'] === 0 && data['minutes'] === 0 && data['seconds'] === 0){
                    view.empty()
                    view.append(
                        "<p>Aucune données, base de données certainement vide.</p>"
                    )
                }else{
                    view.empty()
                    view.append(
                        "<p>durée totale réelle des appels effectués après le 15/02/2012 (inclus): "+data['hours']+" Heures ,"+data['minutes']+" Minutes ,"+data['seconds']+" Secondes"+"</p>"
                    )
                }
            },
            error: function (){
                alert("Erreur lors de l'exécution")
            }
        })
    });

    $('#buttonDataBilledBetweenTwoHours').click(function(e) {
        e.preventDefault();
        loading();
        $.ajax({
            type:'POST',
            url: "../php/mainController.php",
            data:{
                ajaxPost : 'DataBilledBetweenTwoHours'
            },
            success: function (data) {
                data =JSON.parse(data)
                console.log(data)
                if (data.length > 0){
                    view.empty()
                    view.append(
                        "<div class='card'>" +
                        "  <table class=\"table table-striped table-light table-bordered\">\n" +
                        "    <thead>\n" +
                        "      <tr>\n" +
                        "        <th scope=\"col\">#</th>\n" +
                        "        <th scope=\"col\">N° Abonné</th>\n" +
                        "        <th scope=\"col\">Compte facturé</th>\n" +
                        "        <th scope=\"col\">Date</th>\n" +
                        "        <th scope=\"col\">Heure</th>\n" +
                        "        <th scope=\"col\">Volume facturé</th>\n" +
                        "      </tr>\n" +
                        "    </thead>\n" +
                        "    <tbody id='TableContent'>\n" +
                        "    </tbody>\n" +
                        "  </table>" +
                        "</div>"
                    )
                    for (let i=0; i<=data.length-1;i++){
                        $('#TableContent').append(
                            "<tr>\n" +
                            "   <th scope=\"row\">"+(i+1)+"</th>\n" +
                            "   <td>"+data[i]['num_abonne']+"</td>\n" +
                            "   <td>"+data[i]['compte_facture']+"</td>\n" +
                            "   <td>"+data[i]['date']+"</td>\n" +
                            "   <td>"+data[i]['heure']+"</td>\n" +
                            "   <td>"+data[i]['volume_facture']+"</td>\n" +
                            "</tr>"
                        )
                    }
                }else {
                    view.empty()
                    view.append(
                        "<p>Aucune données, base de données certainement vide.</p>"
                    )
                }

            },
            error: function (){
                alert("Erreur lors de l'exécution")
            }
        })
    });


    $('#buttonDataBilledBetweenTwoHoursBySubscribe').click(function(e) {
        let subNum = $('#inputSub').val()
        e.preventDefault();
        loading();
        $.ajax({
            type:'POST',
            url: "../php/mainController.php",
            data:{
                ajaxPost : 'DataBilledBetweenTwoHoursBySubscribe',
                subscribe : subNum
            },
            success: function (data) {
                data =JSON.parse(data)
                console.log(data)
                if (data.length > 0){
                    view.empty()
                    view.append(
                        "<div class='card'>" +
                        "  <table class=\"table table-striped table-light table-bordered\">\n" +
                        "    <thead>\n" +
                        "      <tr>\n" +
                        "        <th scope=\"col\">#</th>\n" +
                        "        <th scope=\"col\">N° Abonné</th>\n" +
                        "        <th scope=\"col\">Compte facturé</th>\n" +
                        "        <th scope=\"col\">Date</th>\n" +
                        "        <th scope=\"col\">Heure</th>\n" +
                        "        <th scope=\"col\">Volume facturé</th>\n" +
                        "      </tr>\n" +
                        "    </thead>\n" +
                        "    <tbody id='TableContent'>\n" +
                        "    </tbody>\n" +
                        "  </table>" +
                        "</div>"
                    )
                    for (let i=0; i<=data.length-1;i++){
                        $('#TableContent').append(
                            "<tr>\n" +
                            "   <th scope=\"row\">"+(i+1)+"</th>\n" +
                            "   <td>"+data[i]['num_abonne']+"</td>\n" +
                            "   <td>"+data[i]['compte_facture']+"</td>\n" +
                            "   <td>"+data[i]['date']+"</td>\n" +
                            "   <td>"+data[i]['heure']+"</td>\n" +
                            "   <td>"+data[i]['volume_facture']+"</td>\n" +
                            "</tr>"
                        )
                    }
                }else {
                    view.empty()
                    view.append(
                        "<p>Aucune données, base de données certainement vide ou numéro abonné incorrect.</p>"
                    )
                }

            },
            error: function (){
                alert("Erreur lors de l'execution")
            }
        })
    });

    $('#buttonTotalSms').click(function(e) {
        e.preventDefault();
        loading();
        $.ajax({
            type:'POST',
            url: "../php/mainController.php",
            data:{
                ajaxPost : 'TotalSms'
            },
            success: function (data) {
                data = JSON.parse(data)
                console.log(data)
                if (data>0){
                    view.empty()
                    view.append(
                        "<p>Quantité totale de SMS envoyés par l'ensemble des abonnés: "+data+" sms </p>"
                    )
                }else {
                    view.empty()
                    view.append(
                        "<p>Aucune données, base de données certainement vide.</p>"
                    )
                }

            },
            error: function (){
                alert("Erreur pendant l'execution")
            }
        })
    });

});

