<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="hero-body">
        <div class="container">
            <h1 class="title is-1" style="color : #0074D9;">Add Post :</h1>
        </div>
    </div>
    <div class="columns is-centered">
        <form action="<?php echo URLROOT;?>posts/add" method="post" class="box">
            <a href="<?php echo URLROOT;?>posts" class="button is-link has-text-left">
                <span class="icon">
                    <img src="https://raw.githubusercontent.com/hassanreus/img/master/undo.png"></img>
                </span>
                <p class="containt">Back</p>
            </a>
            <div class="buttons">
                <!-- <input class="button is-primary" type="submit" name="submit" value="Add"> -->
            </div>
        </form>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>