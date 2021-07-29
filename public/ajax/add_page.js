
var pageTop = 2
document.getElementById("showTop").addEventListener("click", function() {
  

  axios.get(`https://18.219.204.151/Dept/index.php/movies/top?page=${pageTop}`)
    .then(function(response) {
      // handle success
      pageTop += 1
      let templateData = ``
      response.data.data.map(function(item) {
        
        templateData +=
        `
        <div class="col-lg-3 col-md-6 mb-4 >
            <div class = "card h-100" >
                <a href ="film.php?id=${item.id}">
                <img class="card-img-top"
                    style ="
                            @media only screen and(min - width: 1200 px) {
                              max - height: 355 px;
                            }
                            @media only screen and(max - width: 1200 px) {
                              max - height: 600 px;
                            }
                            " src="${item.poster}"> 
                </a> <div class = "card-body">

          <p class = "card-subtitle"
        style = "font-size=13px;" >
          <b>${item.title} (${item.year})</b> 
          </p>

          <p class = "card-text"
        style = "font-size: 13px"> Team: 
        <a href="participant.php?id=#"> ${item.crew}</a> 
        </p>
      </div>
  </div>
`
     
      })
     
      document.getElementById('topMovies').innerHTML += templateData 
      templateData = ""
      
    })
    .catch(function(error) {
      return error
    });

})

