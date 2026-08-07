<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomeControllers;
use App\Http\Controllers\News;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserPackageController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\WebhookController;
use App\Http\Controllers\TeamLeaderController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\User\Position;
use App\Http\Controllers\User\ActivationController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Users;
use App\Http\Controllers\Admin\TasksController;
use App\Http\Controllers\Admin\manage;
use App\Http\Controllers\Admin\Requested;
use App\Http\Controllers\Fearloss\Payclick;
use App\Http\Controllers\Fearloss\Videos;
// use App\Http\Controllers\Fearloss\Advertisment;
use App\Http\Controllers\User\Project;
use App\Http\Controllers\User\Balance;
use App\Http\Controllers\User\Club;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\User\Team;
use App\Http\Controllers\User\Events;
use App\Http\Controllers\User\VentureController;
use App\Http\Controllers\Admin\AdventureController;
use App\Http\Controllers\CampainController;
use App\Http\Controllers\FCpackageController;
// Your routes here

use App\Http\Controllers\User\FinanceController;
use App\Http\Controllers\Admin\ClaimController;



Route::get('/', function () {
    return view('home.welcome');
})->name('front');


Route::get('/team', function () {
    return view('user.team.teamtest');
});
Route::get('/userdasboard', function () {
    return view('userdashboard.UserDashboard');
})->name('UserDashboard');


Route::get('/reminder', function () {
    return view('reminder');
});

// Route::post('send/email', [AdminController::class, 'send'])->name('send.email');

Route::get('money', [Balance::class, 'money']);

Route::get('guest-request', [HomeController::class, 'requested'])->name('request');
// routes/web.php

  // create ambassador
  Route::get('home/ambassador', function () {
    return view('home.ambassador');
});

  // create ambassador
  Route::get('home/bestreferral', function () {
    return view('home.bestreferral');
});



// end ventures

