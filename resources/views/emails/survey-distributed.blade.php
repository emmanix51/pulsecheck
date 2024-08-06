<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>New Survey Available</title>
</head>

<body>
    <h1>{{ $survey->title }}</h1>
    <p>Hello {{ $user->first_name }},</p>
    <p>A new survey "{{ $survey->title }}" has been distributed to you. Please click the link below to participate:</p>
    <a href="{{ url('/public/survey/' . $survey->slug) }}">Answer Survey</a>
    <p>Thank you!</p>
    {{-- {{$name}}{{$from}} --}}

</body>

</html>