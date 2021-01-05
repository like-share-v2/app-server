<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>loading...</title>
    <script>
        window.onload = function () {
            document.getElementById('pay').submit();
        }
    </script>
</head>
<body>
    <form id="pay" method="{{ $method }}" action="{{ $action }}">
        @foreach ($formData as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>
</body>
</html>