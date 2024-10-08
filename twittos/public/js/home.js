/**
 * 
 * Like & Unlike a Twitto
 * 
 */

document.querySelectorAll(".like").forEach((element,numer,array)=>{
    element.addEventListener("click",function(event){                
        let twittoId = event.currentTarget.getAttribute("data-id");
        fetch('/like/'+twittoId,{ 
            method: "post"
         })
         .then((reponse)=> reponse.json())
         .then((data)=>{
            let like = JSON.parse(data);
            let likeButton = this.querySelector(".fa-heart");   

            if(like.status == 1){
                this.nextElementSibling.innerHTML++;
                likeButton.classList.add("fa-solid");
                likeButton.classList.remove("fa-regular");
            }else{
                this.nextElementSibling.innerHTML--;
                likeButton.classList.add("fa-regular");
                likeButton.classList.remove("fa-solid");
                
            }
            
         })
         .catch((error)=>{
            console.error(error);
         })
         ;
    })
});

/**
 * 
 * Delete Twitto
 * 
 */

document.querySelectorAll(".delete-twitto").forEach((element,key,array)=>{
    element.addEventListener("click",function (event) {
        
        const twittoId = event.currentTarget.getAttribute("data-id");
        const twittoCSRFToken = event.currentTarget.getAttribute("data-csrf-token");
        const twittoNodeContainer = this.closest("[data-delete-twitto]");

        if(!confirm("Voulez vous vraiment supprimer ce Twitto!")) return false;

        if(twittoId!==twittoNodeContainer.getAttribute("data-delete-twitto")) return;

        fetch("/twitto/delete/"+twittoId,{
            method:"POST",
            body: JSON.stringify({"_token":twittoCSRFToken})
        })
        .then(reponse=>reponse.json())
        .then(data=>{
            const json = JSON.parse(data);
            if(json.msg){
                document.getElementById("msg").innerHTML= "<div class='alert'>"+json.msg+"</div>";
            }
            if(json.code===false) return;
            alert("ddd");
            twittoNodeContainer.remove();
        })
        .catch(error=>console.log(error))


    });
})