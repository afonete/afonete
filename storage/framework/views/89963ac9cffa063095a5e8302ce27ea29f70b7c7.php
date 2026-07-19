 <?php

    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;

    $user = Auth::user();
    $name = $user->name;
    $ref_code = $user->activation;

  function generateActivationCode($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';

    for ($i = 0; $i < $length; $i++) {
        $randomIndex = mt_rand(0, strlen($characters) - 1);
        $code .= $characters[$randomIndex];
    }

    return $code;
}
    ?>



 <div>
     <?php echo $__env->make('admin.admin-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
     <style>
     .cc .col {
         background-color: #fff5aa;
         border-radius:12px;
         /* Add desired background color */
         border: 1px solid #ccc;
         /* Add desired border style */
         display: flex;
         width: 12px;
         border: 4px solid white;
         height: 100px;
         text-align: center;
         left: 40px;
         padding-top: 30px;
         padding-left: 25px;
     }

     #founder {
         background-color: lightgreen;
         border-raius: 1px solid #ccc;
         display: flex;
         width: 80px;
         height: 100px;
         text-align: center;
         left: 80px;
         padding-top: 30px;
         padding-left: 45px;
     }

     .coming .info-box {
         display: flex;
         width: 100%;
         height: 100%;
         justify-content: center;
         text-align: center;
         float: right;
         padding-top: 5px;
     }


     @media (max-width: 768px),
     @media  screen and (min-width: 768px) {
         .cc .col {
             /*  border: 1px solid #ccc; /* Add desired border style */
             */ background-color: red;
             width: 100%;
             flex: 0 0 60%;
             border: 4px solid white;
              height: 100px;
             text-align: center;
             left: 40px;
             padding-top: 30px;
             padding-left: 25px;
         }

         #hide {
             display: none;
         }
     }


     .coming {
         display: flex;
         width: 100%;
         height: 120px;
         justify-content: center;
     }
     .button-7 {
            background-color: #0095ff;
            border: 1px solid transparent;
            border-radius: 3px;
            box-shadow: rgba(255, 255, 255, .4) 0 1px 0 0 inset;
            box-sizing: border-box;
            color: #fff;
            cursor: pointer;
            display: inline-block;
            font-family: -apple-system,system-ui,"Segoe UI","Liberation Sans",sans-serif;
            font-size: 13px;
            font-weight: 400;
            line-height: 1.15385;
            margin: 0;
            outline: none;

            position: relative;
            text-align: center;
            text-decoration: none;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            vertical-align: baseline;
            white-space: nowrap;
            align-self: center;

            padding: 8px .8em;

}

.button-7:hover,
.button-7:focus {
  background-color: #07c;
}

.button-7:focus {
  box-shadow: 0 0 0 4px rgba(0, 149, 255, .15);
}

.button-7:active {
  background-color: #0064bd;
  box-shadow: none;
}


