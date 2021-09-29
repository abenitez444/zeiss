<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

   <!-- CSRF Token -->
   <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

  <title>Vision ZEISS</title>

  <!-- Scripts -->
  

  <!-- Custom styles for this template -->
  <link rel="stylesheet" href="<?php echo e(asset('css/sb-admin-2.css')); ?>">

  <!-- Bootstrap core CSS -->
  <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template 1 -->
  <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>" />

</head>

<body>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12 text-right">
              <img src="<?php echo e(asset('logo1.png')); ?>" alt="logo_image">
            </div>
            <hr>
        </div>
    </div>

    <?php echo $__env->make('partial.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Main Content -->
    <div class="my-2">
        <hr>
        <div class="col-md-12">
        <div class="row">
            <?php echo $__env->make('flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo e(Session::get('success')); ?>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong><?php echo e(session('success')); ?></strong>
                </div>
            <?php endif; ?>
            <div class="col-md-6 part1">
                <img id="image-login" style="margin: 0 auto; overflow: hidden; float: left; height: 100vh; display: block; width: 50vw; padding: 0;" src="<?php echo e(asset('login.jpg')); ?>">
                
            </div>
            <div class="col-md-6">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
        </div>
    </div>
  <hr>

  <?php echo $__env->make('partial.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor/fontawesome-free/css/all.min.css"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/js/admin/sb-admin-2.js"></script>

</body>

</html>
<?php /**PATH /home/abenitez444/localhost/CarlSeizz/resources/views/layouts/app.blade.php ENDPATH**/ ?>