<!-- Page Content -->
<div class="container">

    <!-- Jumbotron Header -->
    <header class="jumbotron my-4" style=" background-image: url('images/panoramic.jpg');
    background-repeat: no-repeat;
    background-size: cover;

    -moz-background-size: cover;
    -webkit-background-size: cover;
    -o-background-size: cover;
    -ms-background-size: cover;
    background-position: center center;
color: white;
text-align: right">
        <h1 class="display-3">Welcome!</h1>
        <p class="lead">What's your next movie?</p>
    </header>

    <h1 class="font-weight-bold">
        Best Movies
        <hr style="border: 1px solid black">
    </h1>

    <?php

    $topMovies = callMyApi("http://18.219.204.151/Dept/index.php/movies/top?page=1");


    echo '<div id="topMovies" class="row text-center">';

    for ($i = 0; $i < 4; $i++) {
        echo '<div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100">
                <a href="film.php?id=' . $topMovies->data[$i]->id . '"><img class="card-img-top " 
                style="

                @media only screen and (min-width: 1200px){
 max-height: 355px;
}
     @media only screen and (max-width: 1200px){
 max-height: 600px;
}
                "
                 src="' .  $topMovies->data[$i]->poster . '"></a>
                <div class="card-body">

                    <p class="card-subtitle" style="font-size=13px;"><b>' . $topMovies->data[$i]->title . ' (' . $topMovies->data[$i]->year . ')</b></p>
                    
                    <p class="card-text" style="font-size: 13px"> Directed by: <a href="participant.php?id=#"> ' . $topMovies->data[$i]->crew . '</a></p>
                </div>
            </div>
        </div>';
    }
    echo '</div>
    <div style="text-align:center">            
    <button id="showTop" class="btn  btn-warning mb-3">Show More +</button>
    </div>
    ';
    echo '
    <h1 class="font-weight-bold">

        In Theaters
        <hr style="border: 1px solid black">
    </h1>';

    $inTheaters = callMyApi("http://18.219.204.151/Dept/index.php/movies/InTheaters?page=1");
    echo '<div id="moviesInTheaters" class="row text-center">';

    for ($i = 0; $i < 8; $i++) {
        if (isset($inTheaters->data[$i])){
        echo '
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100">
                    <a href="film.php?id=' . $inTheaters->data[$i]->id . '"><img class="card-img-top " style="
                    
@media only screen and (min-width: 1200px){
 max-height: 355px;
}
     @media only screen and (max-width: 1200px){
 max-height: 600px;
}" src="' .  $inTheaters->data[$i]->poster . '"></a>


                    <div class="card-body">

                        <p class="card-subtitle" style="font-size=13px;"><b>' . $inTheaters->data[$i]->title . ' (' . $inTheaters->data[$i]->year . ')</b></p>
                        <p class="card-text" style="font-size: 14px">' . $inTheaters->data[$i]->plot . '</p>
                        <p class="card-text" style="font-size: 13px"> Directed by: <a href="participant.php?id=#"> ' . $inTheaters->data[$i]->directors . '</a></p>
                    </div>
                </div>
            </div>';
    }else{
        break;
    }}

    echo '
        </div>
       
    </div>'; ?>