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
            <!-- <div class="field">
                <label class="label">Title: <sup>*</sup></label>
                <div class="control">
                    <input class="input <?php echo (!empty($data['title'])) ? 'is-success' : ''?>" type="text" name="title" placeholder="Your_title" value="<?php echo $data['title']?>">
                    <p class="help is-danger"><?php echo $data['title_err']?></p>
                </div>
            </div>
            <div class="field">
                <label class="label">Body: <sup>*</sup></label>
                <div class="control">
                    <textarea rows="9" cols="100" class ="textarea <?php echo (!empty($data['body'])) ? 'is-success' : ''?>" type="text" name="body" placeholder="Your_body"><?php echo $data['body']?></textarea>
                    <p class="help is-danger"><?php echo $data['body_err']?></p>
                </div>
            </div> -->
            <div class="buttons">
                <input class="button is-primary" type="submit" name="submit" value="Add">
            </div>
        </form>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>