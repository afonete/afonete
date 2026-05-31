 <?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
// use App\Models\Activations
$user = Auth::user();
$name = $user->name;
$email=$user->email;
$activated=$user->has_paid_package;
$created=$user->created_at;
// $act=find::Activations()
$sponsored=$user->referee_id??'System';

?>

 <div>
     @include('user.user-dashboard-base')
<style type="text/css">
    .table-container {
  overflow-x: auto;
}

.responsive-table {
  width: 80%;
  border-collapse: collapse;
  margin-left: 50px;
}

.responsive-table th,
.responsive-table td {
  padding: 8px;
  border: 1px solid #ccc;
}

@media screen and (max-width: 600px) {
  .responsive-table th,
  .responsive-table td {
    font-size: 14px;
  }
}
.team-container{
    width: 95%;
    margin: 0px auto;
    background-color: white;
    border-radius: 3px;
}
</style>
<div class="content-wrapper pt-5">
    <div class="team-container">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-12"> 
                <div class="table-container">
                 <table class="table table-striped  hist_table" style="text-align: center;">
                         <?php
                            $result=db::SELECT("SELECT *,tasks.status as status FROM users join activations on(users.email=activations.email) join tasks ON(activations.code=tasks.code)  WHERE users.has_paid_package='ft' and  activations.package='ft' and users.email='$email'");
                        ?>
                        <div>
                            <p class="text-center font-weight-bold my-3">My task</p>
                          </div>
                    
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Task</th>
                        <th>Description</th>
                        <th>remaining days</th>
                        <th>Complete before</th>
                        <th>Status || complete</th>
                        
                       
                      </tr>
                    </thead>
                <tbody>
                   <?php
                          $n=1;


                        function calculateDueDate($years, $months) {
                            $currentDate = new DateTime();
                            $expirationDate = clone $currentDate;
                            $expirationDate->modify("+" . $years . " years");
                            $expirationDate->modify("+" . $months . " months");
                            $dueDate = clone $expirationDate;
                            $dueDate->modify("-1 day");
                            return $dueDate;
                        }
                        function calculateRemainingDays($dueDate) {
                            $currentDate = new DateTime();
                            $interval = $currentDate->diff($dueDate);
                            return $interval->format('%a'); // Return the remaining days as a string
                        }

                        // Example usage: Calculate remaining days until a specific due date


                        // echo "Current Date: " . date("Y-m-d") . "\n";
                        // echo "Due Date: " . $dueDateString . "\n";
                        // echo "Remaining Days: " . $remainingDays . "\n";

                            foreach ($result as $row) {
                              $years = 0;
                        $months = $row->month;
                        $dueDate = calculateDueDate($years, $months);
                        $due=$dueDate->format("Y-m-d");
                        $expire=$dueDate->format("Y-m-d");
                        $dueDateString = $due;
                        $dueDate = new DateTime($dueDateString);
                        $remainingDays = calculateRemainingDays($dueDate);
                        if ($remainingDays<1 && $row->status=='not') {
                            $stutus='Expired, (uncompleted';
                        }
                        if ($remainingDays<1 && $row->status=='yes') {
                            $stutus='Expired || completed';
                        }
                        if ($remainingDays>1 && $row->status=='not') {
                            $stutus='In process || incomplete';
                        }

                     // $stutus=$stutus=='not'?'Pending':'expired';
                       echo "<tr>
                       <td>$n</td>
                       <td>$row->task</td>
                    <td>$row->description</td>
                    <td>".$remainingDays."</td>
                    <td>".$expire."</td>
                    <td>$stutus</td>
                      </tr>";

                    }

                   ?>
                 
                </tbody>
              </table>
            </div>
        </div>
    </div>
    </div>         
    
</div>
     <!-- /.col -->
 </div>
 </div>