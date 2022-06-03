<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset ('asset/css/bootstrap.min.css')}}">
    <title>Data Application</title>
</head>

<body>
    <p class="h4"> Application Name : {{$data->name}}</p>
    <p class="h5">Description : {{$data->description}}</p>
    @foreach ($data_module as $m)
    <p class="h5">- {{$m->name}}</p>
    <p class="h6">Description : {{$m->description}}</p>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Feature</th>
                <th scope="col">Output</th>
                <th scope="col">Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_feature as $f)
            @if ($f->module==$m->name)
            <tr>
                <td>{{$f->feature}}</td>
                <td>{{$f->output}}</td>
                <td>{{$f->description}}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    @endforeach
    <script src="{{ asset ('asset/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript">
        window.onload = function() {
            window.print();
        }
        window.onafterprint = function() {
            window.close();
        }
    </script>
</body>

</html>