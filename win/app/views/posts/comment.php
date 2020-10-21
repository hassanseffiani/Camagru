<?php require APPROOT . '/views/inc/header.php'; ?>
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
                <!-- <div class="column has-text-right"> -->
                            <!-- <p class="subtitle is-6 tag is-link is-light"><i class="fi-torso"></i><?php echo "&nbsp;".$_SESSION['user_name'];?></p> -->
                <!-- </div> -->
                <div class="column content">
                    <?php foreach($data['list'] as $list) :?>
                        <form action="<?php echo URLROOT;?>posts/delete_comment/<?php echo $list->cmt_id;?>" method="post">
                            <div class="media-content">
                                <p class="subtitle is-6 tag is-link is-light"><i class="fi-torso"></i><?php echo "&nbsp;".$list->user_name;?></p>
                                <p class="subtitle is-6 tag is-link is-outlined"><?php echo $list->comment;?></p>
                                <button class="button is-small is-danger is-outlined">
                                    <span class="icon is-small">
                                        <i class="fas fa-times"></i>
                                    </span>
                                </button>
                            </div>
                        </form>
                    <?php endforeach;?>
                </div>

            </div>
            </div>
            <div class="column has-text-right">
                <form action="<?php echo URLROOT;?>posts/add_comment/<?php echo $data['post']->id;?>" method="post">
                    <div class="field">
                        <div class="control">
                            <input class="input" <?php echo (!empty($data['comment'])) ? 'is-success' : ''?> type="text" name="comment" placeholder="Add a comment . . ." value="<?php echo $data['comment']?>">
                            <p class="help is-danger"><?php echo $data['comment_err']?></p>
                        </div>
                    </div>
                    <button class="button is-primary is-outlined">
                        <span>Comment</span>
                        <span class="icon is-small">
                            <i class="fi-comment"></i>
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>