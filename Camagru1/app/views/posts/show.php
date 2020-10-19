<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="hero-body">
        <div class="container">
        <?php if (is_login_in()) :?>
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
                <div class="card-image">
                    <figure class="has-text-centered">
                        <img src="data:<?php echo $data['post']->type?>;base64,<?php echo $data['post']->img_dir ?>" alt="Placeholder image"/>
                    </figure>
                </div>
                <div class="content">
                    <time datetime="2016-1-1"><?php echo $post->created_at; ?></time>
                </div>
            </div>
        </div>
        <div class="column has-text-right">
            <form action="<?php echo URLROOT;?>posts/delete/<?php echo $data['post']->id; ?>" method="post">
                <button class="button is-danger is-outlined">
                    <span>Delete</span>
                    <span class="icon is-small">
                        <i class="fas fa-times"></i>
                    </span>
                    </button>
            </form>
        </div>
    <?php endif; ?>
    </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>