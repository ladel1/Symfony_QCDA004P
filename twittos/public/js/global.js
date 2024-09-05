
const searchTwittosNode = document.querySelector("#search-twittos");
const searchDropdownNode = document.querySelector("#search-dropdown");
const displaySearchResults = document.querySelector("#display-search-results");
const API_URL = "/search/";

searchTwittosNode.addEventListener("keyup", (event) => {
    if (event.isComposing || event.keyCode === 229) {        
      return;
    }
    const textSearch = event.currentTarget.value;
    if(textSearch.length<=3){
      searchDropdownNode.classList.remove("show");
      return;
    }

    fetch(
      API_URL+textSearch
    )
    .then(response=>response.json())
    .then((data)=>{
      let jsonData = JSON.parse(data);          
      if(jsonData.code !== 1){
        searchDropdownNode.classList.remove("show");
        return;
      }
      displayUsers(jsonData);
      displayTwittos(jsonData);
      searchDropdownNode.classList.add("show");
    })
    .catch((error)=>{
      searchDropdownNode.classList.remove("show");
    })
  
  });


function displayUsers(jsonData){
  if(jsonData.type==="users"){
    displaySearchResults.innerHTML="";
    jsonData.results.forEach(element => {
        const aNode = document.createElement("a");
        aNode.classList.add("dropdown-item");
        aNode.setAttribute("href","/profile/@"+element);
        aNode.innerHTML=element;
        displaySearchResults.append(aNode);
    });
  }
}


function displayTwittos(jsonData) {
  if(jsonData.type==="twittos"){
    displaySearchResults.innerHTML="";
    jsonData.results.forEach(element => {
        const aNode = document.createElement("a");
        aNode.classList.add("dropdown-item");
        aNode.setAttribute("href","/s/"+element);
        aNode.innerHTML=element;
        displaySearchResults.append(aNode);
    });
  }
}












searchTwittosNode.addEventListener("search",(event)=>{
  searchDropdownNode.classList.remove("show");
})