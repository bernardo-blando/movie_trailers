<style>
body {
    margin-top: 20px;
    background: #eee;
}

.btn {
    margin-bottom: 5px;
}

.grid {
    position: relative;
    width: 100%;
    background: #fff;
    color: #666666;
    border-radius: 2px;
    margin-bottom: 25px;
    box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.1);
}

.grid .grid-body {
    padding: 15px 20px 15px 20px;
    font-size: 0.9em;
    line-height: 1.9em;
}

.search table tr td.rate {
    color: #f39c12;
    line-height: 50px;
}

.search table tr:hover {
    cursor: pointer;
}

.search table tr td.image {
    width: 80px;
}

.search table tr td img {
    width: 80px;
    height: auto;
}

.search table tr td.rate {
    color: #f39c12;
    line-height: 50px;
}

.search table tr td.price {
    font-size: 1.5em;
    line-height: 50px;
}

.search #price1,
.search #price2 {
    display: inline;
    font-weight: 600;
}
</style>


<div class="container">
    <div class="row">
        <!-- BEGIN SEARCH RESULT -->
        <div class="col-md-12">
            <div class="grid search">
                <div class="grid-body">
                    <div class="row">
                        <!-- BEGIN RESULT -->
                        <div class="col-md-12">
                            <h3><i class="fa fa-search"></i> Search for Movies</h3>
                            <hr>
                            <!-- BEGIN SEARCH INPUT -->

                            <form action="search.php" method="GET">
                                <input style="
                                    display: inline;
                                    width: 30%;
                                    @media only screen and (max-width: 400px){
                                        max-width: 200px;
                                    }" id="searchInput" name="query" type="text" class="form-control"
                                    placeholder="Search.."
                                    <?php if (isset($_GET["query"])) echo 'value="' . $_GET["query"] . '"'; ?>>

                                <button style="width: 35px; height: 35px;" class="ml-2 btn-warning" type="submit"><i
                                        class="fa fa-search"></i></button>

                            </form>

                            <!-- END SEARCH INPUT -->
                            <p id="searchFeedback">Showing all results matching
                                "<?php if (isset($_GET["query"])) echo  $_GET["query"]; ?>"</p>

                            <div class="padding"></div>



                            <?php
                            $input = htmlspecialchars($_GET["query"]);
                            $searchResults = callMyApi("http://18.219.204.151/Dept/index.php/movies/search/" . $input);

                            ?>

                            <!-- BEGIN TABLE RESULT -->
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>

                                        <?php
                                        if (isset($searchResults)) {

                                            foreach ($searchResults->data as $item)
                                                echo '
                                       <tr>
                                       <a href= "film.php?id=' . $item->id . '"><div>
                                            <td class="image"><a href="film.php?id=' . $item->id . '"><img src="' . $item->image . '"
                                                    alt=""></a></td>
                                            <td class="product"><a  href="film.php?id=' . $item->id . '"><strong>' . $item->title . '</strong><br>' . $item->year . '</a></td>
                                        </div>
                                        </a>  
                                        </tr> ';
                                        } else {
                                            echo "No results were found.";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- END TABLE RESULT -->
                        </div>
                        <!-- END RESULT -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END SEARCH RESULT -->
    </div>
</div>


<script>
//const axios = require('axios').default;
// document.getElementById('search').addEventListener('click', getSearchResults);

// function filmDetail(id) {
//     location.replace("http://18.219.204.151/film.php?id=" + id)
// }

// function getSearchResults() {
//     var searchString = document.getElementById("searchInput").value
//     console.log(searchString)
//     document.getElementById("searchFeedback").innerHTML = 'Showing all results matching \"' + searchString + '\"'


//     // axios.get('http://img.omdbapi.com/?apikey=7dd2131a&i=tt0111161')
//     //     .then(function(response) {
//     //         // handle success
//     //         console.log(response);
//     //     })
//     //     .catch(function(error) {
//     //         // handle error
//     //         console.log(error);
//     //     })
// }
</script>