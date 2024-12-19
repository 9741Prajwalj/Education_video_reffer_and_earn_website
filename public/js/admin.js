
    // Function to show the selected table (user or referral)
    function showTable(tableType) {
        // Hide both tables by default
        document.getElementById('userTable').style.display = 'none';
        document.getElementById('referralTable').style.display = 'none';

        if (tableType === 'user') {
            document.getElementById('userTable').style.display = 'block';
        } else if (tableType === 'referral') {
            document.getElementById('referralTable').style.display = 'block';
            fetchAllReferrals(); // Fetch all referrals
        }
    }

    // Fetch all referrals without filtering by user ID
    function fetchAllReferrals() {
        fetch(`/admin/referrals`)
            .then(response => response.json())
            .then(data => {
                let referralTableBody = document.getElementById('referralTableBody');
                referralTableBody.innerHTML = ''; // Clear existing rows
                // Populate referral data into the table
                data.forEach(referral => {
                    let row = document.createElement('tr');
                    row.classList.add('border-t');
                    row.innerHTML = `
                        <td class="py-2 px-4">${referral.id}</td>
                        <td class="py-2 px-4">${referral.referral_name}</td>
                        <td class="py-2 px-4">${referral.referral_phone}</td>
                        <td class="py-2 px-4">${referral.user_id}</td>
                    `;
                    referralTableBody.appendChild(row);
                });
            })
            .catch(error => console.error('Error fetching referrals:', error));
    }

      // Function to open the edit modal and populate the input field with current points
      function openEditModal(userId, currentPoints) {
          document.getElementById('pointsInput').value = currentPoints;
          document.getElementById('editPointsModal').style.display = 'flex';
          window.currentUserId = userId; // Store the user ID to save points later
      }
      // Function to save the updated points
    function savePoints() {
        const newPoints = document.getElementById('pointsInput').value;

        try {
            // Validate new points
            if (!newPoints || isNaN(newPoints) || newPoints < 0) {
                alert('Please enter a valid non-negative number for points.');
                return;
            }

            // Make the fetch request to update points
            fetch(`/admin/dashboard/update-points/${window.currentUserId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ points: parseInt(newPoints) }),
            })
            .then(response => {
                // Handle non-200 HTTP responses
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Failed to update points. Status: ' + response.status);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Points updated successfully!');
                    console.log('Response:', data);
                    updateUserTable(window.currentUserId, newPoints);
                    closeEditModal();
                    setTimeout(() => location.reload(), 1000);// Add a slight delay for better UX
                } else {
                    throw new Error(data.message || 'Unknown error occurred');
                }
            })
            .catch(error => {
                // Show error toast message
                showToast('An error occurred: ' + error.message, 'error');
                console.error('Error:', error);
            });
        } catch (error) {
            // Handle unexpected errors
            alert('An unexpected error occurred: ' + error.message);
            console.error('Unexpected Error:', error);
        }
}

// Function to update the points in the user table after successful saving
function updateUserTable(userId, newPoints) {
    const userRow = document.querySelector(`tr[data-user-id="${userId}"]`);
    if (userRow) {
        const pointsCell = userRow.querySelector('.points-cell');
        if (pointsCell) {
            pointsCell.textContent = newPoints;
        }
    }
}

// Close the modal
function closeEditModal() {
    document.getElementById('editPointsModal').style.display = 'none';
}

// Open the modal and pre-fill data
function openEditModal(userId, currentPoints) {
    window.currentUserId = userId; // Store user ID globally for use in savePoints
    document.getElementById('pointsInput').value = currentPoints;
    document.getElementById('editPointsModal').style.display = 'flex';
} 

function showReferralList(userId) {
    // Send AJAX request to fetch referrals for the user
    fetch(`/admin/referrals/${userId}`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        // Populate the referral table
        const referralTableBody = document.getElementById('referralTableBody');
        referralTableBody.innerHTML = ''; // Clear the existing rows

        data.forEach(referral => {
            const row = `
                <tr class="border-t">
                    <td class="py-2 px-4">${referral.id}</td>
                    <td class="py-2 px-4">${referral.referral_name}</td>
                    <td class="py-2 px-4">${referral.referral_phone}</td>
                    <td class="py-2 px-4">${referral.user_id}</td>
                </tr>`;
            referralTableBody.innerHTML += row;
        });

        // Show the referral table and hide the user table
        document.getElementById('userTable').style.display = 'none';
        document.getElementById('referralTable').style.display = 'block';
    })
    .catch(error => {
        console.error('Error fetching referrals:', error);
    });
}

function showUserTable() {
    document.getElementById('referralTable').style.display = 'none';
    document.getElementById('userTable').style.display = 'block';
}

//For Adding the user Form
function showAddUserForm() {
    document.getElementById('userTable').style.display = 'none';
    document.getElementById('referralTable').style.display = 'none';
    document.getElementById('addUserForm').style.display = 'flex';
}

function closeAddUserForm() {
    document.getElementById('addUserForm').style.display = 'none';
    document.getElementById('userTable').style.display = 'block';
}

document.addEventListener("DOMContentLoaded", () => {
    const addUserForm = document.getElementById("addUserFormElement");

    addUserForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        // Collect form data
        const username = document.getElementById('username').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const password = document.getElementById('password').value.trim();
        const points = document.getElementById('points').value.trim();
        const csrfToken = document.querySelector('input[name="_token"]').value;

        // Gather form data
        const formData = new FormData(addUserForm);
        const data = Object.fromEntries(formData.entries());

        // Validate input
        if (!username || !email || !phone || !password || !points) {
            alert('Please fill out all fields.');
            return;
        }

        try {
            // Send data to backend using fetch
            const response = await fetch('/admin/dashboard/add-user', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,  // Make sure this is the correct token
                },
                body: JSON.stringify({
                    username,
                    email,
                    phone_number: phone,
                    password,
                    points
                }),
            });

            // Handle response
            const data = await response.json();
            if (response.ok) {
                alert('User added successfully!');
                addUserForm.reset(); // Reset form after success
            } else {
                alert('Error adding user: ' + (data.message || 'Unknown error'));
            }

        } catch (error) {
            console.error("Error:", error);
            alert('Error: ' + error.message);
        }

        console.log({ username, email, phone_number: phone, password, points }); // Log form data
    });
});

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        fetch(`/admin/dashboard/delete-user/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('User deleted successfully');
                document.querySelector(`[data-user-id="${userId}"]`).remove(); // Remove the row from the table
            } else {
                alert('Error deleting user');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error deleting the user');
        });
    }
}

// General function to filter any table
function filterTable(searchInputId, tableBodyId) {
    const searchInput = document.getElementById(searchInputId).value.trim().toLowerCase();
    const rows = document.querySelectorAll(`#${tableBodyId} tr`);
    let anyMatch = false;

    rows.forEach(row => {
        const cells = Array.from(row.querySelectorAll('td')).map(td => td.textContent.trim().toLowerCase());
        if (cells.some(cell => cell.includes(searchInput))) {
            row.style.display = ''; // Show matching row
            anyMatch = true;
        } else {
            row.style.display = 'none'; // Hide non-matching row
        }
    });

    // Handle "No Results Found" message
    const noResultsMessage = document.getElementById('noResultsMessage');
    if (noResultsMessage) {
        noResultsMessage.style.display = anyMatch ? 'none' : 'block';
    }
}
