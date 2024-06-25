<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Internal Server Error</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #ffff;
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
            height: 350px;
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

        .home-btn {
            background-color:  rgb(2, 17, 79);
            padding: 10px 20px;
            font-size: 12px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .home-btn:hover {
            background-color: #2980b9;
        }
        a{
            text-decoration: none;
            color: white;
        }

    </style>
</head>
<body>

    <div class="error-container">
        <img src="https://thumbs.dreamstime.com/b/oops-error-page-not-found-flat-illustration-internet-connection-problem-small-robot-icon-vector-problems-175093796.jpg" alt="Page Not Found" class="error-image">
        <h1>500</h1>
        <h3 class="h2 mb-3"><i class="fas fa-exclamation-triangle"></i> Internal Server Error</h3>
        <p class="h4 font-weight-normal">You do not have permission to view this resource</p>
        {{-- <a href="{{route('emplogin')}}" class="btn btn-primary">Back to Home</a> --}}
    </div>

</body>
</html>