Route::middleware(['auth'])->group(function () {
    Route::post('email/verify-pin', [\App\Http\Controllers\User\UserPackageController::class, 'verifyEmailPin'])->name('verification.verify-pin');
    Route::post('email/resend-pin', [\App\Http\Controllers\User\UserPackageController::class, 'resendEmailPin'])->name('verification.resend-pin');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Route::get("user/underpayment",function(){
    //     return view("user.callback",["status"=>"success"]);
    // });
    Route::get('user/pay/{id}/pfc', [PaymentController::class, 'fc'])->name('fc');
    Route::get('user/pay/fc2', [PaymentController::class, 'fc2'])->name('fc2');
    Route::get('user/pay/fc1', [PaymentController::class, 'fc1'])->name('fc1');
    Route::post('user/pay/ventures', [PaymentController::class, 'ventures'])->name('ventures');
    Route::get('user/overview', [FinanceController::class, 'overview'])->name('overview');
    Route::get('user/transaction', [FinanceController::class, 'transaction'])->name('transaction');
    Route::post('user/tranfer', [FinanceController::class, 'transfer'])->name('transfer');
    Route::get('user/tranfer/lookup', [FinanceController::class, 'cashoutTransferLookup'])->name('transfer-touser.lookup');
    Route::post('user/tranfer/user', [FinanceController::class, 'transferToUser'])->name('transfer-touser');

    Route::post('user/tranfer/trading', [FinanceController::class, 'transferToTradingAccount'])->name('transfer-totrading');
    Route::get("user/dashboard/finance/account",[FinanceController::class,'getAccountBalance'])->name('account-balance');


    Route::post('user/tranfer/account', [FinanceController::class, 'transferToAccount'])->name('transferToAccount');
    Route::get('user/commission', [FinanceController::class, 'commission'])->name('commission');
    Route::get("user/subscription", [FinanceController::class, 'subscription'])->name('subscription');

    // ventures  

    Route::get('user/venture/contract', [VentureController::class, 'contract'])->name('user.venture.contract');
    Route::post('user/venture/contract', [VentureController::class, 'SaveContract'])->name('user.venture.SaveContract');
    Route::get("user/deposit",[UserPackageController::class, 'deposits'])->name("user.payment.deposits");

    Route::post("user/deposit",[PaymentController::class, 'SaveDeposits'])->name("user.payment.savedeposits");

    Route::post("user/claim",[UserPackageController::class, 'claim'])->name("user.claim");

    Route::get('investment-package/', [HomeController::class, 'investmentPackage'])->name('investment-package');
    Route::get('staker-package/', [HomeController::class, 'stakerPackage'])->name('staker-package');

    Route::post('dashboard', [ActivationController::class, 'g_upgrade'])->name('validate');
    Route::put('user/package/payment/free', [PaymentController::class, 'free'])->name('free');
    Route::get('user/payment-records', [UserDashboardController::class, 'mypayments'])->name('mypayments');
    Route::get('user/teambuilding', [FinanceController::class, 'teambuilding'])->name('teambuilding');
    Route::get('user/team/referral/free', [FinanceController::class, 'freereferrals'])->name('referrals.free');
    Route::get('user/team/referral/paid', [FinanceController::class, 'paidreferrals'])->name('referrals.paid');
    Route::get('user/teambuilding/downline', [FinanceController::class, 'downline'])->name('downline');

    // Package renew
    Route::post('user/package/renew/pay', [FinanceController::class, 'packageRenewPay'])->name('packageRenewPay');
    Route::get("user/package/renew/{payment_id?}",[FinanceController::class,'packageRenewPage'])->name('packageRenew');


    Route::get('user/teambuilding/team-structure', [FinanceController::class, 'teamStructure'])->name('team.structure');
    Route::post('user/teambuilding/add-team-member', [FinanceController::class, 'registerTeamMemberFromDeposit'])->name('user.team.add-member');
    // api
    Route::get('api/getReferralTree/{userId}', [FinanceController::class, 'getTeamTree'])->name('team.structure.tree');
    // 

    Route::get('user/teambuilding/team-genealogy', [FinanceController::class, 'teamGenealogy'])->name('team.genealogy');
    Route::get('user/teambuilding/downline', [FinanceController::class, 'downline'])->name('downline');
    Route::get('user/finance/volume-points', [FinanceController::class, 'volumePoints'])->name('volume.points');
    Route::get('user/teambuilding/team-ranking', [FinanceController::class, 'teamRanking'])->name('team.ranking');
    Route::get('user/teambuilding/teams-groups', [FinanceController::class, 'teamsGroups'])->name('teams.groups');
    Route::get('user/awards/my-awards', [FinanceController::class, 'myAwards'])->name('my.awards');
    Route::get('user/finance/commission', [FinanceController::class, 'commission'])->name('commission');
    Route::get('user/finance/focoin-point', [FinanceController::class, 'focoinPoint'])->name('focoin.point');
    Route::get('user/finance/commission/details', [FinanceController::class, 'commissionDetails'])->name('commission.details');

    Route::get('user/finance/fone-commission', [FinanceController::class, 'foneCommission'])->name('fone.commission');
    Route::get('user/finance/fomo-commission', [FinanceController::class, 'fomoCommission'])->name('fomo.commission');
    Route::get('user/merchant', [FinanceController::class, 'merchant'])->name('merchant');

    Route::get('user/available-packages/',[UserPackageController::class,'buypackages'])->name("user.buypackage");
    Route::get('user/package/payment/status/fc', [PaymentController::class, 'pscallback']);
    Route::get('user/package/payment/error/fc', [PaymentController::class, 'pserror']);
    Route::get('user/package/payment/success/fc', [PaymentController::class, 'pssuccess']);

    // Route::get('user/package/payment/status/venture', [PaymentController::class, 'pscallbackVenture']);
    // Route::get('user/package/payment/error/venture', [PaymentController::class, 'pserrorVenture']);
    Route::get('user/venture/payment/success', [PaymentController::class, 'successVenture']);
    Route::get('user/venture/payment/error', [PaymentController::class, 'ventureError']);
    Route::get('user/venture/payment/status', [PaymentController::class, 'ventureCallback']);



    Route::get('user/venture-package', [UserPackageController::class, 'index'])->name('user.venture');
    Route::get('user/package', [UserPackageController::class, 'UserPackage'])->name('user.package');
    // Route::get('user/package/pay', [PaymentController::class, 'pay'])->name('user.pay')->withoutMiddleware('user-package');
    Route::Post('user/package/payment', [PaymentController::class, 'blockpay'])->name('payment');
    Route::Post('user/venture/payment', [PaymentController::class, 'blockpayventure'])->name('paymentventure');
    Route::Post('user/package/direct-payment', [PaymentController::class, 'directPackagePayment'])->name('payment.directPackage');
    Route::get('user/package/direct-payment/{deposit}', [PaymentController::class, 'showDirectPackagePayment'])->name('payment.directPackage.show');
    Route::get('user/package/direct-payment/{deposit}/status', [PaymentController::class, 'directPackagePaymentStatus'])->name('payment.directPackage.status');
    Route::post('user/package/direct-payment/{deposit}/cancel', [PaymentController::class, 'cancelDirectPackagePayment'])->name('payment.directPackage.cancel');
    Route::get('user/manual-deposit', [PaymentController::class, 'manualDepositPage'])->name('user.manual-deposit');
    Route::post('user/manual-deposit', [PaymentController::class, 'submitManualDeposit'])->name('user.manual-deposit.submit');
    Route::get('user/manual-deposit/{deposit}/waiting', [PaymentController::class, 'manualDepositWaiting'])->name('user.manual-deposit.waiting');
    Route::get('user/manual-deposit/{deposit}/status', [PaymentController::class, 'manualDepositStatus'])->name('user.manual-deposit.status');
    Route::Post('user/venture/paymentFromDeposits', [PaymentController::class, 'paymentFromDeposits'])->name('paymentventuredeposits');
    Route::Post('user/fc/payment', [PaymentController::class, 'PaymentFcFromDeposit'])->name('paymentfc');
    // Route::get('user/payment/error', [PaymentController::class, 'paymentError'])->name('payment-error');

    // Route::get('user/package/pay',[PaymentController::class,'test']);
    Route::get('user/package/unconfirmed', [PaymentController::class, 'unconfirmed']);
});

Route::middleware(['auth:sanctum', 'verified', 'user-package', 'contract','claims'])->group(function () {

    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/fetch-earnings', [UserDashboardController::class, 'fetchCommissions']);

    Route::get('/dashboard/free-account-restricted', [UserDashboardController::class, 'restrictedFreeAccount'])->name('user.dashboard.freeaccount-restricted');
    Route::get('/dashboard/account-upgrade-venture-package', [UserDashboardController::class, 'upgradeVenturePackage'])->name('user.dashboard.upgrade-venture-package');

    Route::get('user/dashboard/activate/', [ActivationController::class, 'index'])->name('user.dashboard.activate');
    // Route::get('user/payment/error', [ActivationController::class, 'index'])->name('user.dashboard.activate');

    Route::get('user/dashboard/balance/', [Balance::class, 'index'])->name('user.dashboard.balance');
    Route::get('user/dashboard/deposit/', [Balance::class, 'deposit'])->name('user.dashboard.deposit');
    Route::post('user/dashboard/deposit/', [Balance::class, 'api'])->name('user.deposit');

    // ── Plisio deposit callback routes (used in Balance.php callbacks) ──
    Route::get('user/dashboard/deposit/status',  [Balance::class, 'status'])->name('user.deposit.status');
    Route::get('user/dashboard/deposit/success', [Balance::class, 'success'])->name('user.deposit.success');
    Route::get('user/dashboard/deposit/fail',    [Balance::class, 'error'])->name('user.deposit.fail');

    // ── User deposit history ──────────────────────────────────────────────
    Route::get('user/deposits',                   [\App\Http\Controllers\User\Balance::class, 'depositHistory'])->name('user.deposits.history');
    Route::post('user/dashboard/wallet/', [Balance::class, 'wallet'])->name('user.wallet');
    Route::get('user/dashboard/wallet/receive', [Balance::class, 'Receive'])->name('user.receive');
    Route::get('user/dashboard/wallet-address/', [Profilecontroller::class, 'wallet'])->name('wallet');

    // User token actions
    Route::get('user/token/transfer', [\App\Http\Controllers\User\FinanceController::class, 'tokenTransferPage'])->name('user.token.transfer');
    Route::post('user/token/transfer', [\App\Http\Controllers\User\FinanceController::class, 'tokenTransfer'])->name('user.token.transfer.post');
    Route::get('user/token/transfer/lookup', [\App\Http\Controllers\User\FinanceController::class, 'tokenTransferLookup'])->name('user.token.transfer.lookup');
    Route::get('user/token/swap', [\App\Http\Controllers\User\FinanceController::class, 'tokenSwapPage'])->name('user.token.swap');
    Route::post('user/token/swap', [\App\Http\Controllers\User\FinanceController::class, 'tokenSwap'])->name('user.token.swap.post');
    Route::get('user/token/withdraw', [\App\Http\Controllers\User\FinanceController::class, 'tokenWithdrawPage'])->name('user.token.withdraw');
    Route::post('user/token/withdraw', [\App\Http\Controllers\User\FinanceController::class, 'tokenWithdrawRequest'])->name('user.token.withdraw.post');
    Route::get('user/token/locked', [\App\Http\Controllers\User\FinanceController::class, 'lockedTokenPage'])->name('user.token.locked');
    Route::get('user/token/available', [\App\Http\Controllers\User\FinanceController::class, 'availableTokenPage'])->name('user.token.available');
    Route::post('user/token/available-to-free', [\App\Http\Controllers\User\FinanceController::class, 'availableToFree'])->name('user.token.available-to-free');

    // ── REFERRAL (user) ─────────────────────────────────────────────────
    Route::get ('user/referral/bonus',          [\App\Http\Controllers\User\ReferralController::class, 'bonus'])->name('user.referral.bonus');
    Route::post('user/referral/withdraw',       [\App\Http\Controllers\User\ReferralController::class, 'withdraw'])->name('user.referral.withdraw');
    Route::get ('user/referral/downline',       [\App\Http\Controllers\User\ReferralController::class, 'downline'])->name('user.referral.downline');
    Route::get ('user/referral/rank',           [\App\Http\Controllers\User\ReferralController::class, 'rank'])->name('user.referral.rank');

    // ── MY INVESTMENTS (user) ───────────────────────────────────────────
    Route::get('user/investments',                [\App\Http\Controllers\User\InvestmentController::class, 'index'])->name('user.investments');
    Route::get('user/investments/{id}',           [\App\Http\Controllers\User\InvestmentController::class, 'show'])->name('user.investments.show');

    // ── MY CONTRACT (user) ─────────────────────────────────────────────
    Route::get('user/contracts/bifonex', [\App\Http\Controllers\ContractDocumentController::class, 'userIndex'])
        ->name('user.contracts.bifonex');


    // create function that will return the page called kyc
    Route::get('user/kyc', function () {
        return view('user.kyc');
    });


      // create FAQ
      Route::get('user/faq', function () {
        return view('user.faq');
    });

       // create Rewards
       Route::get('user/rewards', function () {
        return view('user.rewards');
    });


         // create wallett
         Route::get('user/user-wallet', function () {
            return view('user.user-wallet');
        });
    
        // education page
        Route::get('user/education', function () {
            return view('user.education');
        });


    // Fixed ads and banner
    Route::get('user/dashboard/fixedads', function () {
        return view('user.fearloss.fixedads');
    });

    // about download app
    Route::get('user/dashboard/download-app', function () {
        return view('user.fearloss.download-app');
    });

      // Perform Tasks
      Route::get('user/dashboard/tasks', function () {
        return view('user.fearloss.tasks');
    });

          //   invitation page 
          Route::get('user/user-invitation', function () {
            return view('user.user-invitation');
        });

          // Add new invitation form 
          Route::get('user/create-invitation', function () {
            return view('user.create-invitation');
        });

  

    Route::get('user/dashboard/withdraw/user', [Balance::class, 'UserWithdrawal'])->name('user.dashboard.userwithdraw');
    Route::get('user/dashboard/withdraw', [Balance::class, 'UserWithdrawal'])->name('user.dashboard.withdraw');
    Route::get('user/dashboard/withdraw/manual', [Balance::class, 'withdraw'])->name('user.dashboard.manual_withdraw');
    Route::get('user/withdrawal/history', [Balance::class, 'userWithdrawalHistory'])->name('user.withdrawal-history');

    Route::post('user/dashboard/withdraw-status/', [Balance::class, 'withdraw_money'])->name('user.withdraw');
    Route::post('user/dashboard/withdraw-manual', [Balance::class, 'requestManualWithdrawal'])->name('user.withdraw.manual');

    // Direct blockchain (TRON USDT TRC20) automatic withdrawal — no Plisio
    Route::post('user/dashboard/withdraw/direct-blockchain', [Balance::class, 'directBlockchainWithdraw'])
          ->name('user.withdraw.direct_blockchain');

    Route::post('user/dashboard/apply', [Position::class, 'apply'])->name('user.position.apply');


    Route::post('user/club/create-club/', [Club::class, 'create'])->name('club.create');
    Route::get('user/club/invite-user/', [Club::class, 'invite'])->name('club.invite');
    Route::get('user/club/accept-request/', [Club::class, 'accept'])->name('club.accept');
    Route::get('user/club/accept-invitaion', [Club::class, 'acceptInvite'])->name('club.invite.accept');
    Route::post('user/club/request-to-join/', [Club::class, 'request'])->name('club.request');;


    Route::post('user/dashboard/activate/validate', [ActivationController::class, 'upgrade'])->name('user.dashboard.validate');


    Route::get('user/dashboard/package', [ActivationController::class, 'package'])->name('user.package.history');

    Route::get('user/dashboard/referral', [UserDashboardController::class, 'referral'])->name('user.referral.show');

    Route::get('user/dashboard/task', [UserDashboardController::class, 'showtask'])->name('user.task.show');
    Route::get('user/contract', [UserPackageController::class, 'contract'])->name('user.contract')->withoutmiddleware('contract');

    Route::post('user/contract/preview', [UserPackageController::class, 'Savecontract'])->name('user.contract.save')->withoutmiddleware('contract');

    Route::post('user/contract/confirm', [UserPackageController::class, 'Confirmcontract'])->name('user.contract.confirm')->withoutmiddleware('contract');
    Route::get('user/dashboard/payclick', [Payclick::class, 'Showclick'])->name('user.dashboard.payclick');
    // Route::get('user/dashboard/advertisment', [Advertisment::class, 'Showads'])->name('user.dashboard.advertisment');
    Route::get('user/dashboard/project', [Project::class, 'Showproject'])->name('user.dashboard.project');
    Route::get('user/dashboard/project/ptc', [Project::class, 'Showptc'])->name('user.dashboard.ptc');
    Route::post('user/dashboard/project/ptc', [Project::class, 'Storeptc'])->name('user.ptc');
    Route::post('user/dashboard/project/video', [Project::class, 'Storevideo'])->name('user.video');
    Route::get('user/dashboard/videos', [Videos::class, 'Showvideo'])->name('user.dashboard.payvideo');
    Route::get('user/dashboard/videos/invoices', [Videos::class, 'invoices'])->name('user.dashboard.payvideo.invoice');
    Route::get('user/dashboard/project/watch', [videos::class, 'watch'])->name('user.watch');
    Route::post('user/dashboard/project/watched', [videos::class, 'watched'])->name('user.watched');
    Route::get('user/dashboard/project/video', [Project::class, 'Showvideo'])->name('user.dashboard.video');
    Route::get('user/dashboard/project/myptc', [Project::class, 'Showmyptc'])->name('user.dashboard.myptc');
    Route::get('user/dashboard/project/myvideo', [Project::class, 'Showmyvideo'])->name('user.dashboard.myvideo');
    Route::get('/user/dashboard/payments', [UserDashboardController::class, 'payments'])->name('user.dashboard.payments');
    Route::post('/user/transfer/internal-account', [UserDashboardController::class, 'TransferToInternal'])->name('user.transfers');
    Route::get('/user/dashboard/coinac', [UserDashboardController::class, 'coinacc'])->name('user.dashboard.coinacc');
    Route::get('/user/dashboard/tradingac', [UserDashboardController::class, 'tradingac'])->name('user.dashboard.tradingac');
    Route::get('/user/dashboard/myinvoices', [UserDashboardController::class, 'myinvoices'])->name('user.dashboard.myinvoices');
    // Route::get('user/dashboard/ppc', [Payclick::class, 'Viewclick'])->name('user.click.view');
    Route::get('user/dashboard/ppcu', [Payclick::class, 'url'])->name('user.url');
    Route::get('user/dashboard/asking-ppcu', [Payclick::class, 'Asking'])->name('user.asking');
    Route::get('user/dashboard/ppc', [Payclick::class, 'click'])->name('user.click');
    Route::post('user/dashboard/ppc', [Payclick::class, 'Payads'])->name('user.ads');
    Route::post('user/dashboard/url', [Payclick::class, 'Payurl'])->name('user.ads.url');
    Route::post('user/dashboard/claim-reward', [Payclick::class, 'ClaimReward'])->name('user.claim.reward');
    // events
    Route::get('user/dashboard/events/', [Events::class, 'getEvents'])->name('user.dashboard.events');
    Route::post('user/dashboard/events-history/', [Events::class, 'reportEvents'])->name('user.event.report');

    // club
    Route::get('user/club', [Team::class, 'create'])->name('user.dashboard.create');
});


Route::post('admin/check', [AdminController::class, 'check'])->name('admin.check');
Route::get('admin/login', [AdminController::class, 'login'])->name('admin.login');
 
Route::middleware(['auth:sanctum', 'verified', 'admin','claims'])->group(function () {
Route::post("admin/deposit-approve",[AdminController::class, 'ApproveDeposit'])->name('admin.approve-deposit');
Route::post('admin/deposit-otherwise-decision', [AdminController::class, 'OtherwiseDepositDecisions'])->name('admin.otherwise-decision-deposit');
    Route::get('admin/', [AdminController::class, 'index'])->name('admin.dashboard');

    // CLAIMS
    Route::get('admin/claims-report', [ClaimController::class, 'index'])->name('admin.claims');
    Route::post('admin/claims-report', [ClaimController::class, 'fixClaim'])->name('admin.fixclaim');
    Route::get('check-new-claims', [ClaimController::class, 'checkNewClaim']);


    Route::get('admin/withdrawal', [AdminController::class, 'Withdrawal'])->name('admin.withdrawal');

    // Token Price Settings
    Route::get('admin/token-settings', [\App\Http\Controllers\Admin\TokenSettingController::class, 'index'])->name('admin.token-settings');
    Route::put('admin/token-settings', [\App\Http\Controllers\Admin\TokenSettingController::class, 'update'])->name('admin.token-settings.update');
    Route::post('admin/withdrawal/approve', [AdminController::class, 'approveWithdrawal'])->name('admin.withdrawal.approve');
    Route::post('admin/withdrawal/reject', [AdminController::class, 'rejectWithdrawal'])->name('admin.withdrawal.reject');
    Route::get('admin/withdrawal-history', [AdminController::class, 'withdrawalHistory'])->name('admin.withdrawal-history');
    
    // ── ZOOM MEETINGS MANAGEMENT (admin) ──
    Route::get('admin/zoom-meetings', [AdminController::class, 'zoomIndex'])->name('admin.zoom.index');
    Route::post('admin/zoom-meetings', [AdminController::class, 'zoomStore'])->name('admin.zoom.store');
    Route::post('admin/zoom-meetings/{id}/toggle', [AdminController::class, 'zoomToggle'])->name('admin.zoom.toggle');
    Route::post('admin/zoom-meetings/{id}/delete', [AdminController::class, 'zoomDelete'])->name('admin.zoom.delete');

    // ── TEAM LEADERS MANAGEMENT (admin) ──
    Route::get('admin/team-leaders', [AdminController::class, 'teamLeadersList'])->name('admin.team-leaders.index');
    Route::get('admin/tm-auto-activations', [AdminController::class, 'tmAutoActivationsIndex'])->name('admin.tm-auto-activations.index');
    Route::post('admin/tm-auto-activations/store', [AdminController::class, 'tmAutoActivationStore'])->name('admin.tm-auto-activations.store');
    Route::post('admin/tm-auto-activations/{id}/activate-credit', [AdminController::class, 'activateTmAutoCredit'])->name('admin.tm-auto-activations.activate-credit');
    Route::post('admin/tm-auto-activations/{id}/toggle-credit', [AdminController::class, 'toggleTmAutoCredit'])->name('admin.tm-auto-activations.toggle-credit');
    Route::post('admin/tm-auto-activations/{id}/delete', [AdminController::class, 'tmAutoActivationDelete'])->name('admin.tm-auto-activations.delete');
    Route::get('admin/team-leaders-token-releases', [AdminController::class, 'teamLeaderTokenReleases'])->name('admin.team-leaders.token-releases');
    Route::post('admin/team-leaders-token-releases/{id}/approve', [AdminController::class, 'approveTokenRelease'])->name('admin.team-leaders.token-releases.approve');
    Route::post('admin/team-leaders-token-releases/{id}/reject', [AdminController::class, 'rejectTokenRelease'])->name('admin.team-leaders.token-releases.reject');
    Route::get('admin/team-leaders/{id}', [AdminController::class, 'showTeamLeader'])->name('admin.team-leaders.show');
    Route::post('admin/team-leaders/{id}/approve', [AdminController::class, 'approveTeamLeader'])->name('admin.team-leaders.approve');
    Route::post('admin/team-leaders/credit/{creditId}/update', [AdminController::class, 'updateCredit'])->name('admin.team-leaders.credit.update');
    Route::post('admin/team-leaders/{leaderId}/credit/create', [AdminController::class, 'createCredit'])->name('admin.team-leaders.credit.create');
    Route::post('admin/team-leaders/{id}/reject', [AdminController::class, 'rejectTeamLeader'])->name('admin.team-leaders.reject');
    Route::post('admin/team-leaders/{id}/revoke-credit', [AdminController::class, 'revokeCredit'])->name('admin.team-leaders.revoke-credit');
    Route::post('admin/team-leaders/{id}/convert-to-free', [AdminController::class, 'convertToFreeUser'])->name('admin.team-leaders.convert-to-free');

    // Official Video Promotions & Tutorials
    Route::get('admin/leader-videos', [AdminController::class, 'leaderVideos'])->name('admin.leader-videos');
    Route::get('admin/leader-videos/upload', [AdminController::class, 'leaderVideoUpload'])->name('admin.leader-videos.upload');
    Route::post('admin/leader-videos/store', [AdminController::class, 'leaderVideoStore'])->name('admin.leader-videos.store');
    Route::get('admin/leader-videos/{id}/approve', [AdminController::class, 'leaderVideoApprove'])->name('admin.leader-videos.approve');
    Route::post('admin/leader-videos/{id}/reject', [AdminController::class, 'leaderVideoReject'])->name('admin.leader-videos.reject');
    Route::get('admin/leader-videos/{id}/delete', [AdminController::class, 'leaderVideoDelete'])->name('admin.leader-videos.delete');

    // Marketing Banners & Creatives
    Route::get('admin/leader-banners', [AdminController::class, 'leaderBanners'])->name('admin.leader-banners');
    Route::get('admin/leader-banners/upload', [AdminController::class, 'leaderBannerUpload'])->name('admin.leader-banners.upload');
    Route::post('admin/leader-banners/store', [AdminController::class, 'leaderBannerStore'])->name('admin.leader-banners.store');
    Route::get('admin/leader-banners/{id}/approve', [AdminController::class, 'leaderBannerApprove'])->name('admin.leader-banners.approve');
    Route::post('admin/leader-banners/{id}/reject', [AdminController::class, 'leaderBannerReject'])->name('admin.leader-banners.reject');
    Route::get('admin/leader-banners/{id}/delete', [AdminController::class, 'leaderBannerDelete'])->name('admin.leader-banners.delete');

    // Team Leaders Announcements
    Route::get('admin/announcements', [AdminController::class, 'announcementsList'])->name('admin.announcements.index');
    Route::get('admin/announcements/create', [AdminController::class, 'announcementCreate'])->name('admin.announcements.create');
    Route::post('admin/announcements', [AdminController::class, 'announcementStore'])->name('admin.announcements.store');
    Route::get('admin/announcements/{id}/toggle', [AdminController::class, 'announcementToggle'])->name('admin.announcements.toggle');
    Route::get('admin/announcements/{id}/delete', [AdminController::class, 'announcementDelete'])->name('admin.announcements.delete');
    Route::post('admin/team-leaders/{id}/suspend', [AdminController::class, 'suspendTeamLeader'])->name('admin.team-leaders.suspend');
    Route::post('admin/team-leaders/{id}/reactivate', [AdminController::class, 'reactivateTeamLeader'])->name('admin.team-leaders.reactivate');
    
    // Auditing Routes
    Route::post('admin/team-leaders/events/{id}/approve', [AdminController::class, 'approveEventPlan'])->name('admin.team-leaders.events.approve');
    Route::post('admin/team-leaders/events/{id}/reject', [AdminController::class, 'rejectEventPlan'])->name('admin.team-leaders.events.reject');
    Route::post('admin/team-leaders/proofs/{id}/approve', [AdminController::class, 'approveEventProof'])->name('admin.team-leaders.proofs.approve');
    Route::post('admin/team-leaders/proofs/{id}/reject', [AdminController::class, 'rejectEventProof'])->name('admin.team-leaders.proofs.reject');
    Route::post('admin/team-leaders/socials/{id}/approve', [AdminController::class, 'approveSocialProfile'])->name('admin.team-leaders.socials.approve');
    Route::post('admin/team-leaders/socials/{id}/reject', [AdminController::class, 'rejectSocialProfile'])->name('admin.team-leaders.socials.reject');

    Route::get('admin/referral-bonuses', [AdminController::class, 'referralBonuses'])->name('admin.referral-bonuses');
    Route::get('admin/referral-bonuses/{userId}', [AdminController::class, 'referralBonusDetail'])->name('admin.referral-bonus-detail');
    Route::get('admin/token-withdrawals', [AdminController::class, 'tokenWithdrawals'])->name('admin.token-withdrawals');
    Route::post('admin/token-withdrawals/approve', [AdminController::class, 'approveTokenWithdrawal'])->name('admin.token-withdrawals.approve');
    Route::post('admin/token-withdrawals/reject', [AdminController::class, 'rejectTokenWithdrawal'])->name('admin.token-withdrawals.reject');

    // ── REFERRAL BONUSES (admin) ────────────────────────────────────────
    Route::get  ('admin/referral/bonuses',                [\App\Http\Controllers\Admin\ReferralAdminController::class, 'bonuses'])->name('admin.referral.bonuses');
    Route::get  ('admin/referral/bonuses/user/{id}',      [\App\Http\Controllers\Admin\ReferralAdminController::class, 'userDetail'])->name('admin.referral.bonuses.user');
    Route::get  ('admin/referral/withdrawals',            [\App\Http\Controllers\Admin\ReferralAdminController::class, 'withdrawals'])->name('admin.referral.withdrawals');
    Route::post ('admin/referral/withdrawals/{id}/approve',[\App\Http\Controllers\Admin\ReferralAdminController::class, 'approveWithdrawal'])->name('admin.referral.withdrawals.approve');
    Route::post ('admin/referral/withdrawals/{id}/reject', [\App\Http\Controllers\Admin\ReferralAdminController::class, 'rejectWithdrawal'])->name('admin.referral.withdrawals.reject');

    // ── RANK APPLICATIONS (admin) ───────────────────────────────────────
    Route::get  ('admin/rank/applications',   [\App\Http\Controllers\Admin\ReferralAdminController::class, 'rankApplications'])->name('admin.rank.applications');

    // ── DEPOSIT / WITHDRAWAL SETTINGS (admin) ───────────────────────────
    Route::get ('admin/settings/deposit-wallets',     [\App\Http\Controllers\Admin\SettingsController::class, 'depositWallets'])->name('admin.settings.deposit-wallets');
    Route::put ('admin/settings/deposit-wallets',     [\App\Http\Controllers\Admin\SettingsController::class, 'updateDepositWallets'])->name('admin.settings.deposit-wallets.update');
    Route::get ('admin/settings/withdrawal-settings', [\App\Http\Controllers\Admin\SettingsController::class, 'withdrawalSettings'])->name('admin.settings.withdrawal-settings');
    Route::put ('admin/settings/withdrawal-settings', [\App\Http\Controllers\Admin\SettingsController::class, 'updateWithdrawalSettings'])->name('admin.settings.withdrawal-settings.update');

    Route::get ('admin/enable-free-user/on-register', [\App\Http\Controllers\Admin\AdminController::class, 'freeUserRegisterPage'])->name('admin.enable-free-user.register');
    Route::post('admin/enable-free-user/on-register', [\App\Http\Controllers\Admin\AdminController::class, 'updateFreeUserRegister'])->name('admin.enable-free-user.update');
    Route::post ('admin/rank/{id}/approve',   [\App\Http\Controllers\Admin\ReferralAdminController::class, 'approveRank'])->name('admin.rank.approve');
    Route::post ('admin/rank/{id}/reject',    [\App\Http\Controllers\Admin\ReferralAdminController::class, 'rejectRank'])->name('admin.rank.reject');
    Route::get  ('admin/rank/eligible',       [\App\Http\Controllers\Admin\ReferralAdminController::class, 'eligible'])->name('admin.rank.eligible');
    Route::get  ('admin/rank/settings',       [\App\Http\Controllers\Admin\ReferralAdminController::class, 'rankSettings'])->name('admin.rank.settings');
    Route::put  ('admin/rank/settings',       [\App\Http\Controllers\Admin\ReferralAdminController::class, 'updateRankSettings'])->name('admin.rank.settings.update');
    
    Route::get('admin/users/list', [AdminController::class, 'users'])->name('users.list');
    Route::get('admin/memberships-plan', [AdminController::class, 'plans'])->name('membership.plans');

    Route::get('admin/adventures', [AdventureController::class, 'index'])->name('admin.adventures');
    Route::get('admin/Adventures', [AdventureController::class, 'index'])->name('admin.Adventures');
    Route::get('admin/campains', [CampainController::class, 'index'])->name('admin.campains');
    Route::get('admin/video-campains', [CampainController::class, 'VideoCampain'])->name('admin.video-campain');
    Route::get('admin/Text-campains', [CampainController::class, 'TextCampain'])->name('admin.text-campain');
    Route::get('admin/Banner-campains', [CampainController::class, 'BannerCampain'])->name('admin.banner-campain');
    Route::get('admin/Link-campains', [CampainController::class, 'LinkCampain'])->name('admin.link-campain');

    Route::get('admin/adventures/create', [AdventureController::class, 'create'])->name('admin.adventures.create');
    Route::get('admin/Adventures/create', [AdventureController::class, 'create'])->name('admin.Adventures.create');
    Route::post('admin/adventures/store', [AdventureController::class, 'store'])->name('admin.adventures.store');
    Route::post('admin/Adventures/store', [AdventureController::class, 'store'])->name('admin.Adventures.store');
    Route::get('admin/adventures/{adventure}/edit', [AdventureController::class, 'edit'])->name('admin.adventure.edit');
    Route::get('admin/Adventures/{adventure}/edit', [AdventureController::class, 'edit'])->name('admin.Adventure.edit');
    Route::put('admin/adventures/{adventure}/update', [AdventureController::class, 'update'])->name('adventures.update');
    Route::put('admin/Adventures/{adventure}/update', [AdventureController::class, 'update'])->name('Adventures.update');
    Route::get('admin/adventures/{adventure}/investors', [AdventureController::class, 'investors'])->name('admin.adventures.investors');
    Route::get('admin/Adventures/{adventure}/investors', [AdventureController::class, 'investors'])->name('admin.Adventures.investors');
    Route::delete('admin/adventures/{adventure}/delete', [AdventureController::class, 'destroy'])->name('admin.adventures.destroy');
    Route::delete('admin/Adventures/{adventure}/delete', [AdventureController::class, 'destroy'])->name('admin.Adventures.destroy');
    Route::get("admin/payments/deposited",[AdminController::class,'depositedPayment'])->name("admin.payments");
    Route::get("admin/payments/deposited/{id}",[AdminController::class,'depositDetail'])->name("admin.payments.show");

    // ── BIFONEX CONTRACTS (admin) ──────────────────────────────────────
    Route::get('admin/bifonex-contracts', [\App\Http\Controllers\ContractDocumentController::class, 'adminIndex'])->name('admin.contracts.index');
    Route::get('admin/bifonex-contracts/{contract}', [\App\Http\Controllers\ContractDocumentController::class, 'adminShow'])->name('admin.contracts.show');
    Route::get('admin/bifonex-contracts/{contract}/download', [\App\Http\Controllers\ContractDocumentController::class, 'adminDownload'])->name('admin.contracts.download');

    Route::get('admin/dashboard/contacted', [AdminController::class, 'contacted'])->name('admin.contacted');
    Route::post('admin/admin-login', [AdminController::class, 'logout'])->name('admin.logout');
    Route::get('admin/manage-ads', [manage::class, 'ads'])->name('admin.ads');
    Route::get('admin/adsDetail', [manage::class, 'adsDetail'])->name('admin.adsDetail');
    Route::get('admin/check-ads', [manage::class, 'clicked'])->name('clicks');
    Route::get('admin/check-ads/approve', [manage::class, 'approveAds'])->name('admin.ads.approve');
    Route::post('admin/check-ads/rejected', [manage::class, 'rejectAds'])->name('admin.ads.reject');
    Route::post('admin/add-ads/create', [manage::class, 'createAds'])->name('admin.ads.created');
    Route::get('admin/add-ads', [manage::class, 'addAds'])->name('admin.ads.create');
    // Route::post('admin/add-ptc', [manage::class, 'createAds'])->name('admin.add.ads');
    Route::get('admin/manage-video', [manage::class, 'videos'])->name('admin.videos');
    Route::get('admin/manage-clubs', [manage::class, 'clubs'])->name('admin.clubs');
    Route::get('admin/check-video', [manage::class, 'videoDetail'])->name('admin.videoDetail');
    Route::get('admin/check-video/approve', [manage::class, 'approveVideo'])->name('admin.video.approve');
    Route::post('admin/check-video/rejected', [manage::class, 'rejectVideo'])->name('admin.video.reject');
    Route::get('admin/add-video', [manage::class, 'videoAds'])->name('admin.video');
    Route::post('admin/add-video/create', [manage::class, 'createVideo'])->name('admin.video.create');
    Route::get('admin/add-video/create', [manage::class, 'newVideo'])->name('admin.video.upload');
    Route::get('admin/manage-position', [AdminController::class, 'position'])->name('admin.position');
    Route::get('admin/manage-position/approved', [AdminController::class, 'position_approved'])->name('admin.position.approve');
    Route::get('admin/pos_edit', [AdminController::class, 'pos_edit'])->name('admin.pos_edit');
    Route::post('admin/pos_edit/edited', [AdminController::class, 'edit'])->name('admin.position.edit');
    Route::get('admin/profile', [ProfileController::class, 'ad_edit'])->name('admin.profile.edit');
    Route::post('admin/profile', [ProfileController::class, 'ad_updates'])->name('admin.profile.updates');
    Route::get('admin/user/ft', [AdminController::class, 'ft'])->name('admin.ft');
    Route::get('admin/user/requested', [Requested::class, 'requested'])->name('admin.requested');
    Route::get('admin/user/verify', [AdminController::class, 'verify'])->name('admin.verify');
    Route::get('admin/user/pending', [AdminController::class, 'pending'])->name('admin.pending');
    Route::get('admin/user/fc1', [AdminController::class, 'fc1'])->name('admin.fc1');
    Route::get('fcpackages/{id}/investors', [FCpackageController::class, 'investors'])->name('fcpackages.investors');
    // 'investors' => 'fcpackages.investors'
    Route::resource('fcpackages', FCpackageController::class)->names([
        'index' => 'fcpackages',
        'create' => 'fcpackages.create',
        'store' => 'fcpackages.store',
        'show' => 'fcpackages.show',
        'edit' => 'fcpackages.edit',
        'update' => 'fcpackages.update',
        'destroy' => 'fcpackages.destroy'

    ]);


    Route::get('admin/user/fc2', [AdminController::class, 'fc2'])->name('admin.fc2');
    Route::post('admin/user/ft/save', [ActivationController::class, 'saveCode'])->name('admin.ft.save');
    Route::post('admin/user/tm/credit/approve', [ActivationController::class, 'approveCredit'])->name('tm.credit.approve');
    Route::post('admin/user/tm/credit/reject', [ActivationController::class, 'rejectCredit'])->name('tm.credit.reject');
    Route::post('admin/user/tm/credit/reactivate', [ActivationController::class, 'reactivateCredit'])->name('tm.credit.reactivate');
    Route::put('admin/user/ft/{id}/update', [ActivationController::class, 'updateCode'])->name('admin.ft.update');
    Route::put('admin/activation/{id}/update', [ActivationController::class, 'updateCodeTask'])->name('updateFT');
    Route::delete('admin/user/ft/{id}/delete', [ActivationController::class, 'deleteCode'])->name('admin.ft.delete');
    Route::post('admin/user/task', [TasksController::class, 'store'])->name('admin.task.store');
    Route::get('admin/user/task', [TasksController::class, 'task'])->name('admin.user.task');
    Route::get('admin/user-subscribed', [Requested::class, 'subscribe'])->name('admin.subscribe');
    Route::get('admin/user-add', [Users::class, 'add_user'])->name('admin.add_user');
    Route::get('balance/', [HomeController::class, 'balance']);

});

Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::post('profile', [ProfileController::class, 'updates'])->name('profile.updates');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::get('try', [HomeController::class, 'try']);

Route::get('about', [HomeController::class, 'about'])->name('about');
Route::get('contact', [HomeController::class, 'contact'])->name('contact');
Route::post('contact', [HomeController::class, 'contacted'])->name('contact.us');
Route::get('getnews', [News::class, 'activate'])->name('latest');
Route::get('deactivate', [News::class, 'deactivate']);

Route::get('project', [HomeController::class, 'project'])->name('project');
Route::get('team-leader', [TeamLeaderController::class, 'index'])->name('team.leader');
Route::get('api/countries', [TeamLeaderController::class, 'countries'])->name('api.countries');
Route::get('team-leader/login', [TeamLeaderController::class, 'showLoginForm'])->name('team-leader.login');
Route::post('team-leader/login', [TeamLeaderController::class, 'loginLeader'])->name('team-leader.login.post');
Route::post('team-leader/logout', [TeamLeaderController::class, 'logoutLeader'])->name('team-leader.logout');
Route::post('/team-leader/apply', [TeamLeaderController::class, 'apply'])->name('team-leader.apply');
Route::post('/team-leader/complete-application', [TeamLeaderController::class, 'completeApplication'])->name('team-leader.complete');

// Protected Routes with Team Leader Middleware
Route::middleware('team-leader')->group(function () {
    // pending request
    Route::get('team-leader/pending-approval', function () {
        return view('team-leader.pending-approval');
    })->name('team-leader.pending-approval');

    // rejected approval
    Route::get('team-leader/rejected', function () {
        return view('team-leader.rejected');
    })->name('team-leader.rejected');

    // confirmed approval
    Route::get('team-leader/dashboard', [TeamLeaderController::class, 'dashboard'])->name('team-leader.dashboard');
    Route::get('team-leader/all', [TeamLeaderController::class, 'allFeatures'])->name('team-leader.all');
    Route::post('team-leader/event-report', [TeamLeaderController::class, 'storeEventReport'])->name('team-leader.event-report');
    Route::post('team-leader/events', [TeamLeaderController::class, 'storeEvent'])->name('team-leader.events.store');
    Route::post('team-leader/events/{id}/proof', [TeamLeaderController::class, 'submitEventProof'])->name('team-leader.events.proof');
    Route::post('team-leader/socials', [TeamLeaderController::class, 'storeSocial'])->name('team-leader.socials.store');
    Route::post('team-leader/task-toggle', [TeamLeaderController::class, 'toggleTask'])->name('team-leader.task-toggle');

});



Route::get('leader', [TeamLeaderController::class, 'index'])->name('leader');
Route::get('success', [PaymentController::class, 'success']);
Route::get('error', [PaymentController::class, 'error']);

//logout
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Route::get('/download/{file}', [FileController::class, 'download'])->name('file.download');


Route::get('/privancy', [HomeController::class, 'privacy'])->name('policy');
Route::get('/term_and_condition', [HomeController::class, 'condition'])->name('tam');
Route::get('/info', [HomeController::class, 'info'])->name('info');
// Route::post('/register/request', [HomeController::class, 'request'])->name('guest.request');

Route::get('psio', [HomeController::class, 'psio']);

require __DIR__ . '/auth.php';
