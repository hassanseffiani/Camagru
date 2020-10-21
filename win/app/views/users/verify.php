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
<?php require APPROOT . '/views/inc/footer.php'; ?>