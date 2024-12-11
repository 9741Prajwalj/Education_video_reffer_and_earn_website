<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Header Section with Logout Button -->
    <header class="w-full bg-blue-500 text-white py-4 px-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Dashboard</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                    class="bg-red-500 px-4 py-2 rounded-lg hover:bg-red-600 focus:outline-none focus:ring focus:ring-red-300">
                Logout
            </button>
        </form>
    </header>

    <!-- Main Content Section -->
    <main class="py-6 px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profile Section -->
            <div class="flex flex-col items-center bg-gray-200 p-4 rounded-lg">
                <div class="relative">
                    <img src="https://via.placeholder.com/100" 
                         alt="Profile Picture" 
                         class="w-24 h-24 rounded-full object-cover border-2 border-blue-500">
                    <label for="upload-photo" class="absolute bottom-0 right-0 bg-blue-500 text-white text-xs px-2 py-1 rounded-full cursor-pointer">
                        Upload
                    </label>
                    <input type="file" id="upload-photo" class="hidden">
                </div>
                <h2 class="mt-3 text-xl font-semibold text-gray-700">{{ auth()->user()->username }}</h2>
            </div>

            <!-- Points Section -->
            <div class="flex items-center justify-center bg-gray-200 p-4 rounded-lg">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-blue-500">Points</h3>
                    <p class="text-4xl font-extrabold text-gray-800">{{ $points }}</p>
                </div>
            </div>

            <!-- Update Information Form -->
            <div class="bg-gray-200 p-4 rounded-lg">
                <h3 class="text-xl font-semibold text-gray-700 mb-4 text-center">Update Referral Information</h3>
                @if(session('success'))
                    <div class="mb-4 p-2 bg-green-200 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('update.referral') }}" class="space-y-4">
                    @csrf
                    <!-- Referral Name -->
                    <div>
                        <label for="referral_name" class="block text-gray-600 font-medium">Referral Name</label>
                        <input type="text" id="referral_name" name="referral_name" 
                               class="w-full mt-1 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200" 
                               placeholder="Enter referral name" value="{{ old('referral_name') }}" required>
                    </div>

                    <!-- Referral Phone Number -->
                    <div>
                        <label for="referral_phone" class="block text-gray-600 font-medium">Referral Phone Number</label>
                        <input type="tel" id="referral_phone" name="referral_phone" 
                               class="w-full mt-1 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200" 
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
            </div>
        </div>
        <!-- Advertisement Section -->
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <!-- Advertisement Cards -->
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
        </div>
    </main>
</body>
</html>
