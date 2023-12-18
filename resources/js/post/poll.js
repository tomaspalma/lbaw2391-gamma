import { addCounter, getCsrfToken, getUsername, subtractCounter } from "../utils";
import { addSnackbar } from "../components/snackbar";

const pollAddBtn = document.getElementById("add-poll-btn");
const pollCreation = document.getElementById("poll-creation");
const addPollOptionBtn = document.getElementById("add-poll-option-btn");

const options = document.getElementById("options");

const optionInput = '<input placeholder="Option" type="text" name="poll_options[]" class="mt-1 p-2 border border-gray-300 rounded-md w-full" required><button class="remove-option-btn mt-1 p-2 border border-gray-300 hover:bg-black hover:text-white transition-colors rounded-md">-</button>';

const postArticle = document.getElementById("post-article");

let selectedOption;

if (postArticle) {
    selectedOption = postArticle.getAttribute("data-selected-option");
}

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

function addVoteAction(pollOptionForm, option) {
    pollOptionForm.setAttribute("data-selected-vote", "1");
    pollOptionForm.querySelector(".poll-selected-checkmark").classList.remove("hidden");

    pollOptionForm.classList.remove("unselected-poll-option");
    pollOptionForm.classList.add("selected-poll-option");

    addCounter(pollOptionForm.querySelector(".option-vote-counter"), 1);

    if (selectedOption && selectedOption !== pollOptionForm.id) {
        const option = document.getElementById(`${selectedOption}`);

        removeVoteAction(option, false);

        addSnackbar("You switched your vote!", 2000);
    } else {
        addSnackbar("You added your vote!", 2000);
    }

    selectedOption = option;
}

async function addVote(pollOptionForm, pollId, option) {
    addVoteAction(pollOptionForm, option);

    fetch(`/poll/${pollId}`, {
        method: "POST",
        headers: {
            "X-CSRF-Token": `${getCsrfToken()}`,
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            "option": `${option}`
        })
    }).then((res) => {
        if (!res.ok) {
            removeVoteAction(pollOptionForm);
        }
    }).catch((e) => console.error(e));
}

function removeVoteAction(pollOptionForm, activateSnackbar) {
    pollOptionForm.querySelector(".poll-selected-checkmark").classList.add("hidden");
    pollOptionForm.setAttribute("data-selected-vote", "0");

    pollOptionForm.classList.add("unselected-poll-option");
    pollOptionForm.classList.remove("selected-poll-option");

    subtractCounter(pollOptionForm.querySelector(".option-vote-counter"), 1);

    if (activateSnackbar) {
        addSnackbar("You removed your vote!", 2000);
    }
}

async function removeVote(pollOptionForm, pollId, option) {
    removeVoteAction(pollOptionForm, true);

    fetch(`/poll/${pollId}`, {
        method: "DELETE",
        headers: {
            "X-CSRF-Token": `${getCsrfToken()}`,
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            "option": `${option}`
        })
    }).then((res) => {
        if (!res.ok) {
            addVoteAction(pollOptionForm);
        }
    }).catch((e) => console.error(e));

}

const pollOptions = document.querySelectorAll(".poll-option");
for (const pollOption of pollOptions) {
    pollOption.addEventListener("submit", async (e) => {
        e.preventDefault();

        if (getUsername()) {
            const pollId = pollOption.getAttribute("data-poll-id");
            const pollOptionValue = pollOption.getAttribute("data-option");

            if (pollOption.getAttribute("data-selected-vote") === "0") {
                await addVote(e.target, pollId, pollOptionValue);
            } else {
                await removeVote(e.target, pollId, pollOptionValue);
            }
        }
    });
}

