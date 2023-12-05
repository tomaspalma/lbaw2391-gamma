document.getElementById('leaveGroupButton').addEventListener('click', async function (event) {

    const response = await fetch('/api/users/email/' + data);

    // You can add your custom logic here before submitting the form
    // For example, you can show a confirmation dialog

    // Finally, submit the form
    document.getElementById('leave_group').submit();
});