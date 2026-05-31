<div>
     <?php echo $__env->make('home.include.user-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <section class="contact-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="contact-text">
                        <h2 style="font-family: Arial, Helvetica, sans-serif;">Contact Info</h2>
                        <p>
                        </p>
                        
                                            <div class="help-center">
                            <h4>Help Center</h4>
                            <div class="address">
                                <div class="simbol">
                                    <img src="<?php echo e(asset('assets/front/img/admin.png')); ?>" alt="">
                                </div>
                                <div class="addr-name">ADMIN: </div>
                                <div class="addr-link">
                                    <div><a href="mailto:info@bifonex.com">info@bifonex.com</a></div>
                                    <div><a href="mailto:support@bifonex.com">support@bifonex.com</a></div>
                                </div>
                                
                            </div>
                            <div class="address">
                                <div class="simbol">
                                    <img src="<?php echo e(asset('assets/front/img/white-house.png')); ?>" alt="">
                                </div>
                                <div class="addr-name">USA TEAM: </div>
                                <div class="addr-link">
                                    <div><a href="mailto:usaupport@bifonex.com">usaupport@bifonex.com</a></div>
                                    <!-- <div><a href="mailto:servicebifonex@gmail.com">servicebifonex@gmail.com</a></div> -->
                                </div>
                            </div>
                            <div class="address ">
                                <tr>
                                    <td>
                                    <div class="simbol">
                                        <img src="<?php echo e(asset('assets/front/img/houses.png')); ?>" alt="">
                                    </div>
                                    </td>
                                    <td>
                                         <div class="addr-name">SUEDE TEAM:</div>

                                    </td>
                                    <td>
                                        <div class="addr-link">
                                            <div><a href="mailto:suedebifonex@bifonex.com">servicebifonex@bifonex.com</a></div>
                                            <!-- <div><a href="mailto:suedesupport@gmail.com">suedesupport@gmail.com</a></div> -->
                                        </div>
                                
                                    </td>
                                </tr>
                               
                                
                            </div>
                            <div class="address">
                                <div class="simbol">
                                    <img src="<?php echo e(asset('assets/front/img/south-africa.png')); ?>" alt="">
                                </div>
                                <div class="addr-name south-team">SOUTHAFRICA TEAM: </div>
                                <div class="addr-link">
                                    <div><a href="mailto:sfsupport@bifonex.com">sfsupport@bifonex.com</a></div>
                                    <!-- <div><a href="mailto:fonepoteam@gmail.com">fonepoteam@gmail.com</a></div> -->
                                </div>
                                
                            </div>
                            <div class="address">
                                 <div class="simbol">
                                    <img src="<?php echo e(asset('assets/front/img/woman.png')); ?>" alt="">
                                </div>
                                <div class="addr-name">KENYA TEAM: </div>
                                <div class="addr-link">
                                    <div><a href="mailto:kesupport@bifonex.com">kesupport@bifonex.com</a></div>
                                    <!-- <div><a href="mailto:servicefonepo@gmail.com">servicefonepo@gmail.com</a></div> -->
                                </div>
                                
                            </div>
                            <div class="address">
                                <div class="simbol">
                                    <img src="<?php echo e(asset('assets/front/img/kigali.png')); ?>" alt="">
                                </div>
                                <div class="addr-name">RWANDA TEAM: </div>
                                <div class="addr-link">
                                    <div><a href="mailto:rosupport@bifonex.com">rosupport@bifonex.com</a></div>
                                    <div><a href="mailto:update@bifonex.com">update@bifonex.com</a></div>
                                </div>
                                
                            </div>
                        </div>
                       
                    </div>
                </div>
                <div class="col-lg-6 offset-lg-1">
                  <?php if($message=="error"): ?>
                   <span class="bg-danger">failed to send message, try again</span>
                   <?php endif; ?>
                    <?php if($message=="success"): ?>
                   <span class="bg-primary">Thank u for Contacting <?php echo e(strtoupper(env('APP_NAME'))); ?>,Your message have been sent successfull to our team.</span>
                   <?php endif; ?>
                   <!--    <?php if($message=="email"): ?>-->
                   <!--<span class="bg-primary"> Email not sent</span>-->
                   <!--<?php endif; ?>-->
                    <form action="<?php echo e(route('contact.us')); ?>" method="post" class="contact-form">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="text" placeholder="Your First-Name" name="name" required>
                            </div>
                             <div class="col-lg-6">
                                <input type="text" placeholder="YourLast-Name" name="sur">
                            </div>
                            <div class="col-lg-6">
                                <input type="text" placeholder="Your Phone example(+250)" name="phone">
                            </div>
                            <div class="col-lg-6">
                                <input type="email" placeholder="Your Email" name="email" required>
                            </div>
                            <div class="col-lg-12">
                                <textarea placeholder="Your Message" name="message" required></textarea>
                                <button type="submit">Submit Now</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="map" id="map">


                <!-- maplandia.com search-box 1.0 end -->
                 <iframe
                    src="https://www.google.com/maps/embed?pb="
                    height="470" style="border:0;" allowfullscreen="">
                </iframe> 
            </div>
        </div>
    </section>
    <script>
        function initMap() {
            // Check if geolocation is available in the browser
            if (navigator.geolocation) {
                // Get the user's current location
                navigator.geolocation.getCurrentPosition(function (position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;

                    // Create a map centered at the user's location
                    var map = new google.maps.Map(document.getElementById('map'), {
                        center: { lat: latitude, lng: longitude },
                        zoom: 15
                    });

                    // Add a marker at the user's location
                    var marker = new google.maps.Marker({
                        position: { lat: latitude, lng: longitude },
                        map: map
                    });
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }
    </script>
        <!--<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBfL9sqlNZIqFn1Y9zV8jc1T0VmRxeIfdc&callback=initMap"></script>-->
    <!-- Contact Section End -->

<?php echo $__env->make('home.include.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div><?php /**PATH /home/cwkwkueb/mcu.focoin.eu/resources/views/home/contact.blade.php ENDPATH**/ ?>