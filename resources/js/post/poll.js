import { getCsrfToken } from "../utils";

const pollAddBtn = document.getElementById("add-poll-btn");
const pollCreation = document.getElementById("poll-creation");
const addPollOptionBtn = document.getElementById("add-poll-option-btn");

const options = document.getElementById("options");

const optionInput = '<input placeholder="Option" type="text" name="poll_options[]" class="mt-1 p-2 border border-gray-300 rounded-md w-full" required><button class="remove-option-btn mt-1 p-2 border border-gray-300 hover:bg-black hover:text-white transition-colors rounded-md">-</button>';

function toggleRemoveOption(optionRemove) {
    optionRemove.addEventListener("click", (e) => {
        e.preventDefault();

        optionRemove.parentElement.remove();
    });
}

if (pollAddBtn) {
    pollAddBtn.addEventListener("click", (e) => {
        e.preventDefault();

        pollCreation.classList.contains("hidden")
            ? pollCreation.classList.remove("hidden")
            : pollCreation.classList.add("hidden");

        pollAddBtn.classList.contains("hidden")
            ? pollAddBtn.classList.remove("hidden")
            : pollAddBtn.classList.add("hidden");
    });
}

if (addPollOptionBtn) {
    addPollOptionBtn.addEventListener("click", (e) => {
        e.preventDefault();

        const div = document.createElement("div");
        div.classList.add("flex", "flex-row", "space-x-1");
        div.innerHTML = optionInput;

        toggleRemoveOption(div.querySelector(".remove-option-btn"));

        options.appendChild(div);
    });
}

const pollOptions = document.querySelectorAll(".poll-option");
for (const pollOption of pollOptions) {
    pollOption.addEventListener("submit", (e) => {
        e.preventDefault();

        fetch(`/poll/${pollOption.getAttribute("data-poll-id")}`, {
            method: "POST",
            headers: {
                "X-CSRF-Token": `${getCsrfToken()}`,
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                "option": `${pollOption.getAttribute("data-option")}`
            })
        }).then((res) => {
            if (res.ok) {

            }
        }).catch((e) => console.error(e));
    });
}

