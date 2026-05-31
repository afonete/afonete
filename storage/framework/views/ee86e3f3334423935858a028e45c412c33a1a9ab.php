 <?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
// use App\Models\Activations
$user = Auth::user();
$name = $user->user;
$email=$user->email;
$activated=$user->has_paid_package;
$created=$user->created_at;
// $act=find::Activations()
$sponsored=$user->referee_id??'System';
    $ref_code = $user->activation;

?>

 <div class="wrapper">
     <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


              
    <div class="content-wrapper" style="width:80%">   
        <section class="content">
            <div class="container-fluid">
            <?php
                    $result=db::SELECT("SELECT * from activations where email= :email",['email'=>$email]);

                if (count($result)<1)
                 {

             ?>
            <div class="alert alert-primary"> You have activated standard package please, please Upgrade to get more earning <i style="color:#dc3545; font-size: 15px; position: absolute; right:20px;">comming soon</i></div>

             <?php
                 }
                      if (count($result)>0){
                ?>

              <span class="btn btn-success d-flex justify-content-center"> You have activated <?php echo e($activated); ?> package please, please Upgrade to get more earning <i style="color:#dc3545; font-size: 15px; ">comming soon</i></span>
                        
            <?php
                 }
                     
                   
                $results=db::SELECT("SELECT * from users where referee_id= :ref",['ref'=>$ref_code]);
             ?>

            <div class="main-ref mt-5">
                <div class="ref-left">
                    <h4 class="ref-header">My Referrals Link</h4>
                    <div class="ref-data"> 

                        <div class="ref-icon">
                           <i class="las la-link text-white"></i>
                        </div>
                        <div class="ref-info">
                            <div class="ref-info-header">
                                <h4>Your Referral Link</h4>  
                            </div>
                            
                            <div class="ref-info-content">  
                              
                                <input type="hidden" id="link" value="https://fonepo.com/register?referral=<?php echo e($ref_code); ?>">
                              <?php if($activated!='standard'): ?>
                                  <a href="#" style="color: white;text-decoration: none;"  id="btn1"> https://fonepo.com/register?referral=<?php echo e($ref_code); ?></a>
                                  <?php else: ?>
                                  <a href="#" style="color: white;text-decoration: none;"  id="btn1"> https://fonepo.com/register?referral=*******</a>
                                <?php endif; ?>
                              
                            </div>
                        </div>
                       
                                                
                    </div> 
                    <div class="ref-data"> 

                        <div class="ref-icon">
                           <i class="las la-user-friends text-white"></i>
                        </div>
                        <div class="ref-info">
                            <div class="ref-info-header">
                                <h4>Total Referrals</h4>  
                            </div>
                            
                            <div class="ref-info-content">  
                              <?php echo e(count($results)); ?>

                            </div>
                        </div>
                       
                                                
                    </div> 
                    <div class="ref-data"> 

                        <div class="ref-icon">
                           <i class="las la-dollar-sign text-white"></i>
                        </div>
                        <div class="ref-info">
                            <div class="ref-info-header">
                                <h4>Referral Earnings</h4>  
                            </div>
                            
                            <div class="ref-info-content">  
                               0 Coins
                            </div>
                        </div>
                       
                                                
                    </div> 
                    
                    <div class="d-flex pt-3"> 
                        <div class="ml-2">
                           <p>Referral_code:</p>
                        </div>
                        <div class="ml-3">
                            <div class="ref-info-content">  
                               <input type="hidden" value="<?php echo e($ref_code); ?>" id="link1"> 
                                 <?php if($activated!='standard'): ?>
                                <a href="#" style="color:red;text-decoration:none;" id="btn2"> <?php echo e($ref_code); ?></a>
                                <?php else: ?>
                                 <a href="#" style="color:red;text-decoration:none;" id="btn2"> *******</a>
                                <?php endif; ?>
                            </div>
                        </div>                
                    </div> 
                    
                    <div class="pea col-lg-12  md-whiteframe-3dp bg-dark-transparent text-center ">
                           <!--  <table style=" width: 300px;">
                                <tr>
                                    <th>Referral_code: </th>
                                    <td>
                                        <input type="hidden" value="<?php echo e($ref_code); ?>" id="link1"> 
                                        <a href="#" style="color:red;text-decoration:none;" id="btn2"> <?php echo e($ref_code); ?></a>
                                    </td>
                                </tr>
                            </table> -->
                            <script type="text/javascript">
                            
                             function getlink() {//http://localhost:8000/register?referral=<?php echo e($ref_code); ?>

                                 // var linkinput = document.getElementById('link');
                                  var package="<?php echo $activated ?>";
                                             if(package=="standard"){
                                                 alert('Activate package to be allowed for this feature')
                                             }
                                             else{
                                 var linkinput = document.getElementById('link');
                                 var newlink=linkinput.value;
                                 var tempInput = document.createElement("input");
                                 tempInput.value = newlink;
                                  document.body.appendChild(tempInput);
                                    tempInput.select();
                                     document.execCommand("copy");
                                     document.body.removeChild(tempInput);
                                     alert('Referral link copied to clickboard ');
                                         }}
                                         var copybtn = document.getElementById('btn1');
                                         copybtn.addEventListener("click", getlink);

                                          function getreferral() {
                                               var package="<?php echo $activated ?>";
                                             if(package=="standard"){
                                                 alert('Activate package to be allowed for this feature')
                                             }
                                             else{
                                             var codeinput = document.getElementById('link1');
                                             var newcode=codeinput.value;
                                             var tempInput = document.createElement("input");
                                             tempInput.value = newcode;
                                              document.body.appendChild(tempInput);
                                            tempInput.select();
                                     document.execCommand("copy");
                                     document.body.removeChild(tempInput);
                                     alert('Referral code copied to clickboard ');
                                 }}
                                 var copybtn = document.getElementById('btn2');
                                 copybtn.addEventListener("click", getreferral);
                             </script>
                       </div>
                </div>
                 <?php
                       
                $results=db::SELECT("SELECT * from users where referee_id= :ref",['ref'=>$ref_code]);
                if (count($results)) {
                    // code...
                
                 ?>
                <div class="ref-right" style="overflow-x: auto;">
                   
                      <table class="table ref-table">
                        <thead>
                        
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Paid_Package</th>
                             <th>Email_status</th>
                        </tr>
                    </thead>
                    <tbody class="ref-tbody">
                      <?php
                        $a=1;
                        foreach ($results as $row) { 
                        
                        ?>
                          <tr>
                            <td><?php echo e($a++); ?></td>
                            <td><?php echo e($row->user); ?></td>
                            <td>
                                <?php switch($row->has_paid_package):
    case ('standard'): ?>
        No Package
        <?php break; ?>

    <?php case ('fc1'): ?>
        FC $100
        <?php break; ?>

    <?php case ('ft'): ?>
        FT Package
        <?php break; ?>
         <?php case ('fc2'): ?>
      FC $200
        <?php break; ?>

    <?php default: ?>
        no package
<?php endswitch; ?>



</td>
                            <td><?php echo e($row->email_verified_at==NULL?'Not verified':'verified'); ?></td>
                          </tr>
                         <?php    }?>
                        </tbody>
                      </table>
                      <?php 


                        }

                        ?>
                </div>

            </div>
            

            </div>
        </section>
    </div>  

    </div>    
<?php /**PATH /home/cwkwkueb/test.focoin.eu/resources/views/user/referral.blade.php ENDPATH**/ ?>