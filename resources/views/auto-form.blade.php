<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="utf-8">
    <title>請稍候</title>
</head>
<body>
Please wait...

<form id="auto-form" action="{{ $action }}" method="{{ $method ?? 'GET' }}">
    @foreach ($fields as $field => $value)
        <input type="hidden" name="{{ $field }}" value="{{ $value }}">
    @endforeach
    <noscript>
        <button type="submit">Click here to continue 點擊此處以繼續</button>
    </noscript>
</form>

<script>
  document.getElementById('auto-form').submit();
</script>
</body>
</html>
