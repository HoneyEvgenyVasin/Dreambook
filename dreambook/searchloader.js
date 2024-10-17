let do_search = (e) => 
{
    e.preventDefault();
    let query = document.getElementById("search").value;
    const hint = document.createElement("h2");
    

    document.getElementById("search_reqults").innerHTML = "";

    hint.textContent = "Результаты поискового запроса: " + query;
    document.getElementById("search_reqults").append(hint);

    fetch(`https://chilloutsounds.ru/search.php?query=${query}`)
        .then(response => response.json())
        .then(data => {
            data.forEach(item =>{
                const link = document.createElement("a");
                const block = document.createElement("div");

                link.href = `https://chilloutsounds.ru/dream_page.php?book_id=${item.dreambook_id}&id=${item.id}&keyword=${item.keyword}&drname=${item.dreambook}`;
                link.textContent = item.dreambook + ". " + item.keyword;
                link.className = "btn btn-primary link-misc";

                block.appendChild(link);

                document.getElementById("search_reqults").append(block);
            })
        })
        .catch(error => console.log("Error fetching data:", error));
}