.dropdown-container {
            position: relative;
            display: inline-block;
        }

        .dropdown-menus {
            /* display: none; */
            position: absolute;
            top: -40; /* Position it below the icon */
            left: -150px; /* Align it to the right of the icon */
            background-color: white;
            min-width: 150px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 10px 0;
            border-radius: 4px;
            z-index: 1;
        }
        .links{
            display: block;
        }

        .dropdown-menu a {
            padding: 10px 20px;
            display: block;
            text-decoration: none;
            color: black;
        }

        .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }



     </style>
     <!-- Content Wrapper. Contains page content -->

     <div class="content-wrapper right-side" style="width:80%">


            <?php $results=DB::select("SELECT * from users where utype != 'ADM' and email_verified_at IS NOT NULL");
             $verified=count($results);
              $result=DB::select("SELECT * from users where utype != 'ADM' and email_verified_at IS NULL");
             $unverified=count($result);

             $f2=DB::select("SELECT * from users where utype != 'ADM' and email_verified_at IS NOT NULL and has_paid_package='fc2'");
             $fc2=count($f2);
              $f1=DB::select("SELECT * from users where utype != 'ADM' and email_verified_at IS  NOT NULL and has_paid_package='fc1'");
             $fc1=count($result);
                   $std=DB::select("SELECT * from users where utype != 'ADM' and email_verified_at IS NOT NULL and has_paid_package='standard'");

             $sta=count($std);
               $fc1=count($result);
                   $ft=DB::select("SELECT * from users where utype != 'ADM' and email_verified_at IS NOT NULL and has_paid_package='ft'");
             $ftt=count($ft);
             ?>

         <div class="ad-main-box mt-3 mb-3">
             <!-- <div class="" style="background-color: white;" id="hide"></div> -->
             <div class="ad-sub-box1 ">
                    <div class="sub-box-1">
                        <div class="ad-box bg-info " id="not"><a class="nav-icon fas fa-window-restore" href="#"><br>Verified <br> user<br> <?php echo e($verified); ?></a></div>

                        <div class="ad-box bg-success "> <a href="<?php echo e(route('admin.pending')); ?>" class="nav-icon fas fa-window-restore" ><br>Pending <br> user<br><span class="text-white"><?php echo e($unverified); ?></span></a></div>
                    </div>
                    <div class="sub-box-1">
                        <div class="ad-box bg-primary"><a class="nav-icon fas fa-wallet" href="#"><br>Projects<br>0:0</a>  </div>
                        <div class="ad-box bg-danger"><a class="nav-icon fas fa-bookmark" href="<?php echo e(route('admin.fc1')); ?>"> <br>FC $100 <br><?php echo e($fc1); ?></a>  </div>
                    </div>

             </div>

             <div class="ad-sub-box2">
                <div  class="sub-box-1">
                    <div class="ad-box bg-warning mr-2 ml-1"><a class="nav-icon fas fa-money" href="<?php echo e(route('admin.fc2')); ?>"> <br>FC $200<br /><?php echo e($fc2); ?></a></div>
                   <div class="ad-box bg-info mr-2  ml-1"><a class="nav-icon fas fa-gift" href='#'> <br>Standard <br><?php echo e($sta); ?></a></div>
                </div>
                <div class="sub-box-1">
                     <div class="ad-box bg-success col"><a class="nav-icon fas fa-gift" href="<?php echo e(route('admin.ft')); ?>"> <br>FT worker <br><?php echo e($ftt); ?></a></div><br>
                </div>


             </div>


         </div>


         <!-- Content Wrapper. Contains page content -->
         <?php $results=DB::select("SELECT * from users where utype != 'ADM' and email_verified_at IS NOT NULL");
         $verified=count($results);
          $result=DB::select("SELECT * from users where utype != 'ADM' and email_verified_at IS NULL");
         $unverified=count($result);
         ?>

