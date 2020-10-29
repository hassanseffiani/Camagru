<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="hero-body">
        <div class="container">
            <h1 class="title is-1" style="color : #0074D9;">Send a new verification :</h1>
        </div>
    </div>
    <div class="columns is-centered">
        <form action="<?= URLROOT;?>users/send_N_email" method="post" class="box">
            <?php echo flash('verify_failed');?>
            <div class="field">
                <label class="label">Email: <sup>*</sup></label>
                <div class="control">
                    <input class="input <?= (!empty($data['email_err'])) ? 'is-danger' : ''?>" type="text" name="email" placeholder="Your_email@gmail.com" value="<?php echo $data['email']?>">
                    <p class="help is-danger"><?php echo $data['email_err']?></p>
                </div>
            </div>
            <div class="buttons column is-half">
            <input class="button is-primary" type="submit" name="submit" value="Send">
            </div>
        </form>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>