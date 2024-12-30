// Function to show the selected table (user or referral)
function showTable(tableType) {
    document.getElementById('userTable').style.display = tableType === 'user' ? 'block' : 'none';
    document.getElementById('referralTable').style.display = tableType === 'referral' ? 'block' : 'none';
    if (tableType === 'referral') fetchAllReferrals(); // Fetch all referrals
}

document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Fetch and populate referrals initially
    fetchAllReferrals(csrfToken);

    // Dynamically attach click listeners for buttons (delegation method)
    document.getElementById('referralTableBody').addEventListener('click', (event) => {
        if (event.target.classList.contains('status-button')) {
            const button = event.target;
            const id = button.dataset.id;
            const status = button.dataset.status;

            // Call the updateStatus function
            updateStatus(id, status, csrfToken);
        }
    });
});
// Function to fetch all referrals
function fetchAllReferrals(csrfToken) {
    fetch('/admin/referrals', {
        headers: {
            'X-CSRF-TOKEN': csrfToken, // Include CSRF token if required
        },
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch referrals');
            }
            return response.json();
        })
        .then(data => {
            const referralTableBody = document.getElementById('referralTableBody');
            referralTableBody.innerHTML = ''; // Clear existing rows

            data.forEach(referral => {
                const row = document.createElement('tr');
                row.classList.add('border-t');
                row.innerHTML = `
                    <td class="py-3 px-4">${referral.id}</td>
                    <td class="py-3 px-4">${referral.referral_name}</td>
                    <td class="py-3 px-4">${referral.referral_phone}</td>
                    <td class="py-3 px-4">${referral.user_id}</td>
                `;
                referralTableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error fetching referrals:', error);
            alert('An error occurred while fetching referrals. Please try again.');
        });
}


// Function to open the edit modal and populate the input field with current points
function openEditModal(userId, currentPoints) {
    document.getElementById('pointsInput').value = currentPoints;
    document.getElementById('editPointsModal').style.display = 'flex';
    window.currentUserId = userId; // Store user ID globally for use in savePoints
}

// Function to save the updated points
function savePoints() {
    const newPoints = document.getElementById('pointsInput').value;

    if (!newPoints || isNaN(newPoints) || newPoints < 0) {
        alert('Please enter a valid non-negative number for points.');
        return;
    }

    fetch(`/admin/dashboard/update-points/${window.currentUserId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ points: parseInt(newPoints) }),
    })
        .then(response => response.ok ? response.json() : Promise.reject('Failed to update points'))
        .then(data => {
            if (data.success) {
                alert('Points updated successfully!');
                updateUserTable(window.currentUserId, newPoints);
                closeEditModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                throw new Error(data.message || 'Unknown error occurred');
            }
        })
        .catch(error => {
            alert('An error occurred: ' + error.message);
            console.error('Error:', error);
        });
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

// Fetch referrals for a specific user
function showReferralList(userId) {
    fetch(`/admin/referrals/${userId}`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        const referralTableBody = document.getElementById('referralTableBody');
        referralTableBody.innerHTML = '';
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

        document.getElementById('userTable').style.display = 'none';
        document.getElementById('referralTable').style.display = 'block';
    })
    .catch(error => console.error('Error fetching referrals:', error));
}

// Show user table
function showUserTable() {
    document.getElementById('referralTable').style.display = 'none';
    document.getElementById('userTable').style.display = 'block';
}

// Show Add User Form
function showAddUserForm() {
    document.getElementById('userTable').style.display = 'none';
    document.getElementById('referralTable').style.display = 'none';
    document.getElementById('addUserForm').style.display = 'flex';
}

// Close Add User Form
function closeAddUserForm() {
    document.getElementById('addUserForm').style.display = 'none';
    document.getElementById('userTable').style.display = 'block';
}

// Handle user form submission
document.addEventListener("DOMContentLoaded", () => {
    const addUserForm = document.getElementById("addUserFormElement");

    addUserForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        const username = document.getElementById('username').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const password = document.getElementById('password').value.trim();
        const points = document.getElementById('points').value.trim();
        const csrfToken = document.querySelector('input[name="_token"]').value;

        if (!username || !email || !phone || !password || !points) {
            alert('Please fill out all fields.');
            return;
        }

        try {
            const response = await fetch('/admin/dashboard/add-user', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ username, email, phone_number: phone, password, points })
            });

            const data = await response.json();
            if (response.ok) {
                alert('User added successfully!');
                addUserForm.reset();
            } else {
                alert('Error adding user: ' + (data.message || 'Unknown error'));
            }

        } catch (error) {
            console.error("Error:", error);
            alert('Error: ' + error.message);
        }
    });
});

// Delete user from the table
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
                document.querySelector(`[data-user-id="${userId}"]`).remove();
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

 // Show the notification modal
 function showNotificationModal() {
    document.getElementById('notificationModal').classList.remove('hidden');
}

// Close the notification modal
function closeNotificationModal() {
    document.getElementById('notificationModal').classList.add('hidden');
}


// JavaScript to handle the opening and closing of the notification modal

// Function to open the modal
function openNotificationModal() {
    document.getElementById("notificationModal").classList.remove("hidden");
    document.getElementById("notificationModal").classList.add("flex");
}

// Function to close the modal
function closeNotificationModal() {
    document.getElementById("notificationModal").classList.add("hidden");
    document.getElementById("notificationModal").classList.remove("flex");
}

// Function to load notifications via AJAX
function loadNotifications() {
    fetch('/dashboard') // Make a GET request to the dashboard route
        .then(response => response.json())
        .then(data => {
            let notificationsHtml = '';
            if (data.length > 0) {
                data.forEach(notification => {
                    notificationsHtml += `
                        <div class="mb-4 p-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold">${notification.title}</h3>
                            <p class="text-gray-700">${notification.message}</p>
                        </div>
                    `;
                });
            } else {
                notificationsHtml = '<p>No notifications available.</p>';
            }
            document.getElementById('notificationsContent').innerHTML = notificationsHtml;
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
        });
}

// Add an event listener to open the modal and load notifications
document.getElementById('openNotificationsBtn').addEventListener('click', () => {
    loadNotifications(); // Load notifications when modal opens
    openNotificationModal(); // Open the modal
});
