<link rel="stylesheet" href="about.css">
<div class="container my-5" style="height: 1250px">
    <div class="row my-5">
        <div class="col-md-12">
            <div class="card rounded-0 card-outline card-green shadow">
                <div class="card-body rounded-0">
                    <!-- About Section -->
                    <h2 class="text-center">About</h2>
                    <center><hr class="bg-navy border-navy w-25 border-2"></center>
                    <div>
                        <center>
                            <?= file_get_contents("about_us.html") ?>
                        </center>
                    </div>
                    
                    <!-- Contact Section -->
                    <h2 class="text-center">Contact</h2>
                    <center><hr class="bg-navy border-navy w-25 border-2"></center>
                    <div class="card-body rounded-0">
                        <dl class="row text-center">
                            <dt class="col-12 col-md-2 text-muted"><i class="fa fa-envelope"></i> Email</dt>
                            <dd class="col-12 col-md-2"><?= $_settings->info('email') ?></dd>
                            <dt class="col-12 col-md-2 text-muted"><i class="fa fa-globe"></i> Website</dt>
                            <dd class="col-12 col-md-2"><?= $_settings->info('contact') ?></dd>
                            <dt class="col-12 col-md-2 text-muted"><i class="fa fa-map-marked-alt"></i> Location</dt>
                            <dd class="col-12 col-md-2"><?= $_settings->info('address') ?></dd>
                        </dl>
                    </div>

                    <!-- Meet Our Team Section -->
                    <h2 class="text-center">Meet Our Team</h2>
                    <center><hr class="bg-navy border-navy w-25 border-2"></center>
                    <div class="row justify-content-center" style="transform: scale(0.8)">
                        <div class="col-12 col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="./uploads/devs/1.png" class="card-img-top" alt="Blossom" style="object-fit: cover;" width="100%" height="400px">
                                <div class="card-body text-center">
                                    <h4><strong>Jade Lester Mapola</strong></h4>
                                    <p><i>Bachelor of Science in Information Technology</i></p>
                                    <p>BATCH 2025</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="./uploads/devs/2.jpg" class="card-img-top" alt="Bubbles" style="object-fit: cover;" width="100%" height="400px">
                                <div class="card-body text-center">
                                    <h4><strong>Francess Almocera</strong></h4>
                                    <p><i>Bachelor of Science in Information Technology</i></p>
                                    <p>BATCH 2025</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="./uploads/devs/3.jpg" class="card-img-top" alt="Buttercup" style="object-fit: cover;" width="100%" height="400px">
                                <div class="card-body text-center">
                                    <h4><strong>Heleina Althea Hinagpis</strong></h4>
                                    <p><i>Bachelor of Science in Information Technology</i></p>
                                    <p>BATCH 2025</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
