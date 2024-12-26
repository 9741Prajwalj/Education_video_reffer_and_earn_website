<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/user.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/user.css') }}"></head>
<body class="bg-gray-100">
    <!-- Header Section -->
    <header class="w-full bg-blue-500 text-white py-4 px-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Dashboard</h1>
        <!-- Sliding Text Section -->
        <div class="slide w-1/1 overflow-hidden relative">
            <div class="sliding-text text-xl">
                😊Welcome to the Dashboard! Manage your data and settings seamlessly.😁 &nbsp;|&nbsp;
                Stay productive and organized with our tools.🧑‍🏫 &nbsp;|&nbsp;
                Have a great day!🤗
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <!-- Notification Button -->
            <button id="notificationButton" class="px-3 py-2 bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring focus:ring-yellow-300">
                <i class="fa-solid fa-bell"></i> <!-- Notification Icon -->
            </button>
            <!-- Change Password Button -->
            <button onclick="openChangePasswordModal()" class="px-3 py-2 bg-green-500 rounded-lg hover:bg-green-600 focus:outline-none focus:ring focus:ring-green-300">
                <i class="fa-solid fa-lock"></i> <!-- Lock Icon for Change Password -->
            </button>
            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 px-4 py-2 rounded-lg hover:bg-red-600 focus:outline-none focus:ring focus:ring-red-300">
                    Logout
                </button>
            </form>
        </div>
    </header>
    @if (session('success'))
        <div id="successAlert" class="alert bg-green-500 text-white px-4 py-2 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div id="errorAlert" class="alert bg-red-500 text-white px-4 py-2 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div id="errorAlert" class="alert bg-red-500 text-white px-4 py-2 rounded-lg mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <!-- Change Password Modal -->
    <div id="changePasswordModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-lg font-bold mb-4">Change Password</h2>
            <form id="changePasswordForm" method="POST" action="{{ route('password.change') }}">
                @csrf
                <!-- Current Password -->
                <div class="mb-4">
                    <label for="currentPassword" class="block text-gray-700">Current Password</label>
                    <input type="password" id="currentPassword" name="currentPassword" placeholder="using password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <!-- New Password -->
                <div class="mb-4">
                    <label for="newPassword" class="block text-gray-700">New Password</label>
                    <input type="password" id="newPassword" name="newPassword" placeholder="new password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <!-- Show Password Checkbox -->
                <div class="flex items-center mb-4">
                    <input type="checkbox" id="showPassword" onclick="togglePasswordVisibility()" class="mr-2">
                    <label for="showPassword" class="text-gray-700">Show Password</label>
                </div>
                <!-- Buttons -->
                <div class="flex justify-end">
                    <button type="button" onclick="closeChangePasswordModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Change
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Main Content Section -->
    <main class="py-6 px-4 space-y-8">
        <!-- Section 1: Profile and Points -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Profile Section -->
            <div class="flex flex-col items-center bg-gray-200 p-4 rounded-lg">
            <div class="w-32 h-32 rounded-full overflow-hidden border-2 border-gray-500 bg-gray-100">
                <img src="{{ asset('image/user_logo.png') }}" alt="Admin Image" class="w-full h-full object-cover">
            </div>
                <h2 class="mt-3 text-xl font-semibold text-gray-700">{{ auth()->user()->username }}</h2>
                <h2 class="mt-3 text-xl font-semibold text-gray-700">{{ auth()->user()->email }}</h2>
            </div>

            <!-- Points Section -->
            <div class="flex items-center justify-center bg-gray-200 p-4 rounded-lg">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-blue-500">Points</h3>
                    @if($points > 0)
                        <p class="text-4xl font-extrabold text-gray-800">{{ $points }}</p>
                    @else
                        <p class="text-lg font-medium text-red-500">To Continue, Recharge Again</p>
                    @endif
                </div>
            </div>
        </section>
        <!-- Section 2: Update Information Form -->
        <div class="flex items-center justify-center mt-2 bg-gray-100">
            <section class="bg-white shadow-md rounded-lg p-6 w-full max-w-md">
                <h3 class="text-xl font-semibold text-gray-700 mb-4 text-center">Update Referral Information</h3>
                @if($points > 0)
                    <form method="POST" action="{{ route('update.referral') }}" class="space-y-4">
                        @csrf
                        <!-- Referral Name -->
                        <div>
                            <label for="referral_name" class="block text-gray-600 font-medium">Referral Name</label>
                            <input type="text" id="referral_name" name="referral_name" class="w-full mt-1 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200" 
                                placeholder="Enter referral name" value="{{ old('referral_name') }}" required>
                        </div>
                        <!-- Referral Phone Number -->
                        <div>
                            <label for="referral_phone" class="block text-gray-600 font-medium">Referral Phone Number</label>
                            <input type="tel" id="referral_phone" name="referral_phone" class="w-full mt-1 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200" 
                                placeholder="Enter referral phone number" value="{{ old('referral_phone') }}" required>
                        </div>
                        <!-- Hidden User ID -->
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" 
                                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">
                                Submit
                            </button>
                        </div>
                    </form>
                @else
                    <p class="text-center text-red-500 font-medium">Recharge to Update</p>
                @endif
            </section>
        </div>
        <!-- Section 3: Referred List -->
        <div class="flex items-center justify-center mt-2 bg-gray-100">
            <section class="bg-white p-6 border border-gray-200 rounded-lg shadow-lg w-full max-w-lg">
                <h3 class="text-xl font-semibold text-gray-700 mb-4 text-center">Referred List</h3>
                @if($referralList->isEmpty())
                    <p class="text-gray-500 text-center">No referrals yet.</p>
                @else
                    <div id="referral-list-container" class="overflow-y-auto max-h-48"> <!-- Set max height and enable scrolling -->
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 text-left text-gray-700">Referred Name</th>
                                    <th class="px-4 py-2 text-left text-gray-700">Referred Phone</th>
                                    <th class="px-4 py-2 text-left text-gray-700">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($referralList as $referral)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $referral->referral_name }}</td>
                                        <td class="px-4 py-2">{{ $referral->referral_phone }}</td>
                                        <td class="px-4 py-2">
                                            @if($referral->referral_name || $referral->referral_phone || $referral->status == 'sent')
                                                <i class="fas fa-check text-green-500"></i> <!-- Tick mark icon -->
                                            @else
                                                <i class="fas fa-clock text-yellow-500"></i> <!-- Waiting icon -->
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>
        <!-- Section 4: Advertisements -->
        <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                <img src="https://via.placeholder.com/300x150" alt="Ad Image" class="w-full h-32 object-cover">
                <div class="p-4">
                    <h4 class="text-lg font-semibold text-gray-800">Advertisement 1</h4>
                    <p class="text-sm text-gray-600">This is a sample ad description.</p>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                <img src="https://via.placeholder.com/300x150" alt="Ad Image" class="w-full h-32 object-cover">
                <div class="p-4">
                    <h4 class="text-lg font-semibold text-gray-800">Advertisement 2</h4>
                    <p class="text-sm text-gray-600">This is another ad description.</p>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                <img src="https://via.placeholder.com/300x150" alt="Ad Image" class="w-full h-32 object-cover">
                <div class="p-4">
                    <h4 class="text-lg font-semibold text-gray-800">Advertisement 3</h4>
                    <p class="text-sm text-gray-600">Yet another ad description.</p>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                <img src="https://via.placeholder.com/300x150" alt="Ad Image" class="w-full h-32 object-cover">
                <div class="p-4">
                    <h4 class="text-lg font-semibold text-gray-800">Advertisement 4</h4>
                    <p class="text-sm text-gray-600">Yet another ad description.</p>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                <img src="https://via.placeholder.com/300x150" alt="Ad Image" class="w-full h-32 object-cover">
                <div class="p-4">
                    <h4 class="text-lg font-semibold text-gray-800">Advertisement 5</h4>
                    <p class="text-sm text-gray-600">Yet another ad description.</p>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                <img src="https://via.placeholder.com/300x150" alt="Ad Image" class="w-full h-32 object-cover">
                <div class="p-4">
                    <h4 class="text-lg font-semibold text-gray-800">Advertisement 6</h4>
                    <p class="text-sm text-gray-600">Yet another ad description.</p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
