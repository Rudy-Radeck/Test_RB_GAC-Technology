
<!doctype html>
<html lang="fr" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="BODENON Rudy pour GAC-Technology">
    <title>GAC_Test</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
</head>


<body class="d-flex text-center text-white bg-dark">

    <div class="container d-flex flex-column ">

        <header class="mb-auto">
            <div>
                <h3 class="float-md-start mb-0">Test BODENON Rudy pour GAC-Technology</h3>
            </div>
        </header>

        <main class="mt-5">

            <div class="d-inline-flex flex-row align-items-center w-100 mx-auto my-2">
                <div class="col-8 d-flex flex-row align-items-center justify-content-end">
                    <span class="">Envoyer le fichier csv dans la base de données</span>
                    <button id="buttonInsert" class="btn btn-primary mx-1"> Cliquez Ici </button>
                </div>
                <div class="col-4 d-flex flex-row align-items-center justify-content-start">
                    <img id="CheckBddImg" src="img/novalide.png" class="img-fluid mx-2" alt="bddCheck">
                    <span id="CheckBddTxt"><!--Injected by AJAX--></span>
                </div>
            </div>

            <div class="mx-auto my-2">
                <span>Supprimer les données de la base de données</span>
                <button id="buttonDelete" class="btn btn-primary mx-auto"> Cliquez Ici </button>
            </div>


            <p>_______________________________________________________________</p>


            <div class="mx-auto my-2">
                <span>Retrouver la durée totale réelle des appels effectués après le 15/02/2012 (inclus)</span>
                <button id="buttonCallAfterDate" class="btn btn-primary mx-auto">Cliquez Ici</button>
            </div>
            <div class="mx-auto my-2">
                <span> Retrouver le TOP 10 des volumes data facturés en dehors de la tranche horaire 8h00-18h00</span>
                <button id="buttonDataBilledBetweenTwoHours" class="btn btn-primary mx-auto">Cliquez Ici</button>
            </div>
            <div class="mx-auto my-2">
                <span> Retrouver le TOP 10 des volumes data facturés en dehors de la tranche horaire 8h00-18h00, <strong>par abonné</strong></span>
                <button id="buttonDataBilledBetweenTwoHoursBySubscribe" class="btn btn-primary mx-auto">Cliquez Ici</button>
                <input type="number" id="inputSub" class="form-text" placeholder="N° abonné">
            </div>
            <div class="mx-auto my-2">
                <span> Retrouver la quantité totale de SMS envoyés par l'ensemble des abonnés</span>
                <button id="buttonTotalSms" class="btn btn-primary mx-auto">Cliquez Ici</button>
            </div>


            <p>_______________________________________________________________</p>



            <div class="mx-auto my-2" id="executionView">
                <!--Injected by AJAX-->
            </div>


        </main>

    </div>

    <!-- JQuery -->
    <script
            src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous">

    </script>
    <script type="text/javascript" src ="js/main.js"></script>

</body>
</html>
