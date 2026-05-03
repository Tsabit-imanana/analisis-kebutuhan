<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Task List</h1>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Assigned To</th>
                <th>Assigned From</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->description }}</td>
                    <td>{{ $task->assigned_to }}</td>
                    <td>{{ $task->assigned_from }}</td>
<td>{{ $task->details->first()?->status }}</td>                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>