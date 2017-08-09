<?php include 'header.html'; ?>
<form role="form" action="#" method="POST">

    <div class="container">
        <div class="row">
            <div class="" id="loginModal">
                <div class="modal-body">
                    <div class="well">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#login" data-toggle="tab">Login</a></li>
                            <li><a href="#create" data-toggle="tab">Create Account</a></li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div class="tab-pane active in" id="login" > 
                                <form class="form-horizontal" action='' method="POST">
                                    <div class="panel panel-default" >	
                                        <div class="panel-heading" style="text-align:center;#background:#ea0000;"><h3>Login to your account.</h3></div>	
                                        <div class="panel-body" style="text-align:center;" >
                                            <fieldset> 
                                                <div class="row">
                                                    <div class="center-block">
                                                        <img class="profile-img"
                                                             src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120" alt="">
                                                    </div>
                                                </div>
                                                <div class="row" >
                                                    <div class="col-sm-4 "></div>
                                                    <div class="col-sm-4 ">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="glyphicon glyphicon-user"></i>
                                                                </span> 
                                                                <input class="form-control" placeholder="Username" name="loginname" type="text" autofocus>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="glyphicon glyphicon-lock"></i>
                                                                </span>
                                                                <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <button class="btn btn-primary">Login</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 "></div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="panel-footer ">
                                            Don't have an account? <a href="#" onClick=""> Sign Up </a>
                                        </div>
                                    </div>
                            </div>
                            <div class="tab-pane fade" id="create">
                                <div class="panel panel-default">		
                                    <div class="panel-body" style="text-align:center;">
                                        <div><h1>Register</h1></div>
                                        <div class="row">
                                            <div class="col-sm-4 "></div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="glyphicon glyphicon-user"></i>
                                                            </span> 
                                                            <input class="form-control" placeholder="Username" name="loginname" type="text" autofocus>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-pencil"></i>
                                                            </span> 
                                                            <input class="form-control" placeholder="FirstName" name="firstName" type="text" autofocus>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-pencil"></i>
                                                            </span> 
                                                            <input class="form-control" placeholder="Lastname" name="lastname" type="text" autofocus>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-envelope"></i>
                                                            </span> 
                                                            <input class="form-control" placeholder="Email" name="email" type="text" autofocus>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <button class="btn btn-primary">Create Account</button>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 "></div>
                                        </div>
                                    </div>
                                    <div class="panel-footer ">
                                        Already have an account? <a href="#" onClick=""> Sign In</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</form>
</div>
</div>
</div>
</div>
<?php include 'footer.html'; ?>