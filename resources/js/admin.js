// public/js/admin.js

let userIdToEdit = null;

// Function to open the modal and set the current points
function openEditModal(userId, currentPoints) {
    userIdToEdit = userId;
    document.getElementById('pointsInput').value = currentPoints;  // Set the current points in the input field
    document.getElementById('editPointsModal').style.display = 'flex';  // Show the modal
}

// Function to close the modal
function closeEditModal() {
    document.getElementById('editPointsModal').style.display = 'none';  // Hide the modal
}

// Function to save the new points and send it to the backend
function savePoints() {
    const newPoints = document.getElementById('pointsInput').value;

    // Make an AJAX request to update points in the database
    fetch(`/admin/update-points/${userIdToEdit}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',  // CSRF token for security
        },
        body: JSON.stringify({
            points: newPoints,
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Points updated successfully');
            location.reload();  // Reload the page to reflect changes
        } else {
            alert('Failed to update points');
        }
    })
    .catch(error => console.error('Error updating points:', error));
}
