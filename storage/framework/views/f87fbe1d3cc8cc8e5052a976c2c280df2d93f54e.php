<div class="wrapper">
    <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- AGGRESSIVE FIX FOR SIDEBAR OVERLAP -->
    <style>
        /* Force the content to the right of the sidebar only when NOT collapsed on desktop */
        @media (min-width: 992px) {
            body:not(.sidebar-collapse) .content-wrapper,
            body:not(.sidebar-collapse) .wrapper > .content-wrapper {
                margin-left: 260px !important;
                padding-top: 70px !important;
                min-height: 100vh;
                padding-right: 20px;
                transition: margin-left .3s ease-in-out;
            }
            
            /* Fit screen smoothly when sidebar is collapsed */
            body.sidebar-collapse .content-wrapper,
            body.sidebar-collapse .wrapper > .content-wrapper {
                margin-left: 4.6rem !important; /* matches standard AdminLTE collapsed sidebar-mini width */
                padding-top: 70px !important;
                min-height: 100vh;
                padding-right: 20px;
                transition: margin-left .3s ease-in-out;
            }
        }
        
        /* Mobile fix */
        @media (max-width: 991.98px) {
            body .content-wrapper,
            body.sidebar-collapse .content-wrapper,
            body:not(.sidebar-collapse) .content-wrapper {
                margin-left: 0 !important;
                padding-top: 70px !important;
                padding-right: 0 !important;
                padding-left: 0 !important;
            }
            .tl-dashboard {
                padding: 10px 0 !important; /* Removes side padding on mobile */
            }
            .tl-grid {
                padding: 0 !important; /* Full bleed on mobile */
                gap: 15px !important;
            }
            .events-grid {
                grid-template-columns: 1fr !important; /* Stacks history below form */
            }
        }
        
        .tl-dashboard {
            padding: 20px;
        }
        
        /* Extra safety for this specific page */
        .tl-dashboard {
            position: relative;
            z-index: 1;
        }

        /* Responsive main layout grid - increases banner size on desktop to 280px */
        .tl-grid {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 280px; /* Increased from 180px to 280px */
            gap: 24px;
        }

        /* Responsive rows that stack neatly on smaller devices */
        .tl-row-top {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }

        .tl-row-bottom {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }

        @media (max-width: 991.98px) {
            .tl-row-top,
            .tl-row-bottom {
                grid-template-columns: 1fr !important; /* Stacks all segments vertically on mobile/tablet */
                gap: 20px;
            }
        }

        @media (max-width: 1200px) {
            .tl-grid {
                grid-template-columns: 1fr 240px;
            }
        }

        @media (max-width: 991.98px) {
            .tl-grid {
                grid-template-columns: 1fr; /* Stacks vertically on mobile/tablet */
                gap: 20px;
            }
        }

        .ad-banner-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-2);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .ad-banner-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-3);
        }
    </style>

    <style>
        :root {
            --space-1: 4px; --space-2: 8px; --space-3: 12px; --space-4: 16px;
            --space-5: 20px; --space-6: 24px; --space-8: 32px;
            --radius-sm: 8px; --radius-md: 12px; --radius-lg: 16px;
            --shadow-1: 0 1px 2px rgba(0,0,0,.05);
            --shadow-2: 0 2px 8px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.04);
            --shadow-3: 0 8px 24px rgba(0,0,0,.12), 0 2px 8px rgba(0,0,0,.06);
        }

        .tl-dashboard {
            font-family: 'Inter', system-ui, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            line-height: 1.5;
        }

        .tl-header {
            background: linear-gradient(135deg, #0f172a, #1e2937);
            color: white;
            padding: 20px 24px;
            margin-bottom: 20px;
        }

        .tl-header h1 {
            font-size: 2rem;
            font-weight: 800;
            color: #f87171;
            margin: 0;
        }

        .section-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-2);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .card-header {
            padding: 14px 18px;
            border-bottom: 1px solid #f1e7ff;
            background: #faf5ff;
            font-weight: 700;
            font-size: 0.85rem;
            color: #581c87;
        }

        .countdown-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 12px;
        }

        .countdown-box {
            background: #1e2937;
            color: white;
            text-align: center;
            padding: 10px 6px;
            border-radius: 8px;
        }

        .countdown-box .number {
            font-size: 1.6rem;
            font-weight: 900;
            line-height: 1;
        }

        .countdown-box .label {
            font-size: 0.65rem;
            opacity: 0.7;
            margin-top: 4px;
        }

        .badges {
            display: flex;
            gap: 8px;
            margin: 12px 0;
        }

        .badge {
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .badge-tm { background: #fee2e2; color: #b91c1c; }
        .badge-fc { background: #dcfce7; color: #166534; }

        .zoom-live {
            background: #450a0a;
            color: #f87171;
            padding: 10px 14px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-weight: 700;
            border: 1px solid #f87171;
        }

        .rules-box {
            background: #7f1d1d;
            color: #fee2e2;
            padding: 18px;
            border-radius: 10px;
            font-size: 0.85rem;
            line-height: 1.6;
        }

        .events-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .event-history {
            background: #f8fafc;
            padding: 16px;
            border-radius: 10px;
        }

        .report-form {
            background: #0f172a;
            color: white;
            padding: 18px;
            border-radius: 10px;
        }

        .report-form label {
            font-size: 0.95rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 7px;
            flex-wrap: wrap;
            margin-bottom: 5px;
            color: #f1f5f9;
        }

        /* Small "required" / "optional" markers — sized down vs the label.
           Works the same for both the Plan and Zoom (switchable) forms. */
        .report-form label span {
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 2px 7px;
            border-radius: 999px;
            line-height: 1.5;
            background: rgba(148, 163, 184, 0.16);
        }

        /* Make the native calendar/clock picker icons clearly visible on the dark background. */
        .report-form input[type="date"],
        .report-form input[type="time"] {
            color-scheme: dark;
        }
        .report-form input[type="date"]::-webkit-calendar-picker-indicator,
        .report-form input[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            opacity: 0.9;
            cursor: pointer;
        }

        .report-form input, .report-form textarea, .report-form select {
            width: 100%;
            padding: 8px 10px;
            border-radius: 6px;
            border: 1px solid #475569;
            background: #1e2937;
            color: white;
            font-size: 0.85rem;
            margin-bottom: 10px;
        }

        .amb-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 12px;
        }

        .amb-card {
            background: #1e40af;
            color: white;
            padding: 14px;
            border-radius: 10px;
            text-align: center;
        }

        .amb-card button {
            margin-top: 8px;
            background: #1e3a8a;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 0.75rem;
            cursor: pointer;
        }

        .video-strip {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 8px 0;
        }

        .v-thumb {
            width: 110px;
            height: 62px;
            border-radius: 6px;
            overflow: hidden;
            flex-shrink: 0;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .v-thumb.active {
            border-color: #3b82f6;
        }

        .toggle-buttons {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }

        .toggle-btn {
            flex: 1;
            padding: 10px;
            text-align: center;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .toggle-btn.active {
            background: #1e40af;
            color: white;
        }

        .toggle-btn:not(.active) {
            background: #e0e7ff;
            color: #1e40af;
        }

        .sidebar-right {
            background: white;
            border-radius: 12px;
            padding: 16px;
            box-shadow: var(--shadow-2);
        }

        .sidebar-item {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .sidebar-item.rewards { background: #581c87; color: white; }
        .sidebar-item.checkin { background: #f59e0b; color: white; }
        .sidebar-item.refer { background: #ea580c; color: white; }
    </style>

    <!-- PROPER ADMINLTE WRAPPER -->
    <div class="content-wrapper" style="padding-top: 70px !important;">
        <section class="content">
            
            <div class="tl-dashboard">
                <?php
                    $userLevel = $activation->package ?? ($teamLeader->leadership_level ?? 'TEAM_LEADER');
                    $isSuperLeader = strtoupper($userLevel) === 'SUPER_LEADER';
                    $dashboardTitle = $isSuperLeader ? 'Super Leader Dashboard' : 'Team Leader Dashboard';
                ?>

                <!-- HEADER -->
                <div class="tl-header">
                    <h1><?php echo e($dashboardTitle); ?></h1>
                </div>

                <div class="tl-grid">

            <!-- MAIN CONTENT -->
            <div>

            <!-- TOP ROW: COUNTDOWN + RULES -->
            <div class="tl-row-top">
                
                <!-- Countdown + Badges -->
                <div class="card">
                    <div class="card-header">DURATION &amp; STATUS</div>
                    <div style="padding: 18px;">
                        <div class="countdown-grid">
                            <div class="countdown-box"><div class="number" id="cd-d">57</div><div class="label">Days</div></div>
                            <div class="countdown-box"><div class="number" id="cd-h">21</div><div class="label">Hours</div></div>
                            <div class="countdown-box"><div class="number" id="cd-m">43</div><div class="label">Min</div></div>
                            <div class="countdown-box"><div class="number" id="cd-s">52</div><div class="label">Sec</div></div>
                        </div>

                        <div class="badges">
                            <?php if($isSuperLeader): ?>
                                <span class="badge badge-tm">TM SUPER LEADER</span>
                            <?php else: ?>
                                <span class="badge badge-fc">FC LEADER</span>
                            <?php endif; ?>
                        </div>

                        <?php if($credit && $credit->credit_amount > 0): ?>
                        <div style="background:#1e1b4b;color:white;padding:12px;border-radius:8px;font-size:0.8rem;margin-top:12px;">
                            <div style="display:flex;justify-content:space-between;"><span>Total</span><strong>$<?php echo e(number_format($credit->credit_amount,2)); ?></strong></div>
                            <div style="display:flex;justify-content:space-between;"><span>Remaining</span><strong>$<?php echo e(number_format($credit->remaining_credit,2)); ?></strong></div>
                            <div style="display:flex;justify-content:space-between;"><span>Cashout</span><strong>$<?php echo e(number_format($credit->cashout_amount,2)); ?></strong></div>
                        </div>
                        <?php endif; ?>

                        <?php
                            $activeZoomLeader = \App\Models\ZoomMeeting::activeMeeting();
                        ?>
                        <?php if($activeZoomLeader): ?>
                        <div class="zoom-live" onclick="window.open('<?php echo e($activeZoomLeader->zoom_link); ?>', '_blank')" style="cursor:pointer;">
                            <i class="fas fa-video fa-lg"></i>
                            <div>
                                <div style="font-weight:800;color:#f87171;">WE ARE LIVE NOW ON ZOOM</div>
                                <div style="font-size:0.75rem;color:#f8fafc;font-weight:700;"><?php echo e($activeZoomLeader->topic); ?> · CLICK HERE TO JOIN US</div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- ASSIGNED TASKS (moved here — swapped with System Announcement) -->
                        <div style="margin-top:16px;border:1px solid #c7d2fe;border-radius:8px;background:#eef2ff;padding:14px;">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                                <span style="font-weight:700;color:#1e40af;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.5px;"><i class="fas fa-tasks"></i> ASSIGNED TASKS</span>
                                <span id="task-count-badge" style="font-size:0.65rem;background:#dbeafe;color:#1e40af;padding:2px 8px;border-radius:999px;"><?php echo e($taskItems['completed']); ?>/<?php echo e($taskItems['total']); ?> done</span>
                            </div>

                            <?php if($taskItems['total'] > 0): ?>
                                <div id="task-list" style="display:flex;flex-direction:column;gap:8px;">
                                    <?php $__currentLoopData = $taskItems['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="task-item" style="display:flex;align-items:flex-start;gap:10px;padding:8px 10px;background:white;border:1px solid #e0e7ff;border-radius:8px;">
                                            <input type="checkbox" id="task-<?php echo e($index); ?>"
                                                   data-hash="<?php echo e($t['hash']); ?>"
                                                   <?php echo e($t['completed'] ? 'checked' : ''); ?>

                                                   onchange="toggleTask(this)"
                                                   style="width:18px;height:18px;accent-color:#1e40af;cursor:pointer;margin-top:2px;">
                                            <label for="task-<?php echo e($index); ?>" style="flex:1;cursor:pointer;font-size:0.8rem;line-height:1.4;">
                                                <?php echo e($index + 1); ?>. <?php echo e($t['text']); ?>

                                            </label>
                                            <span class="task-status" style="font-size:0.65rem;color:#64748b;white-space:nowrap;"><?php echo e($t['completed'] ? '✓ Done' : 'Pending'); ?></span>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                                <div style="margin-top:12px;">
                                    <div style="display:flex;justify-content:space-between;font-size:0.7rem;margin-bottom:4px;">
                                        <span style="color:#64748b;">Progress</span>
                                        <span id="task-progress-text"><?php echo e($taskItems['completed']); ?>/<?php echo e($taskItems['total']); ?></span>
                                    </div>
                                    <div style="background:#e0e7ff;height:7px;border-radius:999px;overflow:hidden;">
                                        <div id="task-progress-bar"
                                             style="height:100%;width:<?php echo e($taskItems['total'] > 0 ? round($taskItems['completed']/$taskItems['total']*100) : 0); ?>%;background:linear-gradient(to right,#1e40af,#3b82f6);transition:width 0.3s;"></div>
                                    </div>
                                </div>
                                <p style="font-size:0.62rem;color:#94a3b8;margin-top:6px;">
                                    <i class="fas fa-shield-alt"></i> Your progress is saved automatically and stays ticked until you untick it.
                                </p>
                            <?php else: ?>
                                <div style="padding:14px;text-align:center;color:#64748b;">
                                    <i class="fas fa-clipboard-list fa-2x mb-2" style="opacity:0.4;"></i><br>
                                    <strong>No tasks assigned yet.</strong><br>
                                    <small>Admin will assign tasks soon.</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- SYSTEM ANNOUNCEMENT (moved here — swapped with Assigned Tasks) -->
                <div class="card">
                    <div class="card-header" style="background:#1d4ed8;color:white;">
                        <i class="fas fa-bullhorn mr-2"></i> SYSTEM ANNOUNCEMENT
                    </div>
                    <div style="padding:18px;">
                        <div style="font-size:0.88rem;color:#1e3a8a;line-height:1.6;max-height:420px;overflow-y:auto;padding-right:4px;">
                            <?php $__empty_1 = true; $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ann): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div style="margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid #dbeafe;">
                                    <strong style="color:#1e40af;"><?php echo e($index + 1); ?>. <?php echo e($ann->title); ?></strong>
                                    <div style="margin-top:5px;color:#1e3a8a;"><?php echo e($ann->content); ?></div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div style="color:#64748b;font-style:italic;text-align:center;padding:30px 0;">No announcements active at this time.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- EVENTS SECTION -->
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-header">EVENTS &amp; REPORTS</div>
                <div style="padding: 20px;">
                    <div class="events-grid">
                        
                        <!-- Event History -->
                        <div>
                            <div class="section-title">My Event History</div>
                            <div class="event-history" style="min-height: 180px;">
                                <?php $__empty_1 = true; $__currentLoopData = $allEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $evIsZoom = ($e->event_type === 'zoom' || $e->type === 'zoom');
                                        $statusColors = [
                                            'approved' => 'background:#dcfce7;color:#166534;',
                                            'pending'  => 'background:#fef3c7;color:#854d0e;',
                                            'rejected' => 'background:#fee2e2;color:#b91c1c;',
                                        ];
                                        $proofColors = [
                                            'approved' => 'background:#dcfce7;color:#166534;',
                                            'pending'  => 'background:#fef3c7;color:#854d0e;',
                                            'rejected' => 'background:#fee2e2;color:#b91c1c;',
                                            'none'     => 'background:#f1f5f9;color:#64748b;',
                                        ];
                                        $proofFiles = !empty($e->proof_files) ? array_filter(array_map('trim', explode(',', $e->proof_files))) : [];
                                        $evPhotos = [];
                                        if (!empty($e->event_image_1)) { $evPhotos[] = $e->event_image_1; }
                                        if (!empty($e->event_image_2)) {
                                            foreach (explode(',', $e->event_image_2) as $_p) {
                                                $_p = trim($_p);
                                                if ($_p !== '') { $evPhotos[] = $_p; }
                                            }
                                        }
                                    ?>
                                    <div class="event-row" style="padding:8px 0;border-bottom:1px solid #e2e8f0;font-size:0.85rem;">
                                        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;flex-wrap:wrap;">
                                            <div style="flex:1;min-width:0;">
                                                <strong><?php echo e($e->title); ?></strong>
                                                <?php if($evIsZoom): ?>
                                                    <span style="font-size:0.6rem;padding:1px 6px;border-radius:999px;background:#dbeafe;color:#1e40af;margin-left:4px;vertical-align:middle;"><i class="fas fa-video"></i> Zoom</span>
                                                <?php else: ?>
                                                    <span style="font-size:0.6rem;padding:1px 6px;border-radius:999px;background:#e0e7ff;color:#3730a3;margin-left:4px;vertical-align:middle;"><i class="fas fa-map-marker-alt"></i> Plan</span>
                                                <?php endif; ?>
                                                <?php if($e->event_time): ?>
                                                    <div style="font-size:0.7rem;color:#64748b;margin-top:2px;"><i class="fas fa-clock"></i> <?php echo e($e->event_time->format('d M Y, h:i A')); ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <span style="font-size:0.7rem;padding:1px 8px;border-radius:999px;<?php echo e($statusColors[$e->status] ?? $statusColors['pending']); ?>"><?php echo e(ucfirst($e->status)); ?></span>
                                        </div>

                                        
                                        <?php if($e->proof_submitted || !empty($proofFiles)): ?>
                                            <div style="margin-top:4px;font-size:0.7rem;color:#64748b;">
                                                Proof:
                                                <span style="padding:1px 8px;border-radius:999px;<?php echo e($proofColors[$e->proof_status] ?? $proofColors['none']); ?>"><?php echo e(ucfirst($e->proof_status ?: 'none')); ?></span>
                                                <?php if(!empty($proofFiles)): ?> &middot; <?php echo e(count($proofFiles)); ?> file(s) <?php endif; ?>
                                            </div>
                                            <?php if(!empty($proofFiles)): ?>
                                                <div style="display:flex;flex-wrap:wrap;gap:4px;margin-top:6px;">
                                                    <?php $__currentLoopData = $proofFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $pfUrl = asset('storage/'.$pf);
                                                            $pfExt = strtolower(pathinfo($pf, PATHINFO_EXTENSION));
                                                            $pfIsImg = in_array($pfExt, ['jpg','jpeg','png','gif','webp','bmp']);
                                                        ?>
                                                        <?php if($pfIsImg): ?>
                                                            <img src="<?php echo e($pfUrl); ?>" onclick="openLightbox('<?php echo e($pfUrl); ?>')" title="Click to view proof" style="width:46px;height:38px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;cursor:pointer;">
                                                        <?php else: ?>
                                                            <a href="<?php echo e($pfUrl); ?>" target="_blank" style="display:inline-flex;align-items:center;gap:3px;font-size:0.62rem;padding:3px 6px;background:#f1f5f9;color:#475569;border-radius:6px;border:1px solid #e2e8f0;">
                                                                <i class="fas fa-file-download"></i> <?php echo e(strtoupper($pfExt)); ?>

                                                            </a>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        
                                        <?php if(!empty($evPhotos)): ?>
                                            <div style="margin-top:6px;">
                                                <div style="font-size:0.65rem;color:#64748b;margin-bottom:3px;"><i class="fas fa-images"></i> Event Photos</div>
                                                <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                                    <?php $__currentLoopData = $evPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php $epUrl = asset('storage/'.$ep); ?>
                                                        <img src="<?php echo e($epUrl); ?>" onclick="openLightbox('<?php echo e($epUrl); ?>')" title="Click to view" style="width:50px;height:42px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;cursor:pointer;">
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        
                                        <?php if($e->status === 'approved' && $e->proof_status !== 'approved'): ?>
                                            <button type="button" onclick="openProofModal(<?php echo e($e->id); ?>)" style="margin-top:8px;background:#0f766e;color:white;border:none;padding:6px 12px;border-radius:8px;font-size:0.75rem;font-weight:700;cursor:pointer;">
                                                <i class="fas fa-cloud-upload-alt"></i> Upload Proof of Event
                                            </button>
                                        <?php elseif($e->status === 'approved' && $e->proof_status === 'approved'): ?>
                                            <span style="display:inline-block;margin-top:8px;background:#065f46;color:#a7f3d0;padding:6px 12px;border-radius:8px;font-size:0.72rem;font-weight:700;">
                                                <i class="fas fa-check-circle"></i> Proof Approved
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div style="color:#64748b;text-align:center;padding:40px 0;">No event history yet.</div>
                                <?php endif; ?>

                                
                                <div id="eventPager" style="display:none;justify-content:space-between;align-items:center;margin-top:12px;font-size:0.75rem;">
                                    <button id="eventPrev" type="button" onclick="eventPrevPage()" style="background:#1e2937;color:white;border:none;padding:5px 12px;border-radius:6px;cursor:pointer;font-size:0.72rem;"><i class="fas fa-chevron-left"></i> Prev</button>
                                    <span id="eventPageInfo" style="color:#64748b;"></span>
                                    <button id="eventNext" type="button" onclick="eventNextPage()" style="background:#1e2937;color:white;border:none;padding:5px 12px;border-radius:6px;cursor:pointer;font-size:0.72rem;">Next <i class="fas fa-chevron-right"></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- Report Event Form -->
                        <div>
                            <div class="section-title">Report Event</div>
                            <div class="report-form">
                                <form action="<?php echo e(route('team-leader.event-report')); ?>" method="POST" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="event_type" id="form-type" value="plan">

                                    <div id="fp">
                                        <label>Image 1 <span style="color:#f87171;">required</span></label>
                                        <input type="file" name="event_image_1" accept="image/*" required>

                                        <label>Image 2 (Multiple) <span style="color:#64748b;">optional</span></label>
                                        <input type="file" name="event_image_2[]" accept="image/*" multiple>

                                        <label>Description <span style="color:#f87171;">required</span></label>
                                        <textarea name="description" required rows="2"></textarea>

                                        <label>Event done on <span style="color:#f87171;">required</span></label>
                                        <input type="date" name="event_done_on" required>

                                        <label>Event Time <span style="color:#f87171;">required</span></label>
                                        <input type="time" name="event_time" required>

                                        <label>Hotel/Location <span style="color:#64748b;">optional</span></label>
                                        <input type="text" name="hotel_location">
                                    </div>

                                    <div id="fz" style="display:none;">
                                        <label>Zoom Link <span style="color:#f87171;">required</span></label>
                                        <input type="url" name="zoom_link">

                                        <label>Country <span style="color:#f87171;">required</span></label>
                                        <input type="text" name="country">

                                        <label>Place <span style="color:#f87171;">required</span></label>
                                        <input type="text" name="place">

                                        <label>Date <span style="color:#f87171;">required</span></label>
                                        <input type="date" name="event_date">

                                        <label>Event Time <span style="color:#f87171;">required</span></label>
                                        <input type="time" name="event_time">
                                    </div>

                                    <button type="submit" style="width:100%;margin-top:12px;background:#3b82f6;color:white;padding:10px;border:none;border-radius:8px;font-weight:700;">
                                        Submit Report
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    

                    <!-- Toggle Buttons -->
                    <div class="toggle-buttons">
                        <div onclick="toggle('plan')" id="tb-plan" class="toggle-btn active">Upload Event plan</div>
                        <div onclick="toggle('zoom')" id="tb-zoom" class="toggle-btn">Upload zoom</div>
                    </div>
                </div>
            </div>

            <!-- AMBASSADOR + VIDEOS ROW -->
            <div class="tl-row-bottom">
                
                <!-- Ambassador Program -->
                <div class="card">
                    <div class="card-header">AMBASSADOR PROGRAM</div>
                    <div style="padding: 18px;">
                        <div style="background:#1e3a8a;color:white;padding:10px 14px;border-radius:8px;text-align:center;margin-bottom:14px;font-weight:700;">
                            BECOME A BIFONEX AMBASSADOR
                        </div>

                        <?php
                            // Map each platform to the user's latest ambassador application.
                            // $socials is already ordered latest-first.
                            $platformStatuses = [];
                            foreach ($socials as $s) {
                                if (!isset($platformStatuses[$s->platform])) {
                                    $platformStatuses[$s->platform] = $s;
                                }
                            }
                        ?>

                        <div class="amb-grid">
                            <?php $__currentLoopData = ['Instagram','Twitter','TikTok','YouTube','Support','Creator']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $mySocial = $platformStatuses[$platform] ?? null;
                                    $ambStatus = $mySocial ? $mySocial->status : null;
                                ?>
                                <div class="amb-card" style="<?php if($ambStatus==='approved'): ?>background:#065f46;<?php elseif($ambStatus==='pending'): ?>background:#92400e;<?php elseif($ambStatus==='rejected'): ?>background:#991b1b;<?php endif; ?>">
                                    <div style="font-weight:700;margin-bottom:4px;"><?php echo e($platform); ?></div>
                                    <div style="font-size:0.7rem;opacity:0.85;">Promote Bifonex</div>

                                    <?php if($ambStatus === 'approved'): ?>
                                        
                                        <div style="margin-top:6px;font-size:0.6rem;font-weight:700;background:#a7f3d0;color:#065f46;padding:2px 8px;border-radius:999px;display:inline-block;">✓ APPROVED</div>
                                        <?php if($mySocial->profile_link): ?>
                                            <a href="<?php echo e($mySocial->profile_link); ?>" target="_blank" rel="noopener" style="margin-top:8px;display:block;background:#064e3b;color:#d1fae5;padding:6px 8px;border-radius:6px;font-size:0.66rem;font-weight:700;text-decoration:none;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                <i class="fas fa-external-link-alt"></i> View Profile
                                            </a>
                                        <?php endif; ?>
                                        <button disabled style="margin-top:8px;background:#064e3b;color:#a7f3d0;border:none;padding:6px 14px;border-radius:6px;font-size:0.7rem;cursor:not-allowed;opacity:0.9;">Approved ✓</button>
                                    <?php elseif($ambStatus === 'pending'): ?>
                                        
                                        <div style="margin-top:6px;font-size:0.6rem;font-weight:700;background:#fde68a;color:#92400e;padding:2px 8px;border-radius:999px;display:inline-block;">⏳ PENDING</div>
                                        <button disabled style="margin-top:8px;background:#78350f;color:#fde68a;border:none;padding:6px 14px;border-radius:6px;font-size:0.7rem;cursor:not-allowed;">Pending Review</button>
                                    <?php elseif($ambStatus === 'rejected'): ?>
                                        
                                        <div style="margin-top:6px;font-size:0.6rem;font-weight:700;background:#fecaca;color:#991b1b;padding:2px 8px;border-radius:999px;display:inline-block;">✕ REJECTED</div>
                                        <button onclick="openModal('<?php echo e($platform); ?>')" style="margin-top:8px;background:#1e3a8a;color:white;border:none;padding:6px 14px;border-radius:6px;font-size:0.7rem;cursor:pointer;">Re-apply</button>
                                    <?php else: ?>
                                        <button onclick="openModal('<?php echo e($platform); ?>')">Apply now</button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <!-- Official Videos -->
                <div class="card">
                    <div class="card-header">OFFICIAL VIDEOS</div>
                    <div style="padding: 18px;">
                        <?php if($adminVideos->isNotEmpty()): ?>
                            <?php $fv = $adminVideos->first(); ?>
                            <div id="vMain" style="border-radius:8px;overflow:hidden;margin-bottom:12px;">
                                <?php if($fv->video_type === 'youtube' && $fv->youtubeId()): ?>
                                    <iframe width="100%" height="200" src="https://www.youtube.com/embed/<?php echo e($fv->youtubeId()); ?>" allowfullscreen></iframe>
                                <?php else: ?>
                                    <video controls style="width:100%;"><source src="<?php echo e(asset('storage/'.$fv->video_url)); ?>"></video>
                                <?php endif; ?>
                            </div>
                            
                            <div class="video-strip">
                                <?php $__currentLoopData = $adminVideos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="v-thumb <?php echo e($i===0 ? 'active' : ''); ?>" onclick="playV(this,'<?php echo e($v->video_type); ?>','<?php echo e($v->video_type==='youtube' ? $v->youtubeId() : asset('storage/'.$v->video_url)); ?>')">
                                        <?php if($v->video_type === 'youtube' && $v->youtubeId()): ?>
                                            <img src="https://img.youtube.com/vi/<?php echo e($v->youtubeId()); ?>/mqdefault.jpg" style="width:100%;height:100%;object-fit:cover;">
                                        <?php else: ?>
                                            <div style="background:#1e2937;height:100%;display:flex;align-items:center;justify-content:center;color:#64748b;font-size:0.65rem;">Video</div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div style="text-align:center;padding:40px;color:#64748b;">No videos available</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            </div> <!-- END MAIN CONTENT -->

            <!-- RIGHT SIDEBAR: AD BANNERS -->
            <div style="display:flex;flex-direction:column;gap:16px;">
                <?php $__empty_1 = true; $__currentLoopData = $adminBanners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="ad-banner-card">
                        <?php if($banner->image_path): ?>
                            <img src="<?php echo e(asset('storage/'.$banner->image_path)); ?>" 
                                 alt="<?php echo e($banner->title ?? 'Ad Banner'); ?>"
                                 style="width:100%;height:auto;display:block;object-fit:cover;">
                        <?php else: ?>
                            <div style="padding:50px 20px;text-align:center;background:#f1e7ff;color:#581c87;">
                                <i class="fas fa-image fa-2x mb-2"></i>
                                <div style="font-weight:600;"><?php echo e($banner->title ?? 'Ad Banner'); ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <!-- Default placeholder banners -->
                    <div class="ad-banner-card" style="padding:60px 20px;text-align:center;color:#64748b;background:#f8fafc;">
                        <i class="fas fa-ad fa-3x mb-3" style="color:#a855f7;"></i>
                        <div style="font-size:0.95rem;font-weight:700;margin-top:4px;">Ad Space</div>
                    </div>
                    <div class="ad-banner-card" style="padding:60px 20px;text-align:center;color:#64748b;background:#f8fafc;">
                        <i class="fas fa-ad fa-3x mb-3" style="color:#a855f7;"></i>
                        <div style="font-size:0.95rem;font-weight:700;margin-top:4px;">Ad Space</div>
                    </div>
                    <div class="ad-banner-card" style="padding:60px 20px;text-align:center;color:#64748b;background:#f8fafc;">
                        <i class="fas fa-ad fa-3x mb-3" style="color:#a855f7;"></i>
                        <div style="font-size:0.95rem;font-weight:700;margin-top:4px;">Ad Space</div>
                    </div>
                <?php endif; ?>
            </div>

                </div> <!-- END MAIN CONTENT -->

            </div> <!-- END GRID -->

        </div> <!-- END .tl-dashboard -->
        
        </section>
    </div> <!-- END .content-wrapper -->

    <!-- Ambassador Modal -->
    <div id="ambModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);align-items:center;justify-content:center;z-index:99999;">
        <div style="background:white;border-radius:12px;width:380px;max-width:92vw;padding:24px;">
            <h4 style="margin-bottom:16px;">Apply for Ambassador Program</h4>
            <form action="<?php echo e(route('team-leader.socials.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <label>Platform</label>
                <select name="platform" id="mPlat" style="width:100%;padding:8px;margin-bottom:12px;">
                    <option>Instagram</option>
                    <option>Twitter</option>
                    <option>TikTok</option>
                    <option>YouTube</option>
                    <option>Support</option>
                    <option>Creator</option>
                </select>

                <label>Follower Count</label>
                <input type="number" name="views" value="0" style="width:100%;padding:8px;margin-bottom:12px;">

                <label>Profile URL</label>
                <input type="url" name="profile_link" required placeholder="https://..." style="width:100%;padding:8px;margin-bottom:20px;">

                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" onclick="closeModal()" style="padding:8px 18px;background:#f1e7ff;color:#581c87;border:none;border-radius:6px;">Cancel</button>
                    <button type="submit" style="padding:8px 18px;background:#1e40af;color:white;border:none;border-radius:6px;">Submit Application</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Proof of Event Upload Modal -->
    <div id="proofModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);align-items:center;justify-content:center;z-index:99999;">
        <div style="background:white;border-radius:12px;width:460px;max-width:92vw;max-height:90vh;overflow-y:auto;padding:24px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                <h4 style="margin:0;font-size:1rem;">Upload Proof of Event</h4>
                <button type="button" onclick="closeProofModal()" style="background:none;border:none;font-size:1.3rem;cursor:pointer;color:#64748b;line-height:1;">&times;</button>
            </div>
            <p style="font-size:0.75rem;color:#64748b;margin:0 0 14px;">Add photos, documents or videos. You can select multiple files at once.</p>
            <form id="proofForm" action="" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <label style="font-size:0.8rem;font-weight:600;color:#334155;display:block;margin-bottom:4px;">Pictures &amp; Files <span style="color:#ef4444;">*</span></label>
                <input type="file" name="proof_files[]" multiple accept="image/*,application/pdf,video/*" required
                       style="width:100%;font-size:0.8rem;padding:8px;border:1px solid #cbd5e1;border-radius:8px;margin-bottom:4px;box-sizing:border-box;">
                <small style="font-size:0.68rem;color:#94a3b8;display:block;margin-bottom:12px;">Hold Ctrl / Cmd to pick several. Images, PDFs &amp; videos are all allowed together.</small>

                <label style="font-size:0.8rem;font-weight:600;color:#334155;display:block;margin-bottom:4px;">Notes <span style="color:#ef4444;">*</span></label>
                <textarea name="proof_notes" required rows="3" placeholder="Describe what this proof shows..."
                          style="width:100%;font-size:0.8rem;padding:8px;border:1px solid #cbd5e1;border-radius:8px;margin-bottom:14px;box-sizing:border-box;"></textarea>

                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" onclick="closeProofModal()" style="padding:8px 16px;background:#f1f5f9;color:#475569;border:none;border-radius:8px;font-weight:600;cursor:pointer;">Cancel</button>
                    <button type="submit" style="padding:8px 16px;background:#0f766e;color:white;border:none;border-radius:8px;font-weight:700;cursor:pointer;">
                        <i class="fas fa-cloud-upload-alt"></i> Upload Proof
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Image Lightbox -->
    <div id="imgLightbox" onclick="closeLightbox()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.88);z-index:999999;align-items:center;justify-content:center;padding:20px;">
        <button onclick="event.stopPropagation(); closeLightbox()" style="position:fixed;top:14px;right:24px;color:#fff;font-size:2.2rem;line-height:1;background:none;border:none;cursor:pointer;z-index:1000000;">&times;</button>
        <img id="lightboxImg" src="" alt="" onclick="event.stopPropagation()" style="max-width:92%;max-height:90%;border-radius:8px;box-shadow:0 8px 30px rgba(0,0,0,0.6);object-fit:contain;">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Countdown Timer
        function startCountdown() {
            <?php if($expiryDate): ?>
                var endDate = new Date('<?php echo e($expiryDate->toIso8601String()); ?>').getTime();
            <?php else: ?>
                var endDate = Date.now() + (60 * 86400000);
            <?php endif; ?>

            function update() {
                var now = Date.now();
                var distance = Math.max(0, endDate - now);

                document.getElementById('cd-d').innerText = Math.floor(distance / (1000 * 60 * 60 * 24));
                document.getElementById('cd-h').innerText = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                document.getElementById('cd-m').innerText = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                document.getElementById('cd-s').innerText = Math.floor((distance % (1000 * 60)) / 1000);
            }
            update();
            setInterval(update, 1000);
        }
        startCountdown();

        // Toggle between Plan and Zoom
        function toggle(type) {
            document.getElementById('form-type').value = type;
            document.getElementById('fp').style.display = (type === 'plan') ? 'block' : 'none';
            document.getElementById('fz').style.display = (type === 'zoom') ? 'block' : 'none';
            
            document.getElementById('tb-plan').classList.toggle('active', type === 'plan');
            document.getElementById('tb-zoom').classList.toggle('active', type === 'zoom');

            // Dynamically manage required attributes
            const fpInputs = document.querySelectorAll('#fp input, #fp textarea');
            const fzInputs = document.querySelectorAll('#fz input');

            fpInputs.forEach(input => {
                if (type === 'plan') {
                    if (input.name !== 'event_image_2[]' && input.name !== 'hotel_location') {
                        input.setAttribute('required', 'required');
                    }
                } else {
                    input.removeAttribute('required');
                }
            });

            fzInputs.forEach(input => {
                if (type === 'zoom') {
                    input.setAttribute('required', 'required');
                } else {
                    input.removeAttribute('required');
                }
            });
        }

        // Ambassador Modal
        function openModal(platform) {
            document.getElementById('mPlat').value = platform;
            document.getElementById('ambModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('ambModal').style.display = 'none';
        }

        // ===== Proof of Event Upload Modal =====
        var proofBaseUrl = "<?php echo e(route('team-leader.events.proof', ['id' => '__PROOFID__'])); ?>";
        function openProofModal(eventId) {
            var form = document.getElementById('proofForm');
            form.action = proofBaseUrl.replace('__PROOFID__', eventId);
            form.reset();
            document.getElementById('proofModal').style.display = 'flex';
        }
        function closeProofModal() {
            document.getElementById('proofModal').style.display = 'none';
        }

        // ===== Image Lightbox =====
        function openLightbox(src) {
            var lb = document.getElementById('imgLightbox');
            document.getElementById('lightboxImg').src = src;
            lb.style.display = 'flex';
        }
        function closeLightbox() {
            document.getElementById('imgLightbox').style.display = 'none';
            document.getElementById('lightboxImg').src = '';
        }
        // Close lightbox on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') { closeLightbox(); }
        });

        // ===== Event History pagination (3 per page) =====
        var eventPageSize = 4;
        var eventCurrentPage = 1;

        function renderEventPage() {
            var rows = document.querySelectorAll('.event-row');
            var total = rows.length;
            var totalPages = Math.max(1, Math.ceil(total / eventPageSize));

            if (eventCurrentPage > totalPages) { eventCurrentPage = totalPages; }

            var start = (eventCurrentPage - 1) * eventPageSize;
            rows.forEach(function(row, i) {
                row.style.display = (i >= start && i < start + eventPageSize) ? '' : 'none';
            });

            var info = document.getElementById('eventPageInfo');
            var prev = document.getElementById('eventPrev');
            var next = document.getElementById('eventNext');
            var pager = document.getElementById('eventPager');

            if (info) { info.textContent = total > 0 ? ('Page ' + eventCurrentPage + ' of ' + totalPages) : ''; }
            if (prev) { prev.style.visibility = (eventCurrentPage <= 1) ? 'hidden' : 'visible'; }
            if (next) { next.style.visibility = (eventCurrentPage >= totalPages) ? 'hidden' : 'visible'; }
            if (pager) { pager.style.display = (total <= eventPageSize) ? 'none' : 'flex'; }
        }

        function eventPrevPage() {
            if (eventCurrentPage > 1) { eventCurrentPage--; renderEventPage(); }
        }

        function eventNextPage() {
            var rows = document.querySelectorAll('.event-row');
            var totalPages = Math.max(1, Math.ceil(rows.length / eventPageSize));
            if (eventCurrentPage < totalPages) { eventCurrentPage++; renderEventPage(); }
        }

        // Video player
        function playV(el, type, src) {
            document.querySelectorAll('.v-thumb').forEach(x => x.classList.remove('active'));
            el.classList.add('active');

            const container = document.getElementById('vMain');
            if (type === 'youtube') {
                container.innerHTML = `<iframe width="100%" height="200" src="https://www.youtube.com/embed/${src}" allowfullscreen></iframe>`;
            } else {
                container.innerHTML = `<video controls autoplay style="width:100%;"><source src="${src}"></video>`;
            }
        }

        // Initialize default toggle
        document.addEventListener('DOMContentLoaded', function() {
            const fp = document.getElementById('fp');
            const fz = document.getElementById('fz');
            if (fp && fz) {
                fp.style.display = 'block';
                fz.style.display = 'none';
            }

            // Initialize Task Progress
            initTaskUI();

            // Initialize Event History pagination
            renderEventPage();
        });

        // ===== PERSISTENT TASK SYSTEM (server-backed) =====
        // Ticks are saved to the server so they survive logout / device changes
        // and feed the admin Performance Monitoring view.
        function getCsrfToken() {
            const meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.getAttribute('content') : '';
        }

        function applyTaskState(item, checked) {
            const statusEl = item.querySelector('.task-status');
            if (checked) {
                item.style.opacity = '0.75';
                statusEl.innerHTML = '<span style="color:#16a34a;font-weight:600;">✓ Done</span>';
            } else {
                item.style.opacity = '1';
                statusEl.innerHTML = 'Pending';
            }
        }

        function updateTaskProgress(completed, total) {
            const progressBar = document.getElementById('task-progress-bar');
            const progressText = document.getElementById('task-progress-text');
            const countBadge = document.getElementById('task-count-badge');
            if (!progressBar) return;

            const pct = total > 0 ? Math.round((completed / total) * 100) : 0;
            progressBar.style.width = pct + '%';
            progressBar.style.background = (completed === total && total > 0)
                ? 'linear-gradient(to right, #16a34a, #4ade80)'
                : 'linear-gradient(to right, #1e40af, #3b82f6)';

            if (progressText) progressText.innerHTML = completed + '/' + total;
            if (countBadge)   countBadge.innerHTML   = completed + '/' + total + ' done';
        }

        function setAllTasksState() {
            const cbs = document.querySelectorAll('#task-list input[type="checkbox"]');
            let done = 0;
            cbs.forEach(cb => {
                applyTaskState(cb.closest('.task-item'), cb.checked);
                if (cb.checked) done++;
            });
            updateTaskProgress(done, cbs.length);
        }

        function toggleTask(checkbox) {
            const item = checkbox.closest('.task-item');
            const hash = checkbox.getAttribute('data-hash');
            const isNowChecked = checkbox.checked;

            // Optimistic UI update
            applyTaskState(item, isNowChecked);
            const cbs = document.querySelectorAll('#task-list input[type="checkbox"]');
            let done = 0;
            cbs.forEach(cb => { if (cb.checked) done++; });
            updateTaskProgress(done, cbs.length);

            fetch('<?php echo e(route("team-leader.task-toggle")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ task_hash: hash, completed: isNowChecked }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Use authoritative counts returned by the server.
                    updateTaskProgress(data.completed_count, data.total_count);
                } else {
                    // Revert on failure.
                    checkbox.checked = !isNowChecked;
                    setAllTasksState();
                    alert(data.message || 'Could not save this task. Please try again.');
                }
            })
            .catch(() => {
                checkbox.checked = !isNowChecked;
                setAllTasksState();
                alert('Network error — your task was not saved. Please try again.');
            });
        }

        function initTaskUI() {
            const cbs = document.querySelectorAll('#task-list input[type="checkbox"]');
            if (!cbs.length) return;

            // Paint initial state from the server-rendered checkboxes.
            setAllTasksState();

            // Click anywhere on the task row to toggle (not on the checkbox/label itself).
            cbs.forEach(cb => {
                const item = cb.closest('.task-item');
                item.addEventListener('click', function(e) {
                    if (e.target.tagName === 'INPUT' || e.target.tagName === 'LABEL') return;
                    cb.checked = !cb.checked;
                    toggleTask(cb);
                });
            });
        }
    </script>
</div><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/team-leader/all.blade.php ENDPATH**/ ?>