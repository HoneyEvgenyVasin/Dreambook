//`http://dreambook/change_rating.php?action=${like|dislike}&rid=${}`

let like = (e, rid, obj) => {
    if(localStorage.getItem(`like_change:${rid}`) !== null){
        alert("Вы уже проголосовали!");
        return;
    }

    localStorage.setItem(`like_change:${rid}`, Date.now());

    fetch(`https://chilloutsounds.ru/change_rating.php?action=like&rid=${rid}`)
    .then(response => response.json())
    .then(data => {
        obj.innerHTML = "Нравится " + data.likes;
    })
    .catch(error => console.log("Error of update likes"));
}

let dislike = (e, rid, obj) => {
    if(localStorage.getItem(`dislike_change:${rid}`) !== null){
        alert("Вы уже проголосовали!");
        return;
    }

    localStorage.setItem(`dislike_change:${rid}`, Date.now());
    
    fetch(`https://chilloutsounds.ru/change_rating.php?action=dislike&rid=${rid}`)
    .then(response => response.json())
    .then(data => {
        obj.innerHTML = "Не нравится " + data.dislikes;
    })
    .catch(error => console.log("Error of update likes"));
}