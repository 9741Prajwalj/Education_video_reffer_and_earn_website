<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    // Function to show the selected table (user or referral)
    function showTable(tableType) {
        // Hide both tables by default
        document.getElementById('userTable').style.display = 'none';
        document.getElementById('referralTable').style.display = 'none';
        // Show the selected table
        if (tableType === 'user') {
            document.getElementById('userTable').style.display = 'block';
        } else if (tableType === 'referral') {
            document.getElementById('referralTable').style.display = 'block';       
            // Fetch referrals for the specific user if provided
            if (userId) {
                fetchReferrals(userId);
            }
        }
    }
    // Function to fetch referrals via an AJAX request
    function fetchReferrals(userId) {
        // Make an AJAX request to fetch the referrals
        fetch(`/admin/referrals/${userId}`)
            .then(response => response.json())
            .then(data => {
                let referralTableBody = document.getElementById('referralTableBody');
                referralTableBody.innerHTML = ''; // Clear existing table rows
                // Append new referral data to the table
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
            var newPoints = document.getElementById('pointsInput').value;
            if (newPoints) {
                fetch(`/admin/dashboard/updatePoints/${window.currentUserId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, // CSRF token
                    },
                    body: JSON.stringify({ points: newPoints }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Points updated successfully!');
                        updateUserTable(window.currentUserId, newPoints);
                        closeEditModal();
                    } else {
                        alert('Failed to update points: '+ (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error updating points:', error);
                    alert('An error occurred while updating points');
                });
            }
        }
        // Function to update the points in the user table after successful saving
        function updateUserTable(userId, newPoints) {
            // Find the corresponding table row for the user and update the points
            let userRow = document.querySelector(`tr[data-user-id="${userId}"]`);
            if (userRow) {
                let pointsCell = userRow.querySelector('.points-cell');
                if (pointsCell) {
                    pointsCell.textContent = newPoints;
                }
            }
        }
        // Function to close the modal
        function closeEditModal() {
            document.getElementById('editPointsModal').style.display = 'none';
        }
    </script>
</head>
<body class="bg-gray-100 h-screen flex">
    <!-- Sidebar Navigation -->
    <div class="w-1/4 bg-white shadow-lg p-4 flex flex-col items-center">
        <!-- Admin Image -->
        <div class="w-32 h-32 rounded-full overflow-hidden">
            <img src="/path/to/admin-image.jpg" alt="Admin Image" class="w-full h-full object-cover">
        </div>

        <!-- Upload Button -->
        <button class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Upload Image</button>

        <!-- Admin Details -->
        <div class="mt-4 text-center">
            <p class="text-lg font-bold">{{ $admin->name }}</p>
            <p class="text-gray-600">{{ $admin->email }}</p>
        </div>

        <!-- Navigation Buttons -->
        <div class="mt-6 w-full">
            <button onclick="showTable('user')" class="w-full px-4 py-2 mb-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">User List</button>
            <button onclick="showTable('referral')" class="w-full px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Referral List</button>
        </div>

        <!-- Logout Button -->
        <form action="{{ route('admin.logout') }}" method="POST" class="mt-auto w-full">
            @csrf
            <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700">Logout</button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8">
        <!-- User List Table -->
        <div id="userTable" class="w-full bg-white shadow-lg rounded-lg">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">User List</h1>
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="text-left py-2 px-4">ID</th>
                        <th class="text-left py-2 px-4">Username</th>
                        <th class="text-left py-2 px-4">Email</th>
                        <th class="text-left py-2 px-4">Phone</th>
                        <th class="text-left py-2 px-4">Referral Count</th>
                        <th class="text-left py-2 px-4">View</th>
                        <th class="text-left py-2 px-4">Points</th>
                        <th class="text-left py-2 px-4">Edit (Points)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="border-t">
                        <td class="py-2 px-4">{{ $user->id }}</td>
                        <td class="py-2 px-4">{{ $user->username }}</td>
                        <td class="py-2 px-4">{{ $user->email }}</td>
                        <td class="py-2 px-4">{{ $user->phone_number }}</td>
                        <td class="py-2 px-4">{{ $user->referral_count }}</td>
                        <td class="py-2 px-4">
                            <button onclick="showTable('referral', '{{ $user->id }}')" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-700">
                                View
                            </button>
                        </td>
                        <td class="py-2 px-4">{{ $user->points }}</td>
                        <!-- User Table -->
                        <td class="py-2 px-4">
                            <button onclick="openEditModal('{{ $user->id }}', '{{ $user->points }}')" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-700">
                                Edit
                            </button>
                        </td>
                        <!-- <td class="py-2 px-4">
                        <form action="{{ route('admin.dashboard.updatePoints', ['userId' => $user->id]) }}" method="POST" class="inline">
                            @csrf
                            <input type="number" name="points" value="{{ $user->points }}" class="hidden">
                            <button type="submit" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-700">
                                Edit
                            </button>
                        </form>  
                    </td> -->
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Referral List Table -->
        <div id="referralTable" class="w-full bg-white shadow-lg rounded-lg" style="display: none;">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Referral List</h1>
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="text-left py-2 px-4">ID</th>
                        <th class="text-left py-2 px-4">Referral Name</th>
                        <th class="text-left py-2 px-4">Referral Phone</th>
                        <th class="text-left py-2 px-4">User ID</th>
                    </tr>
                </thead>
                <tbody id="referralTableBody">
                    @foreach($referrals as $referral)
                    <tr class="border-t">
                        <td class="py-2 px-4">{{ $referral->id }}</td>
                        <td class="py-2 px-4">{{ $referral->referral_name }}</td>
                        <td class="py-2 px-4">{{ $referral->referral_phone }}</td>
                        <td class="py-2 px-4">{{ $referral->user_id }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Edit Modal (Hidden by default) -->
    <div id="editPointsModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center" style="display: none;">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Edit Points</h2>
            <input type="number" id="pointsInput" class="w-full px-4 py-2 mb-4 border border-gray-300 rounded" placeholder="Enter new points" />
            <div class="flex justify-end">
                <button onclick="savePoints()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
                <button onclick="closeEditModal()" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</button>
            </div>
        </div>
    </div>
<!-- In your Blade file, at the bottom before closing </body> -->
<script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>