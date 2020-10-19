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
                    <div class="column has-text-right">
                        <div class="media-content has-text-centered">
                            <p class="subtitle is-6 tag is-link is-light"><i class="fi-torso"></i><?php echo "&nbsp;".$data['user']->name;?></p>
                        </div>
                    </div>
                    <div class="card-image">
                        <figure class="has-text-centered">
                            <img src="data:<?php echo $data['post']->type?>;base64,<?php echo $data['post']->img_dir ?>" style="filter : <?php echo $data['post']->filter?> ;" alt="Placeholder image"/>
                        </figure>
                    </div>
                    <div class="content">
                        <time datetime="2016-1-1"><?php echo $post->created_at; ?></time>
                    </div>
                </div>
                <div class="column content">
                    <button id="btn_display_like" onclick="display_like();" class="button is-danger is-outlined is-left">
                    <i class="fi-heart"></i>
                    <p>&nbsp;<?php echo $data['cnt_like']->cnt;?></p>
                    </button>
                    <button id="btn_display_comment" onclick="display_comment();" class="button is-link is-outlined">
                        <i class="fi-comments"></i>
                        <p id="all_comment_p">&nbsp;<?php echo $data['cnt_comment']->cnt;?></p>
                    </button>
                </div>
                <div class="columns">
                   
                    <div class="column is-14" id="like">
                        <header class="card-header">
                            <p class="card-header-title">
                                All Like.
                            </p>
                        </header>
                        <div class="card-table">
                            <div class="content" id="t_scroll">
                                <table class="table is-fullwidth is-striped">
                                    <tbody>
                                        <div class="column content has-right">
                                            <?php foreach($data['like'] as $like) :?>
                                                <div class="media-content">
                                                    <p class="subtitle is-6 tag is-link is-light"><i class="fi-torso"></i><?php echo "&nbsp;".$like->user_name;?></p>
                                                    <p class="subtitle is-6 tag is-success is-light"><?php echo "Like";?>&nbsp;<i class="fi-like"></i></p>
                                                </div>
                                            <?php endforeach;?>
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="column is-14" id="comment">
                        <header class="card-header">
                            <p class="card-header-title">
                                All Comment.
                            </p>
                        </header>
                        <div class="card-table">
                            <div class="content" id="t_scroll">
                                <table class="table is-fullwidth is-striped">
                                    <tbody>
                                        <div class="column content has-right">
                                            <?php $j = 0;?>
                                            <?php foreach($data['list'] as $list) :?>
                                                <div id="elem_to_dlt<?= $j;?>">
                                                    <div class="media-content" id="list_5">
                                                        <p class="subtitle is-6 tag is-link is-light"><i class="fi-torso"></i><?php echo "&nbsp;".$list->user_name;?></p>
                                                        <p class="subtitle is-6 tag is-link is-outlined"><?php echo $list->comment;?></p>
                                                        <button class="button is-small is-danger is-outlined" onclick="dlt_f_ajax(<?= $list->cmt_id;?>, <?= $j;?>);">
                                                            <span class="icon is-small">
                                                                <i class="fas fa-times"></i>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php $j++;?>
                                            <?php endforeach;?>
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($_SESSION['user_name'] === $data['user']->name):?>
                <?php if(is_login_in()) :?>
                    <div class="column">
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
                    </div>
                <?php endif;?>
            <?php endif ;?>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>