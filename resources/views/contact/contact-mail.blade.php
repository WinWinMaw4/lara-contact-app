
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .fw-bold{
            font-weight: bolder;
        }
    </style>
</head>
<body>
    <h1 class="text-danger">Danger &times;</h1>
    <h3>Contact Sharing</h3>
    <p>{{count($sendContact)}} Contact Shared form <span class="fw-bold ">`{{$sendUsers}}`</span> to <span class="fw-bold ">`{{$receiver->name}}`</span></p>
    <a href="{{route('contact.index')}}">See More</a>

</body>
</html>
