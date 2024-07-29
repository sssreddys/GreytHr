<!-- resources/views/connection-refused.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connection Refused</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }

        .error-container {
            text-align: center;
        }

        .error-image {
            height: 300px;
            width: 350px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 2em;
            margin-bottom: 10px;
            color: #333;
        }

        p {
            font-size: 1.2em;
            margin-bottom: 20px;
            color: #555;
        }

        .refresh-btn {
            padding: 10px 20px;
            font-size: 1.2em;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .refresh-btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>

    <div class="error-container">
        <img src="https://cdn1.iconfinder.com/data/icons/alert-14/64/1_database_error_server_warning_storage-1024.png" alt="Connection Refused" class="error-image">
        <h1>Data Base Connection Issues</h1>
        <p>Our site is currently undergoing maintenance. Please try again later.</p>


        <button class="refresh-btn" onclick="refreshPage()">Refresh Page</button>
    </div>

    <script>
        function refreshPage() {
            location.reload();
        }
    </script>

</body>

</html>
