<nav class="navbar" role="navigation" aria-label="main navigation">
  <div class="navbar-brand">
    <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
    </a>
  </div>
  <div id="navbarBasicExample" class="navbar-menu">
    <div class="navbar-start">
      <?php if(is_login_in()) :?>
        <a class="navbar-item" href="<?php  echo URLROOT ;?>posts/post">
          <i class="fi-home"></i>
        </a>
      <?php else :?>
        <a class="navbar-item" href="<?php echo URLROOT ;?>posts/post">
          <i class="fi-home"></i>
        </a>
      <? endif;?>
      </div>
    <?php if(is_login_in()) :?>
        <div class="navbar-item has-dropdown is-hoverable">
            <a class="navbar-link">
            <p class="tag is-link is-light"> Welcome <?php echo $_SESSION['user_name']; ?></p>
            </a>

            <div class="navbar-dropdown">
              <a class="navbar-item" href="<?php echo URLROOT ;?>users/edit">
                Edit profil
              </a>
            </div>
        </div>
      </div>
      </div>
      <div class="navbar-end">
        <div class="navbar-item">
          <div class="buttons">
            <a class="button is-primary" href="<?php echo URLROOT ;?>users/logout">
              <strong>Log out</strong>
            </a>
          </div>
        </div>
      </div>
    <?php else : ?>
      <div class="navbar-end">
        <div class="navbar-item">
          <div class="buttons">
            <a class="button is-primary" href="<?php echo URLROOT ;?>users/register">
              <strong>Sign up</strong>
            </a>
            <a class="button is-light" href="<?php echo URLROOT ;?>users/login">
              Log in
            </a>
          </div>
        </div>
      </div>
    <?php endif;?>
  </div>
</nav>