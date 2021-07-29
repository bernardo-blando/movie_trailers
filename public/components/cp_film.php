<?php

if (isset($_GET["id"])) {
    $id_active = $_GET["id"];
} else {
    $id_active = 0;
}

if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 0;
}

if (isset($_GET["id"])) {

    $id = htmlspecialchars($_GET["id"]);
    $movie = callMyApi("http://18.219.204.151/Dept/index.php/movies/" . $id);
}
#CONTENT----------------------------------------------------------CAPA----------------------------------------------------------------------------------------
?>

<div class="container">

    <div class="row">
        <div class="col-lg-3  mt-4">
            <img src="<?= $movie->data->poster ?>" style="width: 100%;">
            <hr style=" border: 1px solid grey">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Stars</h5>
                    <?php
                    #----------------------------------------------------------------ELENCCO------------------------------------------------------------------

                    foreach ($movie->data->actorList as $item) {

                        echo '    <p><span class="m-1"><img src="' . $item->image . '" style="width:50px;"></span>' .
                            $item->name . '
                    </p>';
                    }


                    echo '
                </div>
            </div>
        </div> 
        <div class="col-lg-9">
            <div class="card my-4">

                <iframe style="height:28rem; width:100%;" src="https://www.youtube.com/embed/' . $movie->data->youtubeTrailer->videoId . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <div class="card-body">
                    <h3 class="card-title">' . $movie->data->title . '(' . $movie->data->year . ')</h3>
                            <br><span style="font-size: 2vh; font-weight: normal">Directed by <a style="font-size: 2.2vh"
                                href="participant.php?id="">' . $movie->data->directors . '</a></span>
                                    <h5>' . $movie->data->genres . '</h5>
                                    <p class=" card-text">' . $movie->data->plot . '</p>
                                <span id="estrelas" class="text-warning my-auto">'; //ESTRELAS CHEIAS OU VAZIAS


                    for ($i = 1; $i <= 10; $i++) {
                        if ($i <= round($movie->data->imdbRating)) {
                            echo "&#9733";
                        } else {
                            echo "&#9734";
                        }
                    }
                    ?>
                    </span>
                    <span style="color:black;"> <?= round($movie->data->imdbRating, 1) ?> stars</span>
                </div>
            </div>

            <div class="card my-4">
                <div class="card-body">
                    <h4 class="card-title">Actor Interviews</h4>
                    <div class="container">
                        <div class="row">
                            <?php

                            foreach ($movie->data->interviewVideos as $item) {

                            ?>
                                <figure class="col-sm-4">
                                    <a href="<?= $item->url ?>" target="_blank"><img style="max-width: 230px; height:auto;" src="<?= $item->thumbnails->medium ?>"></a>
                                    <figcaption>
                                        <?= $item->title ?>
                                    </figcaption>
                                </figure>

                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="row my-4">
        <div class="col-sm-12">
            <div class="card my-4">
                <div class="card-body">
                    <h4 class="card-title">Similars to "<?= $movie->data->title ?>"</h4>
                    <div class="container">
                        <div class="row">
                            <?php
                            foreach ($movie->data->similarMovies as $item) {
                            ?>

                                <figure class="col-sm-2">
                                    <a href="film.php?id=<?= $item->id ?>"><img style=" max-width: 150px; height:auto;" src="<?= $item->poster ?>"></a>
                                    <figcaption>
                                        <p><strong><?= $item->title ?>
                                                <?php if (isset($item->year))
                                                    echo "(" . $item->year . ")"; ?></strong>

                                            <?php
                                            if (!empty($item->imdbRating)) {
                                                echo '<br>Rating: ' . $item->imdbRating .
                                                    '&#9733</p>';
                                            } ?>
                                    </figcaption>
                                </figure>

                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card -->
</div>
</div>
</div>






<style>
    i {
        width: 10px;
        color: black;
    }
</style>

<?php
#---------------------------------------------------------------MODAL 1-------------------------------------------------------------->
echo '
<div class="modal fade" id = "myModal" role = "dialog" >

    <div class="modal-dialog modal-sm" >
      <div class="modal-content" >
        <div class="modal-body m-auto" style = "text-align: center;" id = "rate" > ';

if (isset($_SESSION["username_bb"])) {
    echo '<style >
        .estrelinha{
                        color:#f0ad4e;
                    }  
</style >
          <span  class="text-warning" style = "font-size:4vh; " > <a id = "e1" class="estrelinha" href = "scripts/sc_rating.php?rate=1&id=' . $id_film_bd . '" >&#9734</a> <a id="e2" class="estrelinha" href="scripts/sc_rating.php?rate=2&id=' . $id_film_bd . '">&#9734</a> <a id="e3" class="estrelinha" href="scripts/sc_rating.php?rate=3&id=' . $id_film_bd . '">&#9734</a> <a id="e4" class="estrelinha" href="scripts/sc_rating.php?rate=4&id=' . $id_film_bd . '">&#9734</a> <a id="e5" class="estrelinha"  href="scripts/sc_rating.php?rate=5&id=' . $id_film_bd . '">&#9734</a> </span>';
} else {
    echo '<p>Faça Login para poder fazer avaliações. <a href="login.php">Clique Aqui</a></p>';
}
echo '    </div>
</div>      
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
        </div>
      </div>
    </div>
  </div>



<!---------------------------------------------------------------MODAL 2-------------------------------------------------------------->
<div class="modal fade" id = "myModal2" role = "dialog" >

    <div class="modal-dialog modal-sm" >
      <div class="modal-content" >
        <div class="modal-body m-auto" style = "text-align: center; font-size: 4vh; ">';


for ($i = 1; $i < 6; $i++) {
    if ($i <= round($rating_user)) {
        echo "<a class='text-warning estrelinhas'  href='scripts/sc_rating_update.php?rate=" . $i . "&id=" . $id_film_bd . "'>&#9733</a>";
    } else {
        echo "<a class='text-warning estrelinhas' href='scripts/sc_rating_update.php?rate=" . $i . "&id=" . $id_film_bd . "'>&#9734</a>";
    }
}
echo '
</div></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
        </div>
      </div>
    </div>
  </div>';
