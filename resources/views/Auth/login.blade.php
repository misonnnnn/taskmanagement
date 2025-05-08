<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
    <div class="vw-100 vh-100 d-flex justify-content-center align-items-center">
        <div class="row w-100">
            <div class="col-xl-4 col-lg-4 col-md-8 col-sm-12 col-12 mx-auto">
                @if ($errors->any())
                    <div>
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger d-flex">
                                <box-icon color="#440000" class="me-2" name='comment-x'></box-icon><p class="m-0"> {{ $error }} </p>
                            </div>
                        @endforeach
                    </div>
                @endif
                <h3 class="text-center">Task Management System</h3>
                    <form method="POST" action="">
                    @csrf
                    <div class="w-100 shadow p-4">
                        <div class="bg-light  w-100">
                            <h5>Login <i class="fa fa-key"></i></h5>
                        </div>
                        <hr>
                        <div>
                            <div class="mb-2">
                                <label>Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control form-control-sm">
                            </div>
                            <div class="mb-2">
                                <label>Password</label>
                                <div class="position-relative">
                                    <input type="password" id="password" name="password" class="col login_input passwordField form-control form-control-sm" required placeholder="password">
                                    <div class="position-absolute end-0 showPassword" style="top:5px;margin-right:10px;">
                                        <i class="showPasswordEyeIcon fa fa-eye-slash"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <input type="submit" value="Login" class="btn btn-sm text-light primary_background">
                                <a href="{{ URL('signup') }}" class="btn btn-sm btn-default ">Sign up</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>


<script>
    $(document).on('click','.showPassword',function(){
        let passwordField = $(this).siblings('.passwordField');
        let icon = $(this).find('.showPasswordEyeIcon'); 
        
        if (passwordField.attr('type') === 'password') {
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
            passwordField.attr('type', 'text');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');

        }
    })

</script>