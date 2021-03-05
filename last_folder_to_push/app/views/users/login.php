<?php require APPROOT . '/views/inc/header.php'; token();?>
    <div class="hero-body">
        <div class="container">
            <h1 class="title is-1" style="color : #0074D9;">Connect a User</h1>
            <h1 class="title is-6" style="color : #0074D9;"><?= flash('login')?></h1>
            <h1 class="title is-6" style="color : #0074D9;"><?= flash('register_success');?></h1>
            <h1 class="title is-6" style="color : #0074D9;"><?= flash('send_N_mail');?></h1>
        </div>
    </div>
    <div class="columns is-centered">
        <form action="<?php echo URLROOT;?>users/login" method="post" class="box">
            <p class="help is-danger"><?= $data['verify_err'];?>
            <?php if (!empty($data['verify_err'])) {?>
                <a class="button is-info is-small" href="<?php echo URLROOT ;?>users/send_N_email">Send New Email</a>
            <?php } ?>
            </p>
            <div class="field">
                <label class="label">Username: <sup>*</sup></label>
                <div class="control">
                    <input class="input <?= (!empty($data['Username_err'])) ? 'is-danger' : ''?>" type="text" name="email" placeholder="Username" value="<?php echo $data['Username']?>">
                    <p class="help is-danger"><?php echo $data['Username_err']?></p>
                </div>
            </div>
            <div class="field">
                <label class="label">Password: <sup>*</sup></label>
                <div class="control">
                    <input class="input <?= (!empty($data['password_err'])) ? 'is-danger' : ''?>" type="password" name="password" placeholder="Your_password" value="<?php echo $data['password']?>">
                    <p class="help is-danger"><?= $data['password_err'];?></p>
                </div>
            </div>
            <div class="field">
                <a href="<?php echo URLROOT;?>users/forget">Forget password ?</a>
            </div>
            <div class="buttons">
                <input class="button is-primary" type="submit" name="submit" value="Login">
                <p>No account?  <a class="button is-info" href="<?php echo URLROOT;?>users/index">Sign up</a></p>
            </div>
            <input type="hidden" name="token" id="token" value="<?= $_SESSION['token']; ?>" />
        </form>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>