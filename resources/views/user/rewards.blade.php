{{--
    SHIM — do not add content here.

    The old static rewards page lived in this file. The dynamic Volume-Point
    → USDT redemption page is user/rewards-redeem.blade.php. This shim exists
    so that ANY controller version (older copies returned
    view('user.rewards')) renders the dynamic page — and so deployments that
    extract files without deleting the old rewards.blade.php can never
    resurrect the static boxes.
--}}
@include('user.rewards-redeem')
