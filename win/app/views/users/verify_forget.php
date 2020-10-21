<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="hero-body">
        <div class="container">
            <h1 class="title is-1" style="color : #0074D9;">Verify your account</h1>
        </div>
    </div>
    <div class="columns is-centered">
        <form action="<?= URLROOT;?>users/verify/<?= $data['vkey']?>" method="post">
                <input class="button is-primary" type="submit" name="submit" value="Verify"><a class="button" id="hidden">asd</a>
        </form>
    </div>
    <div class="columns is-centered">
        <form action="<?= URLROOT;?>users/verify_forget/<?= $data['vkey']?>" method="post" class="box">
            <div class="field">
                <label class="label">New password: <sup>*</sup></label>
                <div class="control">
                    <input class="input <?php echo (!empty($data['new_p'])) ? 'is-success' : ''?>" type="password" name="new_p" placeholder="Your_password" value="<?php echo $data['new_p']?>">
                    <p class="help is-danger"><?php echo $data['new_p_err']?></p>
                </div>
            </div>
            <div class="field">
                <label class="label">Confirm password: <sup>*</sup></label>
                <div class="control">
                    <input class="input <?php echo (!empty($data['con_p'])) ? 'is-success' : ''?>" type="password" name="con_p" placeholder="Your_password" value="<?php echo $data['con_p']?>">
                    <p class="help is-danger"><?php echo $data['con_p_err']?></p>
                </div>
            
            <div class="buttons column is-half">
            <input class="button is-primary" type="submit" name="submit" value="Edit"><a class="button" id="hidden">asdasdasdaasdasdasdasdsd</a>
            </div>
        </form>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>