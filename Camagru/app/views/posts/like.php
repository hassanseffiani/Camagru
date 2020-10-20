<!-- <?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="hero-body">
        <div class="container">
            <a href="<?php echo URLROOT;?>posts" class="button is-link is-outlined has-text-left">
                <span class="icon">
                    <i class="fi-arrow-left"></i>
                </span>
                <p class="containt">Back</p>
            </a>
            <div class="card">
                <div class="crad-image">
                    <div class="column has-text-right">
                        <div class="media-content has-text-centered">
                                <p class="subtitle is-6 tag is-link is-light"><i class="fi-torso"></i><?php echo "&nbsp;".$data['user']->name;?></p>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <?php foreach($data['like'] as $like) :?>
                        <div class="media-content">
                            <p class="subtitle is-6 tag is-link is-light"><i class="fi-torso"></i><?php echo "&nbsp;".$like->user_name;?></p>
                            <p class="subtitle is-6 tag is-success is-light"><?php echo "Like";?>&nbsp;<i class="fi-like"></i></p>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
            <div class="column has-text-right">
                <form action="<?php echo URLROOT;?>posts/add_like/<?php echo $data['post']->id;?>" method="post">
                    <button class="button is-primary is-outlined" onclick="like();">
                        <span>Like</span>
                        <span class="icon is-small">
                            <i class="fi-like"></i>
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?> -->