<div class="card direct-chat direct-chat-primary">
    <div class="card-header">

        <!-- /.card-header -->
        <button class="btn btn-primary" style="float: right;" onclick="history.back()"><i class="las la-arrow-circle-left"></i> back</button>


        <div class="row">
           <?php if(request()->query("type") != 'TM'): ?>
           <div class="col mr-1 border rounded">
            <div class="">
                <div class="sub-ft-active d-flex flex-column py-2">
                    <h3 class="ft-title" >Manage <small><strong>FT</strong></small> USER

                        <?php if(request()->query('update_act')): ?>
                            UPDATE
                        <?php endif; ?>
                    </h3>
                    <center>
                        <?php if(session('success')): ?>
                            <div class="alert alert-success w-75 mx-auto">
                                <?php echo e(session('success')); ?>

                            </div>
                        <?php endif; ?>

                   <form method="POST" action="<?php echo e(request()->query('update_act') ? route('admin.ft.update', request()->query('update_act')) : route('admin.ft.save')); ?>" class="form">
                        <?php echo csrf_field(); ?>
                        <?php if(request()->query('update_act')): ?>
                            <?php echo method_field('PUT'); ?>
                        <?php endif; ?>

                             <input type="hidden" name="act_type" value="FT"/>

                                <div>
                                    <?php if(!request()->query('update_act')): ?>

                                    <div>

                                            <a href="?req=gen" class="btn btn-secondary btn-sm gnl-btn"> Generate FT activation code</a>

                                    </div>
                                    <?php endif; ?>
                                    <div>
                                        <?php if(request()->query('req')): ?>

                                            <div class="d-block w-75 mx-auto text-left">
                                                <div>
                                                    <label class="text-info">New generated key: </label>
                                                     <input class="form-control" type="text" name="code" value="<?php echo e(generateActivationCode(20)); ?>">
                                                </div>


                                                 <div class="row">
                                                            <div class="col">
                                                                <label class="text-info">Price($): </label>
                                                                <input class="form-control" type="number" step="0.01" name="price" placeholder="Enter Activation Price....">
                                                            </div>
                                                            <div class="col">
                                                                <label class="text-info">Token: </label>
                                                                <input class="form-control" type="number" name="locked_token" step="0.01" placeholder="Enter Activation Token...">


                                                            </div>
                                                </div>

                                                <div>
                                                    <label class="text-info">General Task: </label>
                                                    <input class="form-control" type="text" name="task" placeholder="Enter General Task...">

                                                </div>


                                                <div class="my-1">
                                                     <button class="btn btn-success btn-sm w-full" type="submit">Save</button><br>
                                                </div>

                                            </div>


                                        <?php endif; ?>
                                        <?php if(request()->query('update_act')): ?>
                                        <?php
                                           $act_id = request()->query('update_act');

                                            $act=DB::select("SELECT * from activations where id='$act_id'");
                                              $act = $act[0];
                                        ?>

                                        <div class="d-block saveKey text-left">
                                            <div>
                                                <label class="text-info">Activation code: </label>
                                                 <input class="form-control" type="text" name="code" value="<?php echo e($act->code); ?>" readonly>
                                            </div>
                                            <div>
                                                <label class="text-info">Price($): </label>
                                                <input class="form-control" type="number" step="0.01" name="price"
                                                 placeholder="Enter Activation Price...."
                                                 value="<?php echo e($act->price); ?>"
                                                 >
                                           </div>

                                           <div>
                                            <label class="text-info">Token: </label>
                                            <input class="form-control" type="number" name="locked_token" step="0.01"
                                            placeholder="Enter Activation Token..."
                                            value="<?php echo e($act->token); ?>"
                                            >
                                          </div>

                                          <div>
                                            <label class="text-info">General Task: </label>
                                            <input class="form-control" type="text" name="task"
                                            placeholder="Enter General Task..."
                                            value="<?php echo e($act->task); ?>"
                                            >
                                          </div>
                                            <div class="my-1">
                                                 <button class="btn btn-success btn-sm w-full" type="submit">
                                                    <?php if(request()->query('update_act')): ?>
                                                            UPDATE
                                                        <?php else: ?>
                                                        Save
                                                        <?php endif; ?>
                                                </button><br>
                                            </div>

                                        </div>


                                    <?php endif; ?>

                                </div>
                                </div>
                                 <span class="text-success" style="margin-left:830px;"><?php echo e($message??''); ?></span>
                            </form>
                            </center>
                </div>

            </div>


           </div>

           <?php endif; ?>
           <?php if(request()->query('type') != 'FT'): ?>
                <div class="col-md-6 border rounded mx-auto">
                    <div class="">
                        <div class="sub-ft-active d-flex flex-column py-2">
                            <h3 class="ft-title" >
                            <?php if(request()->query('update_act')): ?>
                                UPDATE
                            <?php else: ?>
                              MANAGE
                            <?php endif; ?> <small><strong>TM</strong></small> USER


                            </h3>
                            <center>
                                <?php if(session('success')): ?>
                                    <div class="alert alert-success w-75 mx-auto">
                                        <?php echo e(session('success')); ?>

                                    </div>
                                <?php endif; ?>

                                <?php if(session('message')): ?>
                                    <div class="alert alert-success w-75 mx-auto">
                                        <?php echo e(session('message')); ?>

                                    </div>
                                <?php endif; ?>

                        <form method="POST" action="<?php echo e(request()->query('update_act') ? route('admin.ft.update', request()->query('update_act')) : route('admin.ft.save')); ?>" class="form">
                                <?php echo csrf_field(); ?>
                                <?php if(request()->query('update_act')): ?>
                                    <?php echo method_field('PUT'); ?>
                                <?php endif; ?>
                                        <div>
                                            <?php if(!request()->query('update_act')): ?>

                                            <div>
                                                <a href="?req=gen" class="btn btn-secondary btn-sm gnl-btn"> Generate TM activation code</a>

                                            </div>
                                            <?php endif; ?>
                                            <div>
                                                <input type="hidden" name="act_type" value="TM"/>
                                                <?php if(request()->query('req')): ?>

                                                    <div class="d-block  mx-2 text-left">


                                                        <div class="row">
                                                            <div class="col">
                                                                <label class="text-info">New Gen Key: </label>
                                                                <input class="form-control" type="text" name="code" value="<?php echo e(generateActivationCode(20)); ?>">
                                                            </div>
                                                            <div class="col">
                                                                <label class="text-info">Price($): </label>
                                                                <input class="form-control" type="number" step="0.01"
                                                                 name="price" placeholder="Enter Activation Price....">
                                                        </div>
                                                        </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <label class="text-info">Token: </label>
                                                        <input class="form-control" type="number" name="locked_token" step="0.01" placeholder="Enter Activation Token...">
                                                    </div>
                                                    <div class="col">
                                                        <label class="text-info">Credit: </label>
                                                        <input class="form-control" type="number" name="credit"
                                                        step="0.01" placeholder="Enter Credit..">
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="col">
                                                        <label class="text-info">General Task</label>
                                                        <input class="form-control" type="text" name="task" required placeholder="Enter General Task...">
                                                    </div>
                                                    <div class="col">
                                                        <label class="text-info">Percentage: </label>
                                                        <input class="form-control" type="number" name="percentage"
                                                        step="0.01" placeholder="Enter Percentage.." required>
                                                    </div>

                                                    <div class="col">
                                                        <label class="text-info">Period: </label>
                                                        <input class="form-control" type="text" name="period"
                                                        step="0.01" placeholder="Enter Period.." required>
                                                    </div>


                                                    <div class="col">
                                                        <label class="text-info">Withdraw Max: </label>
                                                        <input class="form-control" type="number" name="withdrawmax"
                                                        step="0.01" placeholder="Enter Withdraw.." required>
                                                    </div>

                                                </div>


                                                        <div class="my-1">
                                                            <button class="btn btn-success btn-sm w-full" type="submit">Save</button><br>
                                                        </div>

                                                    </div>


                                                <?php endif; ?>
                                                <?php if(request()->query('update_act')): ?>
                                                <?php
                                                $act_id = request()->query('update_act');
                                                $act = $tms->find($act_id);

                                                ?>

                                                <div class="d-block w-75 mx-auto text-left">
                                                    <div class="row">
                                                        <div class="col">
                                                            <label class="text-info">Activation code: </label>
                                                            <input class="form-control" type="text" name="code" value="<?php echo e($act->code); ?>" readonly>
                                                        </div>
                                                        <div class="col">
                                                            <label class="text-info">Price($): </label>
                                                            <input class="form-control" type="number" step="0.01" name="price"
                                                            placeholder="Enter Activation Price...."
                                                            value="<?php echo e($act->price); ?>"
                                                            >
                                                        </div>
                                                    </div>



                                                <div class="row">
                                                    <div class="col">
                                                        <label class="text-info">Token: </label>
                                                        <input class="form-control" type="number" name="locked_token" step="0.01"
                                                        placeholder="Enter Activation Token..."
                                                        value="<?php echo e($act->token); ?>"
                                                        >

                                                    </div>
                                                    <div class="col">
                                                        <label class="text-info">Credit: </label>
                                                    <input class="form-control" type="number" name="credit"
                                                    step="0.01" placeholder="Enter Credit.." value="<?php echo e($act->myCredit->amount); ?>">
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    
                                                    <div class="col">
                                                        <label class="text-info">Percentage: </label>
                                                        <input class="form-control" type="number" name="percentage"
                                                        step="0.01" placeholder="Enter Percentage.." required
                                                        value="<?php echo e($act->percentage); ?>"
                                                        >
                                                    </div>

                                                    <div class="col">
                                                        <label class="text-info">Period: </label>
                                                        <input class="form-control" type="text" name="period"
                                                        step="0.01" placeholder="Enter Period.." required
                                                        value="<?php echo e($act->period); ?>"
                                                        >
                                                    </div>

                                                    <div class="col">
                                                        <label class="text-info">Count Down Period: </label>
                                                        <input class="form-control" type="text" name="countdown"
                                                        step="" placeholder="Enter Count down Period.."
                                                        value="<?php echo e($act->countdown); ?>"
                                                        >
                                                    </div>


                                                    

                                                </div>
                                                    <div class="my-1">
                                                        <button class="btn btn-success btn-sm w-full" type="submit">
                                                            <?php if(request()->query('update_act')): ?>
                                                                    UPDATE
                                                                <?php else: ?>
                                                                Save
                                                                <?php endif; ?>
                                                        </button><br>
                                                    </div>

                                                </div>


                                            <?php endif; ?>

                                        </div>
                                        </div>
                                        <span class="text-success" style="margin-left:830px;"><?php echo e($message??''); ?></span>
                        </form>
                    </center>
                        </div>

                    </div>
                </div>

           <?php endif; ?>


           


           <?php if(request()->query('type') != 'FT' && request()->query('update_act')): ?>
           <div class="col-md-6 border rounded mx-auto">
               <div class="">
                   <div class="sub-ft-active d-flex flex-column py-2">
                       <h3 class="ft-title" >
                        TASK <small>UPDATE</small> TM

                       </h3>

                           <?php if(session('success')): ?>
                               <div class="alert alert-success w-75 mx-auto">
                                   <?php echo e(session('success')); ?>

                               </div>
                           <?php endif; ?>

                           <?php if(session('message')): ?>
                               <div class="alert alert-success w-75 mx-auto">
                                   <?php echo e(session('message')); ?>

                               </div>
                           <?php endif; ?>
                           <?php
                            //    dd();
                           ?>
                               <form action="<?php echo e(route("updateFT",$act->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field("PUT"); ?>

                                <div class="row">

                                    <div class="col">
                                        <label class="text-info">Sales Turnover</label>
                                        <input class="form-control" type="text" name="sales"
                                        value="<?php echo e($act->turnover); ?>" required placeholder="Enter Sales Turnover...">
                                    </div>

                                    <div class="col">
                                        <label class="text-info">Period: </label>
                                        <input class="form-control" type="text" name="period"
                                         placeholder="Enter Period.." required value="<?php echo e($act->task_period); ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label class="text-info">Activities Or Exception Task: </label>
                                        <input class="form-control" type="text" name="task" required placeholder="Enter Task..."
                                        value="<?php echo e($act->exceptionalTask); ?>">

                                    </div>
                                    <div class="col">
                                        <label class="text-info">View: </label>
                                        <input class="form-control" type="text" name="view"
                                        step="0.01" placeholder="Enter View.." required value="<?php echo e($act->view); ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <label class="text-info">Cashout Max: </label>
                                        <input class="form-control" type="text" name="withdrawmax" required
                                        placeholder="Enter Withdrawmax..." value="<?php echo e($act->withdrawmax); ?>">
                                    </div>
                                </div>

                                <div class="my-1">
                                    <button class="btn btn-success btn-sm w-full" type="submit">Save</button><br>
                               </div>
                               </form>

               </center>
                   </div>

               </div>
           </div>

      <?php endif; ?>

        </div>

    <div class="row container-fluit">
        <!--<div class="col-md-6">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="bg-gradient-primary ">
                        <tr>
                            <th>#</th>
                            <th>Username</th>

                            <th>Code</th>

                            <th>Task</th>

                            <th>Date_created</th>
                        </tr>
                    </thead>
                    <?php
                    $results=DB::select("SELECT * from users where has_paid_package='ft'");
                    $n=1;
                    foreach ($results as $row):
                    // dd($row);
                    ?>

                    <tbody>
                        <tr>
                            <td><?php echo e($n++); ?></td>
                            <td><?php echo e($row->user); ?></td>
                            <td><?php echo e($row->activation); ?></td>
                            <td><?php echo e($row->task); ?></td>
                            <td><?php echo e($row->created_at); ?></td>
                        </tr>
                    </tbody>
                    <?php endforeach;  ?>
                </table>
            </div>


        </div>-->

        <div class="col">
             <div class="">
                <?php



                 ?>
                 <div class="table-responsive">
                    <table class="table table-striped">

                        <thead class="bg-gradient-primary ">
                            <tr>
                                <th>#</th>

                                <th>Activations</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th>Price</th>
                                <th>Token</th>
                                <th>Task</th>

                                <th>Date_created</th>
                                <th>User Details</th>
                                <th>Reffered By</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <?php
                        $n = 1;
                        foreach ($FT as $row):

                                ?>


                            <tbody>
                                <tr>
                                    <td><?php echo e($n++); ?></td>
                                    
                                    <td>
                                        <div class="d-flex align-items-center ">
                                            <a href="#?code=<?php echo e($row->code); ?>" class="copy-code mx-1"><?php echo e($row->code); ?></a>
                                        <button onclick="copyToClipboard(this)">copy</button>
                                        </div>
                                    </td>
                                    <td><?php echo e($row->package); ?></td>

                                    <td><?php echo e($row->stutus); ?></td>
                                    <td><?php echo e($row->price); ?></td>
                                    <td><?php echo e($row->token); ?></td>
                                    <td><?php echo e($row->task); ?></td>
                                    <td><?php echo e($row->created_at); ?></td>
                                    <td>
                                        <?php if($row->myOwner): ?>
                                        <p> Username: <?php echo e($row->myOwner->name); ?></p>
                                        <p> Email: <?php echo e($row->myOwner->email); ?></p>
                                        <p> Phone: <?php echo e($row->myOwner->phone); ?></p>
                                        <?php else: ?>
                                        Not Used
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        soon
                                    </td>

                                    <td>
                                    <?php if($row->stutus == 'used'): ?>

                                            <a href="?update_act=<?php echo e($row->id); ?>&type=FT" class="button-7 d-inline btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                    <?php else: ?>

                                            <form class="d-flex" action="<?php echo e(route("admin.ft.delete",$row->id)); ?>" method="POST">
                                                <?php echo method_field("DELETE"); ?>
                                                <?php echo csrf_field(); ?>

                                                <a href="?update_act=<?php echo e($row->id); ?>&type=FT" class="btn btn-primary">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <input type="hidden" name="act_id" value="<?php echo e($row->id); ?>">
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                    <?php endif; ?>
                                    </td>

                                </tr>
                            </tbody>
                        <?php endforeach; ?>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">

                        <thead class="bg-gradient-primary ">

                            <tr>
                                <th>#</th>
                                <th>Activations</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th>Price</th>
                                <th>Token</th>
                                <th>Task</th>
                                <th>Percentage</th>
                                <th>Period</th>
                                <th>Withdraw</th>
                                <th>Date_created</th>
                                <th colspan="2" class="text-center">Credits</th>

                                <th>Withdraw</th>
                                <th>UserDetails</th>
                                <th>Reffered By</th>

                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            // $activation=DB::select("SELECT * from activations where package='tm' ");
                        $n = 1;
                        foreach ($tms as $row):


                                ?>




                                <tr>
                                    <td><?php echo e($n++); ?></td>
                                    
                                    <td>
                                        <div class="d-flex align-items-center ">
                                            <a href="#?code=<?php echo e($row->code); ?>" class="copy-code mx-1"><?php echo e($row->code); ?></a>
                                        <button onclick="copyToClipboard(this)">copy</button>
                                        </div>
                                    </td>
                                    <td><?php echo e($row->package); ?></td>
                                    <td><?php echo e($row->stutus); ?></td>
                                    <td><?php echo e($row->price); ?></td>
                                    <td><?php echo e($row->token); ?></td>
                                    <td><?php echo e($row->task); ?></td>
                                    <td><?php echo e($row->percentage); ?>%</td>
                                    <td><?php echo e($row->period); ?></td>
                                    <td><?php echo e($row->withdrawmax); ?></td>
                                    <td><?php echo e($row->created_at); ?></td>
                                    <td><?php echo e($row->myCredit->amount); ?>

                                        <?php
                                                $status = $row->myCredit->status;
                                                $badgeClass = match($status) {
                                                    'approved' => 'badge-primary',
                                                    'rejected' => 'badge-danger',
                                                    default => 'badge-warning',
                                                };
                                         ?>

                                            <span class="badge <?php echo e($badgeClass); ?>">
                                                <?php echo e($status); ?>

                                            </span>

                                    </td>
                                    <td class="d-flex justify-space-around">
                                        
                                        <?php if($status != 'approved'): ?>
                                                <form  action="<?php echo e(route("tm.credit.approve",$row->myCredit->id)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>

                                                            <input type="hidden" name="act_id" value="<?php echo e($row->myCredit->id); ?>">
                                                            <button type="submit" class="button-7">
                                                                Approve
                                                            </button>
                                                            
                                                </form>
                                        <?php endif; ?>

                                        <?php if($status != 'rejected'): ?>
                                        <form  action="<?php echo e(route("tm.credit.reject",$row->myCredit->id)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>



                                                    <input type="hidden" name="act_id" value="<?php echo e($row->myCredit->id); ?>">
                                                    <button type="submit" style="margin: 0 1px" class="button-7 bg-warning">
                                                        Reject
                                                    </button>

                                        </form>
                                        <?php endif; ?>


                                        <?php if($status != 'inactive'): ?>
                                        <form  action="<?php echo e(route("tm.credit.reactivate",$row->myCredit->id)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>

                                                    <input type="hidden" name="act_id" value="<?php echo e($row->myCredit->id); ?>">
                                                    <button type="submit" class="button-7 bg-danger ml-1">
                                                        Reactivate
                                                    </button>
                                                    
                                        </form>
                                        <?php endif; ?>




                                    </td>
                                    <td>Soon</td>
                                    <td>
                                        <?php if($row->myOwner): ?>
                                        <p> Username: <?php echo e($row->myOwner->name); ?></p>
                                        <p> Email: <?php echo e($row->myOwner->email); ?></p>
                                        <p> Phone: <?php echo e($row->myOwner->phone); ?></p>
                                        <?php else: ?>
                                        Not Used
                                        <?php endif; ?>
                                    </td>
                                    <td>soon</td>

                                    <td >
                                    <?php if($row->stutus == 'used'): ?>

                                            <a href="?update_act=<?php echo e($row->id); ?>&type=TM" class="button-7 d-inline btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                    <?php else: ?>

                                            <form class="d-flex" action="<?php echo e(route("admin.ft.delete",$row->id)); ?>" method="POST">
                                                <?php echo method_field("DELETE"); ?>
                                                <?php echo csrf_field(); ?>

                                                    <a href="?update_act=<?php echo e($row->id); ?>&type=TM" class="btn btn-primary btn-sm gnl-btn">
                                                        <i class="fa fa-edit"></i>
                                                    </a>


                                                        <input type="hidden" name="act_id" value="<?php echo e($row->id); ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm gnl-btn">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>



                                    <?php endif; ?>
                                    </td>


                                </tr>


                        <?php endforeach; ?>


                        </tbody>

                    </table>
                </div>

        </div>
