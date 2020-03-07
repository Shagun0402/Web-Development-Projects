function initialize () 
{

}
var json;
function sendRequest () 
{
   var xhr = new XMLHttpRequest();
   var query = encodeURI(document.getElementById("form-input").value);
   xhr.open("GET", "proxy.php?method=/3/search/movie&query=" + query);
   xhr.setRequestHeader("Accept","application/json");
   xhr.onreadystatechange = function () 
   {
       if (this.readyState == 4) 
       {
          var json = JSON.parse(this.responseText);
          var A = json.results;
          var content ="";
          content = "<table>";
          for (var i=0; i<A.length; i++)
          {
            content = content + "<tr>";
            content = content + "<td>" + "&nbsp&nbsp&nbsp" + "</td>";
            var value = json.results[i].id;
            // displays first 20 URL for the movie searched
            content = content + "<td>" +"<a href=\"#\" data-id=\"" + value +"\" onclick=\"loadMovieData(this.getAttribute('data-id'));\">"+ json.results[i].original_title + "</a>" + "</td>";
            content = content + "<td>" + "&nbsp&nbsp&nbsp&nbsp&nbsp" + "</td>";
            // displays date of release for the searched movie
            content = content + "<td>" + json.results[i].release_date + "</td>";
            content = content + "</tr>";
          }
          
          content = content + "</table>";
          document.getElementById("tablespace").innerHTML = content;
          
       }
   };
   xhr.send(null);
}

// function to print movie data like Movie Title, Movie Poster, Movie Summary
function loadMovieData(value)
{
   var counter = value;
   var xhr = new XMLHttpRequest();
   xhr.open("GET", "proxy.php?method=/3/movie/" + counter);
   xhr.setRequestHeader("Accept","application/json");
   xhr.onreadystatechange = function () 
   {
    if(this.readyState == 4)
    {
      var json = JSON.parse(this.responseText);
      //var str = JSON.stringify(json,undefined,2);

      //  prints Movie Title
      document.getElementById("title").innerHTML = json.original_title;

      // displays Movie Poster
      document.getElementById("poster").src = "http://image.tmdb.org/t/p/w185/" + json.poster_path;
      
      // prints Movie Summary
      document.getElementById("summary").innerHTML = "Summary:".bold() + json.overview;

      // displays genres if a movie belongs to several genres
      var genres = "";
      for(i=0, len=json.genres.length; i<len; i++)
      {
        genres = genres + json.genres[i].name;
        if(i != len-1)
          genres = genres + ", ";
      }
      // prints Movie Genre
      document.getElementById("genres").innerHTML = "Genres:".bold() + genres;
      loadMovieCast(value);

    }
   };
   xhr.send(null);
}

// function to print movie cast
function loadMovieCast(value)
{
   var counter = value;
   var xhr = new XMLHttpRequest();
   xhr.open("GET", "proxy.php?method=/3/movie/" + counter + "/credits");
   xhr.setRequestHeader("Accept","application/json");
   xhr.onreadystatechange = function () 
   {
    if(this.readyState == 4)
    {
      var json = JSON.parse(this.responseText);
      // displays the top 5 cast members from the TMDB Database for movie
      var cast = "";
      for(i=0, len=json.cast.length; i<5; i++)
      {
        cast = cast + json.cast[i].name + ", ";
      }
      document.getElementById("cast").innerHTML = "Cast:".bold() + cast;
    }
    };
  xhr.send(null);
} 