
<?php
use Illuminate\Support\Facades\DB;
?>
<!DOCTYPE html>
<html>
<head>
 <div>
     @include('admin.admin-base')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.0/css/bootstrap.min.css">
    <style>
        /* Custom styles for the form */
        .form-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            max-width: 900px;
            margin-left: 120px;
            padding: 20px;
            background-color: #f7f7f7;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-container .form-group label {
            font-weight: bold;
        }

        .form-container .form-group input[type="text"],
        .form-container .form-group input[type="number"],

        .form-container .form-group textarea,
        .form-container .form-group input[type="time"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-container .form-group .submit-btn {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: #fff;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-container .form-group .submit-btn:hover {
            background-color: #0056b3;
        }

        .data-table {
            width: 100%;
            margin-top: 20px;
        }

        .data-table th,
        .data-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .data-table th {
            font-weight: bold;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="container-fluid">
            <div>
                <h2 class="text-lg text-center font-weight-bold pt-3">FT Activations Task Orginize </h2>
                <?php
                $act=request()->query('code')??$code;
                ?>
                <p class="text-danger text-center font-weight-bold"> ({{$act}})</p>

                <p class="bg-secondary">
                    {{$message??''}}
                </p>

                <form action="{{ route('admin.task.store') }}" method="post" class="form border border-secondary rounded task-form">
                    @csrf
                    <input type="hidden" name="code" value="{{request()->query('code')}}">
                    <div>
                        <div class="form-group">
                            <label for="name">Task to  be completed:</label>
                            <input type="text" id="name" name="task" required class="form-control">
                        </div>
                    </div>
                    

                    <div class="form-group">
                        <x-input-label for="message">Description: </x-input-label>
                        <textarea id="message" name="desc" required class="form-control">
                            
                        </textarea>
                    </div>

                    <div class="form-group">
                        <label for="months">Months:</label>
                        <input type="number" id="months" name="month" min="1" max="12" required class="form-control">
                    </div>

                    <div class="form-group">
                        <center>
                             <button type="submit" class="btn btn-primary px-5 font-weight-bold">Add task</button>
                        </center>
                    </div>
                </form>
            </div>

            <div> <button class="btn btn-primary mx-3 my-2" style="float: right;" onclick="history.back()"><i class="las la-arrow-circle-left"></i> back</button>
            <?php

                $results=db::SELECT("SELECT * from tasks where code='$act'");

                $i=1;
                ?>
                @if (count($results)) 


                <div class="table-responsive container">
                    <table class="table table-striped rounded">
                        <thead class="bg-gradient-primary">
                            <tr>
                                <th>#</th>
                                <th>Task</th>
                                <th>Status </th>
                                <th>Time(Month)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $data)
                                <tr><td>{{$i++}}</td>
                                    <td>{{$data->task}}</td>
                                    <td>{{$data->status}}</td>
                                    <td><?php

                               echo $due=$data->month;
                                     ?>
                                         
                                     </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