</div>
<script>
function copyToClipboard(button) {
    // Get the text to copy (in this case, the value of the sibling anchor tag)
    var textToCopy = button.previousElementSibling.textContent;

    // Create a temporary input element to copy the text
    var tempInput = document.createElement("input");
    tempInput.value = textToCopy;
    document.body.appendChild(tempInput);

    // Select the text in the temporary input
    tempInput.select();
    tempInput.setSelectionRange(0, 99999); // For mobile devices

    // Copy the selected text to the clipboard
    document.execCommand("copy");

    // Remove the temporary input
    document.body.removeChild(tempInput);

    // Optionally, show a notification or do any other actions after copying
    alert("Activation Copied : " + textToCopy);
}



$(document).ready(function () {
        // Toggle the dropdown menu on click
        $('.button').click(function (e) {
            e.stopPropagation();
            alert("cool")
            $('.dropdown-menus').toggle();
        });

        // Close the dropdown menu when clicking outside
        $(document).click(function () {
            $('.dropdown-menus').hide();
        });

        // Prevent dropdown menu from closing when clicking inside
        $('.dropdown-menu').click(function (e) {
            e.stopPropagation();
        });
});



</script>


    </div>

</div>
</div>
</div>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/ft-manage.blade.php ENDPATH**/ ?>