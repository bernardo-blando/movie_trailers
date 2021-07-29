<!-- Navigation -->
<?php

if (isset($_GET["feed"])) {
    $go = true;
} else {
    $go = false;
}
?>
<style>
    .dropdown-item:hover {
        background-color: black;
        color: #f8f9fa;
    }

    .dropdown-item {
        color: #f8f9fa;
    }
</style>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand text-warning" href="home.php"><span style="color: gray !important;">hey</span>DEPT</a>
        <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
            aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button> -->
        <!-- <div class="collapse navbar-collapse" id="navbarResponsive"> -->
        <div>
            <ul class="navbar-nav ml-auto">
                <!-- <li class="nav-item">
                    <a class="nav-link" href="home.php">Home
                        <span class="sr-only"></span>
                    </a>
                </li> -->
                <!-- <li class="nav-item">
                    <a class="nav-link" href="login.php">LogIn</a>
                </li> -->
                <li class="nav-item mt-1 ml-2">
                    <form name="search" method="get" action="search.php">
                        <input style="
                        @media only screen and (max-width: 400px){
 max-width: 200px;
}
                        
                        " type="text" name="query" placeholder="Search.." id="query">
                        <button type="submit" class="btn-warning"><i class="fa fa-search" style="width: 21px; height: 21px;"></i></button>
                    </form>
                </li>



            </ul>
        </div>
    </div>
</nav>
<div class=" modal fade" id="mudar_nome" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title " id="exampleModalLabel">Mudar de Username</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="fecha">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <form method="post" action="scripts/sc_mudar_nome.php">

                    <p><input type="text" name="username" placeholder="Novo Username"></p>
                    <p><input type="password" name="password" placeholder="password"></p>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="submit btn btn-warning">Submeter</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
if (isset($_GET["feed"])) {
    $msg_show = true;
    switch ($_GET["feed"]) {
        case 0:
            $message = "Ocorreu um erro! ";
            $class = "alert-warning";
            break;
        case 1:
            $message = "Password Incorreta!";
            $class = "alert-warning";
            break;
        case 2:
            $message = "O username já está a ser utilizado!";
            $class = "alert-warning";
            break;
        case 3:
            $message = "Alteração efectuada com sucesso!";
            $class = "alert-success";
            break;
        case 7:
            $message = "Já tinhas avaliado este Filme. Seu malandro.. andas a contornar o meu código. Pensavas que eu não estava à espera. PFFF..";

        default:
            $msg_show = false;
    }

    if ($msg_show) {
        echo "
<div class=\"alert $class alert-dismissible fade show\" role=\"alert\">" . $message . "
<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
    <span aria-hidden=\"true\">&times;</span>
</button>
</div>
";
    }
}
