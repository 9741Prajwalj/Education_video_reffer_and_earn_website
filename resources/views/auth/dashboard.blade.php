<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Header Section -->
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
    <main class="py-6 px-4 space-y-8">
        <!-- Section 1: Profile and Points -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                @if(session('success'))
                    <div class="mb-4 p-2 bg-green-200 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if($points > 0)
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
                @else
                    <p class="text-center text-red-500 font-medium">You are not allowed to Update</p>
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
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left text-gray-700">Referred Name</th>
                                <th class="px-4 py-2 text-left text-gray-700">Referred Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($referralList as $referral)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $referral->referral_name }}</td>
                                    <td class="px-4 py-2">{{ $referral->referral_phone }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
        </section>
    </main>
</body>
</html>
