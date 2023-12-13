const memberGroupTypeSelect = document.getElementById("");

memberGroupTypeSelect.addEventListener("change", function(e) {
    let notificationsUrl = 'api/notifications/';
    let filter = "";

    if (e.target.value === 'reactions' || e.target.value === 'comments') {
        filter = `${e.target.value}`;
    }

    fetch(`${notificationsUrl}${filter}`).then(async (res) => {
        if (res.ok) {
            const html = await res.text();

            notificationCards.innerHTML = html;
        }
    }).catch((e) => {

    });
});
