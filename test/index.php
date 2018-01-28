<!DOCTYPE html>
<html lang="ru">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"/>
    <style>
      section{
        margin-top: 30px;
      }
    </style>
</head>
<body>
<div class="container ">
    <div class="row justify-content-center">
        <div class="col-sm-6 col-12 align-self-center">
            <section>
              <h1>Login</h1>
              <form action="/user/login" method="POST">
                  <div class="form-group">
                     <label class="form-control-label">Username</label>
                     <input type="text"
                            placeholder="Username"
                            name="username"
                            class="form-control">
                 </div>

                 <div class="form-group">
                    <label class="form-control-label">Password</label>
                    <input type="password"
                           placeholder="Password"
                           name="password"
                           class="form-control">
                </div>

                <div class="form-group text-center">
                   <button type="submit" name="submit" class="btn w-100 btn-primary">Login</button>
               </div>
              </form>
          </section>
<hr>
          <section>
            <h1>Logout</h1>
            <form action="/user/logout" method="POST">
              <div class="form-group text-center">
                 <button type="submit" name="submit" class="btn w-100 btn-primary">Logout</button>
             </div>
            </form>
        </section>
          <hr>
          <section>
            <h1>Register</h1>
            <form action="/user/create" method="POST">
                <div class="form-group">
                   <label class="form-control-label">Username</label>
                   <input type="text"
                          placeholder="Username"
                          name="username"
                          class="form-control">
               </div>

               <div class="form-group">
                  <label class="form-control-label">Password</label>
                  <input type="password"
                         placeholder="Password"
                         name="password"
                         class="form-control">
              </div>

              <div class="form-group text-center">
                 <button type="submit" name="submit" class="btn w-100 btn-primary">Register</button>
             </div>
            </form>
        </section>
<hr>
        <section>
          <h1>Recipes</h1>
          <form action="/recipe/index" method="POST">
            <div class="form-group text-center">
               <button type="submit" name="submit" class="btn w-100 btn-primary">Get User Recipes</button>
           </div>
          </form>
      </section>


<hr>
    <section>
      <h1>Create Recipe</h1>
      <form action="/recipe/create" method="POST">
        <div class="form-group">
           <label class="form-control-label">Name</label>
           <input type="text"
                  placeholder="Name"
                  name="name"
                  class="form-control">
       </div>
       <div class="form-group">
           <label class="form-control-label">Image</label>
           <input type="file"
                  class="form-control-file"
                  name="photo"
           />
       </div>
        <div class="form-group text-center">
           <button type="submit" name="submit" class="btn w-100 btn-primary">Create</button>
       </div>
      </form>
    </section>

        </div>
        <div class="col-sm-6 col-12 align-self-center">
          <section>
              <h1>Result</h1>
              <textarea class="form-control" id="result" style="width: 100%;height: 300px;"></textarea>
          </section>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
  (function ($) {

    $('form').on('submit', function(e){
      e.preventDefault();
       var formData = new FormData(this);

       $.ajax({
           url: $(this).attr('action'),
           type: 'POST',
           data: formData,
           cache: false,
           contentType: false,
           processData: false
       }).done(function( data ) {
          var jsondata = JSON.stringify(data);
          $('#result').html(jsondata);
        });
    });
  }(jQuery));
</script>
</body>
</html>
