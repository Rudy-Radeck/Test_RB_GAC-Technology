

// Récupération de la div où vont être injectées les informations
let view = $('#executionView')

// Fonction d'affichage du spinner de chargement
function loading(){
    view.empty()
    view.append(
        '<h3>Exécution en cours ... </h3>' +
        '<div class="spinner-border text-info" role="status">' +
        '  <span class="visually-hidden">Loading...</span>' +
        '</div>'
    )
}

// Fonction pour vérifier la base de données et voir si elle est vide ou non
function IsEmptyDataBase(){
    let checkBddImg = $("#CheckBddImg")
    let checkBddTxt = $("#CheckBddTxt")
    let btnInsert = $("#buttonInsert")

    $.ajax({
        type:'POST',
        url: "../php/mainController.php",
        data:{
            ajaxPost : 'CheckDatabase'
        },
        success: function (data) {
            data = JSON.parse(data)
            if (data > 0){
                checkBddImg.attr('src', 'img/valide.png')
                checkBddTxt.empty()
                checkBddTxt.append(data+' Entrée(s) prénsente(s) en BDD')
                btnInsert.attr('disabled', 'disabled')
            }else {
                checkBddImg.attr('src', 'img/novalide.png')
                checkBddTxt.empty()
                checkBddTxt.append('Aucune entrée présente en BDD')
                btnInsert.removeAttr('disabled')
            }
        },
        error: function (){
            alert("Erreur lors de l'exécution")
        }
    })
}

// Ajax pour l'envoi du csv en Bdd
$(document).ready(function(){
    IsEmptyDataBase()
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
                data = JSON.parse(data)
                if (data !== false && data !== ''){
                    view.empty()
                    view.append(
                        "<h3>Fichier csv importé en base de données avec succès !</h3><br>"
                    )
                    if (data !== false && data !== ''){
                        view.append(
                            "<p>Anomalie détectée dans le fichier cvs </p>"
                        )
                    }
                    for (let i=0; i<=data.length-1;i++){
                        view.append(
                            "<p>"+data[i]+"</p>"
                        )
                    }
                    IsEmptyDataBase()
                }else{
                    view.empty()
                    view.append(
                        "<h3>Une erreur s'est produite, vérifier votre ficher csv!</h3><br>"
                    )
                }
            },
            error: function (){
                alert("Erreur lors de l'insertion des données")
            }
        })
    });

    // Ajax pour la suppression des données en Bdd
    $('#buttonDelete').click(function(e) {
        e.preventDefault();
        loading();
        $.ajax({
            type:'POST',
            url: "../php/mainController.php",
            data:{
                ajaxPost : 'Delete'
            },
            success: function () {
                view.empty()
                view.append(
                    "<p>Base de données vidée avec succès !</p>"
                )
                IsEmptyDataBase()
            },
            error: function (){
                alert("Erreur lors de la suppression des données")
            }
        })
    });

    // Ajax pour retrouver la durée totale réelle des appels effectués après le 15/02/2012 (inclus)
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
                        "<p>Durée totale réelle des appels effectués après le 15/02/2012 (inclus) : "+data['hours']+" heures, "+data['minutes']+" minutes, "+data['seconds']+" secondes"+"</p>"
                    )
                }
            },
            error: function (){
                alert("Erreur lors de l'exécution")
            }
        })
    });

    // Ajax pour retrouver le TOP 10 des volumes data facturés en dehors de la tranche horaire 8h00-18h00
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

    // Ajax pour retrouver le TOP 10 des volumes data facturés en dehors de la tranche horaire 8h00-18h00, ==> par abonné <==.
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

    // Ajax pour retrouver la quantité totale de SMS envoyés par l'ensemble des abonnés
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
                if (data>0){
                    view.empty()
                    view.append(
                        "<p>Quantité totale de SMS envoyés par l'ensemble des abonnés : "+data+" sms </p>"
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

