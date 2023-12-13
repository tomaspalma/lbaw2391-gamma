const groupMemberTypeFilter = document.getElementById("group-member-filter");
const memberCards = document.getElementById("member-cards");

groupMemberTypeFilter.addEventListener("change", function(e) {
    let url = window.location.href.split("/");
    url = url.slice(3).join("/");
    let filter = "";

    if (e.target.value === "allUsers"
        || e.target.value === "groupOwners"
        || e.target.value === "members") {
        filter = `${e.target.value}`;
    }

    fetch(`/api/${url}/${filter}`).then(async (res) => {
        if (res.ok) {
            const memberCardsJson = await res.json();
            console.log(memberCardsJson);

            memberCards.innerHTML = "";

            if (memberCardsJson.length === 0) {
                memberCards.innerHTML += `<p class="text-center">No members found.</p>`;
            }

            for (const memberCard of memberCardsJson) {
                memberCards.innerHTML += memberCard;
            }
        }
    }).catch((e) => {
        console.error(e)
    });
});
