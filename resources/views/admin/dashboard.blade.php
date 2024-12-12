<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen">
    <div class="container mx-auto py-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Welcome, {{ $admin->name }}</h1>
        <p class="text-gray-700">This is your admin dashboard.</p>

        <form action="{{ route('admin.logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700">Logout</button>
        </form>
    </div>
</body>
</html>
