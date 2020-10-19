<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="hero-body">
        <div class="container">
            <h1 class="title is-1" style="color : #0074D9;"> a User</h1>
        </div>
    </div>
    <div class="columns is-centered">
        <form action="<?php echo URLROOT;?>users/index" method="post" class="box">
            <div class="field">
                <label class="label">Username: <sup>*</sup></label>
                <div class="control">
                    <input class="input <?php echo (!empty($data['name'])) ? 'is-success' : ''?>" type="text" name="name" placeholder="Your_name" value="<?php echo $data['name']?>">
                    <p class="help is-danger"><?php echo $data['name_err']?></p>
                </div>
            </div>
            <div class="field">
                <label class="label">Email: <sup>*</sup></label>
                <div class="control">
                    <input class="input <?php echo (!empty($data['email'])) ? 'is-success' : ''?>" type="text" name="email" placeholder="Your_email@gmail.com" value="<?php echo $data['email']?>">
                    <p class="help is-danger"><?php echo $data['email_err']?></p>
                </div>
            </div>
            <div class="field">
                <label class="label">Password: <sup>*</sup></label>
                <div class="control">
                    <input class="input <?php echo (!empty($data['password'])) ? 'is-success' : ''?>" type="password" name="password" placeholder="Your_password" value="<?php echo $data['password']?>">
                    <p class="help is-danger"><?php echo $data['password_err']?></p>
                </div>
            </div>
            <div class="field">
                <label class="label">Confirm password: <sup>*</sup></label>
                <div class="control">
                    <input class="input <?php echo (!empty($data['confirm_password'])) ? 'is-success' : ''?>" type="password" name="confirm_password" placeholder="Your_password" value="<?php echo $data['confirm_password']?>">
                    <p class="help is-danger"><?php echo $data['confirm_password_err']?></p>
                </div>
            </div>
            <div class="buttons">
                <input class="button is-primary" type="submit" name="submit" value="Register">
                <p>You have an account? <a class="button is-info" href="<?php echo URLROOT;?>users/login">Login</a></p>
            </div>
        </form>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>