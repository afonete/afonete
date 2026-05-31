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
if($user->referee_id)
{
     $result=db::SELECT("SELECT * from users where referee_id= :id",['id'=>$sponsored]);
     foreach ($result as $row){
     $sponsored=$row->user;
}}
?>

 <div class="wrapper">
    <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="content-wrapper">
        <section class="content">
           <div class="container-fluid">
                 <div class="row">
                       <div class="col-12 col-sm-6 col-md-12">
                        <h1 class="page-h-content">My History</h1>
                         <div class="hist_table_cont mt-5">
                          <div>
                            <!--<p class="text-left font-weight-bold">TEAM MEMBER</p>-->
                            <?php
                                      $result=db::SELECT("SELECT * from activations where email= :email",['email'=>$email]);
                                      if (count($result)<1)
                                      {

                                  ?>
                                     <span class=" text-center  font-weight-bold bg-danger">You have activated <u><?php echo e(strtoupper($activated)); ?></u> package please, please Upgrade to get more earning</span>

                          <?php
                              }
                          if (count($result)>0){
                       ?>

                        <?php if($activated=='ft'): ?>
                           <span class="bg-secondary text-center  font-weight-bold py-1 rounded">You have activated <u><?php echo e(strtoupper($activated)); ?></u> package please, please complete tasks for more erning</span>

                          <?php else: ?>
                              <span class="bg-success text-center  font-weight-bold py-1 rounded"> You have activated <u><?php echo e(strtoupper($activated)); ?></u> package please, please Upgrade to get more earning</span>
                        <?php endif; ?>

          <?php
               }



                  ?>

                          </div>
                          <?php
                                    $package="Standard";
        $amount="0";
        $data = $user->have_activation_code;
                          ?>
                          <table class="table table-striped  hist_table" style="text-align: center;">
                              <thead>
                                  <tr>
                                      <th scope="col" class="th">

                                           Package

                                      </th>

                                      <th scope="col">
                                          ACTIVATION
                                      </th>
                                      <th scope="col">
                                          ACTIVATED AT
                                      </th>

                                      <th scope="col">
                                          PRICE
                                      </th>
                                      <th scope="col">
                                        TOKEN
                                        </th>
                                      <th scope="col">
                                          STATUS
                                      </th>
                                      <?php if($data->package == 'TM' || $date->package == 'tm'): ?>
                                        <th>CREDIT</th>
                                      <?php endif; ?>

                                       <th scope="col">
                                          Sponsored
                                      </th>

                                  </tr>
                              </thead>

                              <tbody>

                               <?php


                               if($data)
                               {
                            //        $code="none";
                            //        $date=$created;
                            //        $status="none";


                                   ?>

                                    <tr scope="row">
                                  <td>
                                    <?php echo e($data->package); ?>

                                  </td>

                                  <td>
                                    <?php echo e($data->code); ?>

                                  </td>
                                  <td>
                                    <?php echo e($data->created_at); ?>

                                  </td>
                                  <td>
                                    $<?php echo e($data->price); ?>

                                  </td>
                                  <td>
                                    <?php echo e($data->token); ?>

                                  </td>
                                  <td>
                                    <?php echo e($data->stutus); ?>

                                  </td>
                                  <?php if($data->package == 'TM' || $date->package == 'tm'): ?>
                                        <td>
                                           $<?php echo e($data->myCredit->amount); ?>

                                        </td>
                                      <?php endif; ?>
                                   <td>

                                    System
                                  </td>

                                </tr>


<?php
                               }
?>

                              </tbody>
                          </table>

                           <!-- <table class="responsive-table" style="text-align: center;"><br><br>

          <!--     <thead>
                <tr>
                    <th>#</th>Z
                    <th><?php if(count($result)<1): ?>

                      My referral code

                      <?php else: ?>
                      My code
                      <?php endif; ?>
                    </th>
                    <th>Package </th>
                    <th>Amount ($)</th>
                    <th>Status</th>
                    <th>date_created</th>
                    <th>Sponsored_by</th>

                </tr>
              </thead>
              <tbody> -->

          <script type="text/javascript">

                                    function getreferral() {
                                    var codeinput = document.getElementById('ref');
                                        var newcode=codeinput.value;
                                        var tempInput = document.createElement("input");
                                        tempInput.value = newcode;
                                         document.body.appendChild(tempInput);
                                       tempInput.select();
                                document.execCommand("copy");
                                document.body.removeChild(tempInput);
                                alert('activation code copied to clickboard ');
                                    }
                                    var copybtn = document.getElementById('btn2');
                                    copybtn.addEventListener("click", getreferral);
                                   </script>
             <?php
            $n=1;


          //$am=$activated=='ft'?100:0;

              // $result=db::SELECT("SELECT * from activations where email= :email",['email'=>$email]);
              // if (count($result)<1)
              //  {
          //$am=$activated=='ft'?100:0;
          //  echo "<tr>
          //        <td>$n</td>
          //        <td>
          //        $user->activation&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' id='btn2'><img src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRzE_rtXUJxUI4Jc39QBdv_JQOsxx3v-7EBw6jluVorMbOE2wfESnIa&usqp=CAE&s'
          // style='width:25px;height:35px;'></a></td>
          //     <td><b> <input type='hidden' value='$user->activation' id='ref'>".strtoupper($activated)."</b></td>
          //     <td>$am</td>
          //     <td>active</td>
          //     <td>$created</td>
          //     <td>$sponsored</td>
          //       </tr>";
          //         echo "<tr>
          //        <td>$n</td>
          //        <td>
          //        $user->activation</td>
          //     <td><b>".strtoupper($activated)."</b></td>
          //     <td>0</td>
          //     <td>active</td>
          //     <td>$created</td>
          //     <td>$sponsored</td>
          //       </tr>";
          // }


            //   foreach ($result as $row) {
            //   switch ($row->package) {
            //       case 'fc1':
            //           $package="FC";
            //           $amount=100;
            //           break;
            //             case 'fc2':
            //           $package="FC";
            //           $amount=200;
            //           break;
            //         case 'ft':
            //           $package="FT";
            //           $amount=100;

            //           break;
            //       default:
            //           $package="No package";
            //           break;
            //   }
            //   $stutus=$row->stutus;
            //     $stutus=$stutus=='not'?'Pending':'used';
            //      echo "<tr>
            //      <td>$n</td>
            //      <td>$row->code</td>
            //   <td>$package</td>
            //   <td>$amount</td>
            //   <td>$stutus</td>
            //   <td>$row->created_at</td>
            //   <td>$sponsored</td>
            //     </tr>";
            //   }

            //  ?>


                       </div>
                       <!-- /.col -->
                   </div>
            </div>
           </div>
        </section>

      </div>
    </div>
<?php /**PATH /home/cwkwkueb/mcu.focoin.eu/resources/views/user/package-history.blade.php ENDPATH**/ ?>