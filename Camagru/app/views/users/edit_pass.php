<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="hero-body">
        <div class="container">
            <h1 class="title is-1" style="color : #0074D9;">Edit a User</h1>
            <div class="column">
                <a href="<?php echo URLROOT;?>posts" class="button is-link is-outlined has-text-left">
                <span class="icon">
                    <i class="fi-arrow-left"></i>
                </span>
                <p class="containt">Back</p>
                </a>
            </div>
        </div>
    </div>
    <div class="columns is-centered">
        <form action="<?php echo URLROOT;?>users/edit_pass" method="post" class="box">
            <div class="field">
                <label class="label">Old password: <sup>*</sup></label>
                <div class="control">
                    <input class="input <?php echo (!empty($data['old_p'])) ? 'is-success' : ''?>" type="password" name="old_p" placeholder="Your_password" value="<?php echo $data['old_p']?>">
                    <p class="help is-danger"><?php echo $data['old_p_err']?></p>
                </div>
            </div>
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
            </div>
            <div class="buttons column is-center">
                <input class="button is-primary" type="submit" name="submit" value="Edit password">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
            </div>
        </form>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>