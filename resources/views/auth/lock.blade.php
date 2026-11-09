<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kunci Sesi</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold mb-4">Sesi terkunci
        </h2>
        <form method="POST" action="{{ route('lock.unlock') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Kata sandi</label>
                <input type="password" name="password" class="w-full border rounded p-2" required>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded">Buka kunci
            </button>
        </form>
    </div>
</body>
</html>
