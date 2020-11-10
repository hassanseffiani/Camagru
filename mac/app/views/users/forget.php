<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="hero-body">
        <div class="container">
            <h1 class="title is-1" style="color : #0074D9;">Edit password</h1>
            <div class="column">
                <a href="<?php echo URLROOT;?>users/login" class="button is-link is-outlined has-text-left">
                <span class="icon">
                    <i class="fi-arrow-left"></i>
                </span>
                <p class="containt">Back</p>
                </a>
            </div>
        </div>
    </div>
    <div class="columns is-centered">
        <form action="<?php echo URLROOT;?>users/forget" method="post" class="box">
            <?php echo flash('pass_err');?>
            <div class="field">
                    <label class="label">Email: <sup>*</sup></label>
                    <div class="control">
                        <input class="input <?= (!empty($data['email_err'])) ? 'is-danger' : ''?>" type="text" name="email" placeholder="Your_email@gmail.com" value="<?php echo $data['email']?>">
                        <p class="help is-danger"><?php echo $data['email_err']?></p>
                    </div>
                </div>
            <input class="button is-primary" type="submit" name="submit" value="Edit"><a class="button" id="hidden">asdasdasdaasdas</a>
            </div>
            <input type="hidden" name="token" id="token" value="<?= $data['token']; ?>" />
        </form>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>