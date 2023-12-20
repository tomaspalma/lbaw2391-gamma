const old_name = document.getElementById("old_name");
const group_name = document.getElementById("name");
group_name.addEventListener("input", async (e) => {
    const temp = document.getElementById("name").value;
    if (temp.length > 0) {
        await checkGroupNameExists(temp);
    }
});

async function checkGroupNameExists(data) {
    if (old_name.value == data) {
        document.getElementById("name_error").classList.add("hidden");
        document.getElementById("submit").disabled = false;
        return;
    }

    fetch("/api/group/group_name/" + data)
        .then(async (res) => {
            const response = await res.json();
            if (response.exists) {
                document
                    .getElementById("name_error")
                    .classList.remove("hidden");
                document.getElementById("submit").disabled = true;
            } else {
                document.getElementById("name_error").classList.add("hidden");
                document.getElementById("submit").disabled = false;
            }
        })
        .catch((err) => {
            console.error(err);
        });
}
