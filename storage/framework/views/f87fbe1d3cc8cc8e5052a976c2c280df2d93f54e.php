<div class="wrapper">
    <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- AGGRESSIVE FIX FOR SIDEBAR OVERLAP -->
    <style>
        /* Force the content to the right of the sidebar */
        body .content-wrapper,
        body .wrapper > .content-wrapper {
            margin-left: 260px !important;
            padding-top: 70px !important;
            min-height: 100vh;
            padding-right: 20px;
        }
        
        /* Mobile fix */
        @media (max-width: 991.98px) {
            body .content-wrapper {
                margin-left: 0 !important;
            }
        }
        
        .tl-dashboard {
            padding: 20px;
        }
        
        /* Make sure sidebar doesn't bleed */
        .main-sidebar {
            z-index: 1030 !important;
        }
        
        /* Extra safety for this specific page */
        .tl-dashboard {
            position: relative;
            z-index: 1;
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
            border: 2px solid #f87171;
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
            font-size: 0.75rem;
            display: block;
            margin-bottom: 4px;
            color: #94a3b8;
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
            border: 2px solid transparent;
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
    <div class="content-wrapper" style="margin-left: 260px !important; padding-top: 70px !important;">
        <section class="content">
            
            <div class="tl-dashboard">
                <!-- HEADER -->
                <div class="tl-header">
                    <h1>Team apply dashboard</h1>
                </div>

                <div style="max-width: 1400px; margin: 0 auto; padding: 0 20px; display: grid; grid-template-columns: 1fr 180px; gap: 20px;">

            <!-- MAIN CONTENT -->
            <div>

            <!-- TOP ROW: COUNTDOWN + RULES -->
            <div style="display: grid; grid-template-columns: 280px 1fr; gap: 20px; margin-bottom: 24px;">
                
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
                            <?php
                                $userLevel = $activation->package ?? ($teamLeader->leadership_level ?? 'TEAM_LEADER');
                                $isSuperLeader = strtoupper($userLevel) === 'SUPER_LEADER';
                            ?>

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

                        <div class="zoom-live" onclick="window.location='https://zoom.us'">
                            <i class="fas fa-video fa-lg"></i>
                            <div>
                                <div style="font-weight:800;color:#f87171;">WE ARE LIVE NOW ON ZOOM</div>
                                <div style="font-size:0.7rem;color:#94a3b8;">CLICK HERE TO JOIN US</div>
                            </div>
                        </div>

                        <!-- PITCH TASK RULES (Compliance) -->
                        <div style="margin-top:16px;border:1px solid #fed7aa;border-radius:8px;background:#fff7ed;padding:14px;">
                            <div style="font-weight:700;color:#c2410f;font-size:0.8rem;margin-bottom:6px;">
                                <i class="fas fa-exclamation-triangle"></i> PITCH TASK RULES
                            </div>
                            <div style="font-size:0.78rem;color:#854d0e;line-height:1.45;">
                                <strong>7.</strong> Validity period: You can claim coupons within 14 days of coupon issuance, and the coupons will be valid for 14 days after they're claimed.<br><br>
                                <strong>8.</strong> Invitations from the same IP address or device will be deemed as self-invitations, which will subsequently lead to the user losing eligibility for both previously acquired and potential future rewards.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ASSIGNED TASKS (from Admin) -->
                <div class="card">
                    <div class="card-header" style="background:#1e40af;color:white;">
                        <i class="fas fa-tasks mr-2"></i> ASSIGNED TASKS
                    </div>
                    <div style="padding: 20px;">
                        <?php if($tasks): ?>
                            <div style="margin-bottom: 12px;">
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                                    <span style="font-weight:700;color:#1e40af;">Your Tasks</span>
                                    <span style="font-size:0.75rem;background:#dbeafe;color:#1e40af;padding:2px 10px;border-radius:999px;"><?php echo e(count(explode("\n", $tasks))); ?> tasks</span>
                                </div>

                                <!-- Interactive Task List -->
                                <div id="task-list" style="display:flex;flex-direction:column;gap:10px;">
                                    <?php $__currentLoopData = explode("\n", trim($tasks)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(trim($task)): ?>
                                        <div class="task-item" style="display:flex;align-items:center;gap:12px;padding:12px 14px;background:#f8fafc;border:1px solid #e0e7ff;border-radius:10px;">
                                            <input type="checkbox" id="task-<?php echo e($index); ?>" 
                                                   onchange="markTaskComplete(this, <?php echo e($index); ?>)"
                                                   style="width:20px;height:20px;accent-color:#1e40af;cursor:pointer;">
                                            <label for="task-<?php echo e($index); ?>" style="flex:1;cursor:pointer;font-size:0.92rem;line-height:1.4;">
                                                <?php echo e(trim($task)); ?>

                                            </label>
                                            <span class="task-status" style="font-size:0.7rem;color:#64748b;white-space:nowrap;">Pending</span>
                                        </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                                <!-- Progress Bar -->
                                <div style="margin-top:16px;">
                                    <div style="display:flex;justify-content:space-between;font-size:0.75rem;margin-bottom:4px;">
                                        <span style="color:#64748b;">Progress</span>
                                        <span id="task-progress-text">0/<?php echo e(count(explode("\n", trim($tasks)))); ?></span>
                                    </div>
                                    <div style="background:#e0e7ff;height:8px;border-radius:999px;overflow:hidden;">
                                        <div id="task-progress-bar" 
                                             style="height:100%;width:0%;background:linear-gradient(to right,#1e40af,#3b82f6);transition:width 0.3s;"></div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div style="padding:20px;text-align:center;color:#64748b;">
                                <i class="fas fa-clipboard-list fa-2x mb-2" style="opacity:0.4;"></i><br>
                                <strong>No tasks assigned yet.</strong><br>
                                <small>Admin will assign tasks soon.</small>
                            </div>
                        <?php endif; ?>
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
                                <?php $__empty_1 = true; $__currentLoopData = $planEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div style="padding:6px 0;border-bottom:1px solid #e2e8f0;font-size:0.85rem;">
                                        <strong><?php echo e($e->title); ?></strong> 
                                        <span style="font-size:0.7rem;padding:1px 8px;border-radius:999px;background:#fef3c7;color:#854d0e;"><?php echo e($e->status); ?></span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div style="color:#64748b;text-align:center;padding:40px 0;">No event plan history.</div>
                                <?php endif; ?>
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

                                        <label>Image 2 <span style="color:#64748b;">optional</span></label>
                                        <input type="file" name="event_image_2" accept="image/*">

                                        <label>Description <span style="color:#f87171;">required</span></label>
                                        <textarea name="description" required rows="2"></textarea>

                                        <label>Event done on <span style="color:#f87171;">required</span></label>
                                        <input type="date" name="event_done_on" required>

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
                                    </div>

                                    <button type="submit" style="width:100%;margin-top:12px;background:#3b82f6;color:white;padding:10px;border:none;border-radius:8px;font-weight:700;">
                                        Submit Report
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Event Images Carousel -->
                    <div style="margin-top:16px;">
                        <div style="display:flex;gap:8px;flex-wrap:wrap;">
                            <?php $__currentLoopData = $eventImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <img src="<?php echo e(asset('storage/'.$img)); ?>" style="width:90px;height:70px;object-fit:cover;border-radius:6px;border:2px solid #e2e8f0;">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($eventImages->isEmpty()): ?>
                                <span style="color:#94a3b8;font-size:0.8rem;">No event photos yet.</span>
                            <?php endif; ?>
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
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                
                <!-- Ambassador Program -->
                <div class="card">
                    <div class="card-header">AMBASSADOR PROGRAM</div>
                    <div style="padding: 18px;">
                        <div style="background:#1e3a8a;color:white;padding:10px 14px;border-radius:8px;text-align:center;margin-bottom:14px;font-weight:700;">
                            BECOME A BIFONEX AMBASSADOR
                        </div>

                        <div class="amb-grid">
                            <?php $__currentLoopData = ['Instagram','Twitter','TikTok','YouTube','Support','Creator']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="amb-card">
                                    <div style="font-weight:700;margin-bottom:4px;"><?php echo e($platform); ?></div>
                                    <div style="font-size:0.7rem;opacity:0.85;">Promote Bifonex</div>
                                    <button onclick="openModal('<?php echo e($platform); ?>')">Apply now</button>
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
            <div style="display:flex;flex-direction:column;gap:14px;">
                <?php $__empty_1 = true; $__currentLoopData = $adminBanners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div style="background:white;border-radius:12px;box-shadow:var(--shadow-2);overflow:hidden;">
                        <?php if($banner->image_path): ?>
                            <img src="<?php echo e(asset('storage/'.$banner->image_path)); ?>" 
                                 alt="<?php echo e($banner->title ?? 'Ad Banner'); ?>"
                                 style="width:100%;height:auto;display:block;object-fit:cover;">
                        <?php else: ?>
                            <div style="padding:40px 20px;text-align:center;background:#f1e7ff;color:#581c87;">
                                <i class="fas fa-image fa-2x mb-2"></i>
                                <div style="font-weight:600;"><?php echo e($banner->title ?? 'Ad Banner'); ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <!-- Default placeholder banners -->
                    <div style="background:#f8fafc;border-radius:12px;box-shadow:var(--shadow-2);padding:40px 20px;text-align:center;color:#64748b;">
                        <i class="fas fa-ad fa-2x mb-2"></i>
                        <div style="font-size:0.85rem;">Ad Space</div>
                    </div>
                    <div style="background:#f8fafc;border-radius:12px;box-shadow:var(--shadow-2);padding:40px 20px;text-align:center;color:#64748b;">
                        <i class="fas fa-ad fa-2x mb-2"></i>
                        <div style="font-size:0.85rem;">Ad Space</div>
                    </div>
                    <div style="background:#f8fafc;border-radius:12px;box-shadow:var(--shadow-2);padding:40px 20px;text-align:center;color:#64748b;">
                        <i class="fas fa-ad fa-2x mb-2"></i>
                        <div style="font-size:0.85rem;">Ad Space</div>
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
        }

        // Ambassador Modal
        function openModal(platform) {
            document.getElementById('mPlat').value = platform;
            document.getElementById('ambModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('ambModal').style.display = 'none';
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
            initTaskProgress();
        });

        // ===== INTERACTIVE TASK SYSTEM =====
        function initTaskProgress() {
            const taskItems = document.querySelectorAll('#task-list .task-item');
            if (!taskItems.length) return;

            // Load saved state from localStorage
            const savedState = JSON.parse(localStorage.getItem('teamLeaderTasks') || '{}');

            let completed = 0;
            const total = taskItems.length;

            taskItems.forEach((item, index) => {
                const checkbox = item.querySelector('input[type="checkbox"]');
                const statusEl = item.querySelector('.task-status');

                if (savedState[index]) {
                    checkbox.checked = true;
                    item.style.opacity = '0.75';
                    statusEl.innerHTML = `<span style="color:#16a34a;font-weight:600;">✓ Done</span>`;
                    completed++;
                }

                // Click anywhere on the task row to toggle
                item.addEventListener('click', function(e) {
                    if (e.target.tagName === 'INPUT') return;
                    checkbox.checked = !checkbox.checked;
                    checkbox.dispatchEvent(new Event('change'));
                });
            });

            updateProgressBar(completed, total);
        }

        function markTaskComplete(checkbox, index) {
            const item = checkbox.closest('.task-item');
            const statusEl = item.querySelector('.task-status');

            if (checkbox.checked) {
                item.style.transition = 'all 0.3s';
                item.style.opacity = '0.75';
                statusEl.innerHTML = `<span style="color:#16a34a;font-weight:600;">✓ Done</span>`;

                // Save to localStorage
                const saved = JSON.parse(localStorage.getItem('teamLeaderTasks') || '{}');
                saved[index] = true;
                localStorage.setItem('teamLeaderTasks', JSON.stringify(saved));
            } else {
                item.style.opacity = '1';
                statusEl.innerHTML = `Pending`;

                const saved = JSON.parse(localStorage.getItem('teamLeaderTasks') || '{}');
                delete saved[index];
                localStorage.setItem('teamLeaderTasks', JSON.stringify(saved));
            }

            // Update progress
            const allCheckboxes = document.querySelectorAll('#task-list input[type="checkbox"]');
            let done = 0;
            allCheckboxes.forEach(cb => { if (cb.checked) done++; });

            updateProgressBar(done, allCheckboxes.length);
        }

        function updateProgressBar(completed, total) {
            const progressBar = document.getElementById('task-progress-bar');
            const progressText = document.getElementById('task-progress-text');

            if (!progressBar || !progressText) return;

            const percentage = total > 0 ? Math.round((completed / total) * 100) : 0;

            progressBar.style.width = percentage + '%';
            progressText.innerHTML = `${completed}/${total}`;

            // Color change when all complete
            if (completed === total && total > 0) {
                progressBar.style.background = 'linear-gradient(to right, #16a34a, #4ade80)';
                progressText.style.color = '#16a34a';
                progressText.style.fontWeight = '700';
            }
        }
    </script>
</div><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/team-leader/all.blade.php ENDPATH**/ ?>