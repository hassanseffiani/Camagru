<?php require APPROOT . "/views/inc/header.php"; ?>
<div class="hero-body">
  <div class="container">
    <div class="column has-text-left">
      <h1 class="title is-1" style="color : #0074D9;"><?php echo $data['title']?></h1>
      <div class="column has-text-right">
        <?php if (is_login_in()) :?>
          <a class="button is-link is-outlined"  href="<?php echo URLROOT;?>cameras">
            <span class="icon">
              <i class="fi-plus small"></i>
            </span>
            <p class="containt">Add</p>
          </a>
        <?php endif; ?>
      </div>
    </div>
    <h1 class="title is-4" style="color : #0074D9;"><?php echo flash('Post_mssg')?></h1>
    <h1 class="title is-4" style="color : #0074D9;"><?php echo flash('msg_comm')?></h1>
    <?php $j = 0;?>
      <?php for($x = 1; $x <= $data['total_page']; $x++) echo "<a class='pagination-link' href='$x'>$x</a>"?>
    <?php foreach($data['post'] as $post) :?>
      <?php if (!is_login_in()) :?>
        <div class="block">
        <div class="columns body-columns">
          <div class="column is-half is-offset-one-quarter">
            <div class="card">
              <div class="header">
                <div class="crad-image">
                  <div class="column has-text-right">
                    <div class="media-content">
                      <p class="subtitle is-6 tag is-link is-light"><i class="fi-torso"></i><?php echo "&nbsp;".$post->user_name; ?></p>
                    </div>
                  </div>
                  <div class="content">
                    <div class="card-image">
                      <figure>
                        <img src="data:<?php echo $post->type?>;base64,<?php echo $post->img_dir?>" style="filter : <?php echo $post->filter?> ;" alt="image"/>
                      </figure>
                    </div>
                  </div>
                  <div class="content">
                    <time class="tag is-link is-light" datetime="2016-1-1"><?php echo $post->created_at; ?></time>
                  </div>
                </div>
              </div>
              <div class="content">
                <a class="button is-danger is-outlined is-left">
                  <i class="fi-heart"></i>
                  <p>&nbsp;<?php echo $post->cnt_like;?></p>
                </a>
                <a class="button is-link is-outlined">
                  <i class="fi-comments"></i>
                  <p>&nbsp;<?php echo $post->cnt_comm;?></p>
                </a>
              </div>
              <div class="has-text-left">
                  <a href="<?php echo URLROOT;?>posts/like_comment/<?php echo $post->id?>" class="button is-link is-outlined">
                    <span class="icon">
                      <i class="fi-thumbnails"></i>
                    </span>
                    <p class="containt">Show</p>
                  </a>
              </div>
            </div>
          </div>
        </div>
    </div>
<?php else :?>
<div id="listingTable">
<div class="hero-body" name="listingTable">
  <div class="container">
      <div class="block">
        <div class="columns body-columns">
          <div class="column is-half is-offset-one-quarter">
            <div class="card">
              <div class="header">
                <div class="crad-image">
                  <div class="column has-text-right">
                    <div class="media-content">
                      <p class="subtitle is-6 tag is-link is-light"><i class="fi-torso"></i><?php echo "&nbsp;".$post->user_name; ?></p>
                    </div>
                  </div>
                  <div class="content">
                    <div class="card-image">
                      <figure>
                        <img src="data:<?php echo $post->type?>;base64,<?php echo $post->img_dir?>" style="filter : <?php echo $post->filter?> ;" alt="image"/>
                      </figure>
                    </div>
                  </div>
                  <div class="content">
                    <time class="tag is-link is-light" datetime="2016-1-1"><?php echo $post->created_at; ?></time>
                  </div>
                </div>
              </div>
              <div class="content">
                <!--to like-->
                <a onclick="like_ajax_post(<?= $post->id;?>, <?= $j;?>);" class="button is-danger is-outlined is-left">
                  <i class="fi-heart"></i>
                  <p id="like_p<?= $j;?>">&nbsp;<?php echo $post->cnt_like;?></p>
                </a>
                <!--to like-->
                <a onclick="display_comment_post(<?php echo $j;?>);" class="button is-link is-outlined">
                  <i class="fi-comments"></i>
                  <p id="comment_p<?= $j;?>">&nbsp;<?php echo $post->cnt_comm;?></p>
                </a>
                <!--to comment-->
                <div name="form_comment" id="comment_ajax">
                    <div class="field">
                        <div class="control">
                            <input class="input" type="text" name="comment" id="comment_text<?= $j;?>" placeholder="Add a comment . . .">
                        </div>
                    </div>
                    <button class="button is-primary is-outlined" onclick="t_c(<?= $post->id;?>, <?= $j;?>);">
                        <span>Comment</span>
                        <span class="icon is-small">
                            <i class="fi-comment"></i>
                        </span>
                    </button>
                </div>
                <!--to comment-->
              </div>
                <div class="has-text-left">
                  <a href="<?php echo URLROOT;?>posts/like_comment/<?php echo $post->id?>" class="button is-link is-outlined">
                    <span class="icon">
                      <i class="fi-thumbnails"></i>
                    </span>
                    <p class="containt">Show</p>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  <?php $j++;?>
<?php endif;?>
<?php endforeach;?>
<?php require APPROOT . "/views/inc/footer.php"; ?>
