<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>
    <!-- Add Font Awesome CDN for the search icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}"></head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex">
    <!-- In your Blade file, at the bottom before closing </body> -->
    <script src="{{ asset('js/admin.js') }}"></script>
    <!-- Sidebar Navigation -->
    <div class="w-1/5 bg-white shadow-lg p-4 flex flex-col items-center">
        <!-- Admin Image -->
        <div class="w-32 h-32 rounded-full overflow-hidden border-2 border-gray-500 bg-gray-100">
            <img src="{{ asset('image/admin_logo.png') }}" alt="Admin Image" class="w-full h-full object-cover">
        </div>
        <!-- Admin Details -->
        <div class="mt-4 text-center">
            <p class="text-lg font-bold">{{ $admin->name }}</p>
            <p class="text-gray-600">{{ $admin->email }}</p>
        </div>
        <!-- Navigation Buttons -->
        <div class="mt-6 w-full">
            <button onclick="showAddUserForm()" class="w-full px-4 py-2 mb-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300"><i class="fas fa-user-plus mr-2"></i> Add User</button>
            <button onclick="showTable('user')" class="w-full px-4 py-2 mb-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300"><i class="fas fa-users mr-2"></i> User List</button>
            <button onclick="showTable('referral')" class="w-full px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300"><i class="fas fa-share-alt mr-2"></i> Referral List</button>
             <!-- Add Notification Button -->
            <button onclick="showNotificationModal()" class="w-full px-4 py-2 mt-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                <i class="fas fa-bell mr-2"></i> Send Notification
            </button>
        </div>
        <div class="justify-center" >
            <!-- Logout Button -->
            <form action="{{ route('admin.logout') }}" method="POST" class="mt-2 w-full h-74">
                @csrf
                <button type="submit" class="w-20 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700">Logout</button>
            </form>
        </div>
    </div>

   <!-- Main Content -->
    <div class="flex-1 p-10">
        <!-- User List Table -->
        <div id="userTable" class="w-full bg-white shadow-lg rounded-lg">
            <div class="flex items-center justify-between mb-4 pt-4 pl-4">
                <h1 class="text-3xl font-bold text-gray-800">User List</h1>
                <div class="relative w-1/3">
                    <input type="text" id="userSearchInput" oninput="filterTable('userSearchInput', 'userTableBody')" class="px-4 py-2 border border-gray-300 rounded w-50 pl-10" placeholder="Search users..." />
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
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
                <tbody id="userTableBody" style="max-height: 300px; overflow-y: auto;">
                    @foreach($users as $user)
                    <tr class="border-t" data-user-id="{{ $user->id }}">
                        <td class="py-2 px-4">{{ $user->id }}</td>
                        <td class="py-2 px-4">{{ $user->username }}</td>
                        <td class="py-2 px-4">{{ $user->email }}</td>
                        <td class="py-2 px-4">{{ $user->phone_number }}</td>
                        <td class="py-2 px-4">{{ $user->referral_count }}</td>
                        <td class="py-2 px-4">
                            <button onclick="showReferralList('{{ $user->id }}')" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-700">
                                <i class="fas fa-eye mr-2"></i> View
                            </button>
                        </td>
                        <td class="py-2 px-4 points-cell">{{ $user->points }}</td>
                        <td class="py-2 px-4">
                            <button onclick="openEditModal('{{ $user->id }}', '{{ $user->points }}')" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-700">
                                <i class="fas fa-edit mr-2"></i> Edit
                            </button>
                        </td>
                        <td class="py-2 px-4">
                            <button onclick="deleteUser('{{ $user->id }}')" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-700">
                                <i class="fas fa-trash-alt mr-2"></i> Delete
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
       <!-- Referral List Table -->
        <div id="referralTable" class="w-full bg-white shadow-lg rounded-lg overflow-hidden" style="display: none;">
            <div class="flex items-center justify-between mb-4 pt-4 pl-4">
                <h1 class="text-3xl font-bold text-gray-800">Referral List</h1>
                <div class="flex items-center justify-between space-x-4 mr-4">
                    <div class="relative flex-grow max-w-md">
                        <!-- Search Input -->
                        <input type="text" id="referralSearchInput" oninput="filterTable('referralSearchInput', 'referralTableBody')" 
                            class="px-4 py-2 border border-gray-300 rounded w-full pl-10"
                            placeholder="Search referral..."/>
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <!-- Back to User Button -->
                    <button onclick="showUserTable()" 
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Back to User
                    </button>
                </div>
            </div>
            <!-- Referral List Table -->
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="text-left py-2 px-4 border-b">ID</th>
                        <th class="text-left py-2 px-4 border-b">Referral Name</th>
                        <th class="text-left py-2 px-4 border-b">Referral Phone</th>
                        <th class="text-left py-2 px-4 border-b">User ID</th>
                    </tr>
                </thead>
                <tbody id="referralTableBody">
                    @foreach($referrals as $referral)
                    <tr class="border-t">
                        <td class="py-3 px-4">{{ $referral->id }}</td>
                        <td class="py-3 px-4">{{ $referral->referral_name }}</td>
                        <td class="py-3 px-4">{{ $referral->referral_phone }}</td>
                        <td class="py-3 px-4">{{ $referral->user_id }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Add User Form -->
        <div id="addUserForm" class="fixed inset-0 flex justify-center items-center w-full h-full bg-gray-800 bg-opacity-50" style="display: none;">
            <div class="w-full md:w-1/3 lg:w-1/4 bg-white shadow-lg rounded-lg p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-6">Add New User</h1>
                <form id="addUserFormElement" method="POST" action="{{ route('admin.add-user') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="username" class="block text-gray-700 font-bold mb-2">Username</label>
                        <input type="text" id="username" name="username" placeholder="Jone" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                        <input type="email" id="email" name="email" placeholder="jone@gmail.com" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="block text-gray-700 font-bold mb-2">Phone</label>
                        <input type="text" id="phone" name="phone_number" placeholder="9999988888" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                        <input type="text" id="password" name="password" placeholder="min 8 character" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="points" class="block text-gray-700 font-bold mb-2">Points</label>
                        <input type="number" id="points" name="points" placeholder="0" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add</button>
                        <button type="button" onclick="closeAddUserForm()" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Notification Modal -->
        <div id="notificationModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
            <div class="bg-white rounded-lg p-6 w-96 relative mt-6">
                <!-- Close Button -->
                <button onclick="closeNotificationModal()" class="absolute -top-6 right-0 text-gray-600 hover:text-gray-800 text-2xl p-2 bg-white rounded-full shadow-lg">
                    <i class="fas fa-times"></i>
                </button>

                <h2 class="text-xl font-bold mb-4">Send Notification</h2>

                <form id="notificationForm" method="POST" action="{{ route('admin.sendNotification') }}" enctype="multipart/form-data">
                    @csrf
                    <!-- Notification Title -->
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Notification Title</label>
                        <input type="text" name="title" id="title" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>

                    <!-- Notification Message -->
                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea name="message" id="message" class="w-full px-3 py-2 border border-gray-300 rounded-md" rows="4" required></textarea>
                    </div>

                    <!-- User Selector (Optional, default to all users) -->
                    <div class="mb-4">
                        <label for="user_id" class="block text-sm font-medium text-gray-700">Select User (Optional)</label>
                        <select name="user_id" id="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->username }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Send Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Send Notification</button>
                    </div>
                </form>
            </div>
        </div>

    <!-- Edit Modal (Hidden by default) -->
    <div id="editPointsModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center" style="display: none;">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Edit Points</h2>
            <input type="number" id="pointsInput" class="w-full px-4 py-2 mb-4 border border-gray-300 rounded" placeholder="Enter new points" />
            <div class="flex justify-end">
            @if(session('success'))
                <script>alert("{{ session('success') }}");</script>
            @endif
            @if(session('error'))
                <script>alert("{{ session('error') }}");</script>
            @endif
                <button onclick="savePoints()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
                <button onclick="closeEditModal()" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</button>
            </div>
        </div>
    </div>
</body>
</html>