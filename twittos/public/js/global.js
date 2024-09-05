
const searchTwittosNode = document.querySelector("#search-twittos");
const searchDropdownNode = document.querySelector("#search-dropdown");
const displaySearchResults = document.querySelector("#display-search-results");
const API_URL = "/search/";

searchTwittosNode.addEventListener("keyup", (event) => {
    if (event.isComposing || event.keyCode === 229) {        
      return;
    }
    const textSearch = event.currentTarget.value;
    if(textSearch.length>3){
        fetch(
          API_URL+textSearch
        )
        .then(response=>response.json())
        .then((data)=>{
          let jsonData = JSON.parse(data);          
          if(jsonData.code === 1){
            //console.log(jsonData);
            displaySearchResults.innerHTML="";
            jsonData.results.forEach(element => {
                const aNode = document.createElement("a");
                aNode.classList.add("dropdown-item");
                aNode.innerHTML=element;
                displaySearchResults.append(aNode);
            });
            searchDropdownNode.classList.add("show");
          }else{
            searchDropdownNode.classList.remove("show");
          }
        })
        .catch((error)=>{
          searchDropdownNode.classList.remove("show");
        })
    }else{
        searchDropdownNode.classList.remove("show");
    }
  });

  searchTwittosNode.addEventListener("search",(event)=>{
    searchDropdownNode.classList.remove("show");
  })