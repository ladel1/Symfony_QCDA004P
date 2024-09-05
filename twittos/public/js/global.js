
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

/**
 * Display users list (only who already posted a twitto)
 * @param {*} jsonData 
 * @returns 
 */
function displayUsers(jsonData){
  if(jsonData.type!=="users") return;
  display(jsonData,"/profile/@");
}

/**
 * Display twittos in dropdown
 * @param {*} jsonData 
 * @returns 
 */
function displayTwittos(jsonData) {
  if(jsonData.type!=="twittos") return;
  display(jsonData,"/s/");
}
/**
 * Display in dropdows search
 * @param {*} data 
 * @param {*} href 
 */
function display(jsonData,prefix) {
  displaySearchResults.innerHTML="";
  jsonData.results.forEach(element => {
      const aNode = document.createElement("a");
      aNode.classList.add("dropdown-item");
      aNode.setAttribute("href",prefix+element);
      aNode.innerHTML=element;
      displaySearchResults.append(aNode);
  });
}

/**
 * Clear dropdown when click on close button
 */
searchTwittosNode.addEventListener("search",(event)=>{
  searchDropdownNode.classList.remove("show");
})