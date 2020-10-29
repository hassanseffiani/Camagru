<?php require APPROOT . "/views/inc/header.php"; ?>
    <div class="hero-body">
        <div class="container">
            <h1 class="title is-1" style="color : #0074D9;"><?php echo $data['title']?></h1>
                <?php if(is_login_in()) :?>
                    <div class="columns">
                        <div class="column">
                            <div class="column">
                                <a href="<?php echo URLROOT;?>posts" class="button is-link is-outlined has-text-left">
                                <span class="icon">
                                    <i class="fi-arrow-left"></i>
                                </span>
                                <p class="containt">Back</p>
                                </a>
                            </div>
                            <? $i = 0; foreach($data['arr'] as $arr) : ?>
                                <img id="<?= $i?>" src="data:jpg;base64,<?= $arr?>" style="filter : <?php echo $img->filter?> ;" alt="image" width="15%" onclick="ch_test();"/>
                            <? $i++; endforeach;?>
                            <div class="columns">
                                <div class="column control">
                                    <div class="select is-primary">
                                        <select id="photo-filter" class="control" onchange="ch_filter();">
                                            <option value="" disabled selected></option>
                                            <option value="none">Normal</option>
                                            <option value="grayscale(100%)">Grayscale</option>
                                            <option value="sepia(100%)">Sepia</option>
                                            <option value="invert(100%)">Invert</option>
                                            <option value="hue-rotate(90deg)">Hue</option>
                                            <option value="blur(10px)">Blur</option>
                                            <option value="contrast(200%)">Contrast</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="column control">
                                    <div class="select is-primary">
                                        <select id="photo_stickers" class="control" onchange="filter_stiker();">
                                            <option value="" disabled selected></option>
                                            <option>nrml</option>
                                        </select>
                                    </div>
                                </div> -->
                            </div>
                            <form action="<?php echo URLROOT;?>cameras/index" method="POST" enctype="multipart/form-data">
                                <h1 class="title" id="title_filter">Choose a filter</h1>
                                <div class="border_video" id="display_vedio">
                                    <video id="video" width="100%"><hi class="title">Choose a filter</h1></video>
                                    <canvas id="canvas"><canvas>
                                </div>

                                <div id="photo"></div>
                                <input type="hidden" name="img64" id="img64">
                                <input type="hidden" name="filter" id="filter">
                            <!-- </form> -->
                            <!-- <form action="<?php echo URLROOT;?>cameras/index" method="POST" enctype="multipart/form-data"> -->
                                <div id="file-js" class="file">
                                    <label class="file-label">
                                        <input class="file-input" id="inpFile" type="file" name="file" onchange="ch();">
                                        <span class="file-cta">
                                            <span class="file-icon">
                                                <i class="fas fa-upload"></i>
                                            </span>
                                            <span class="file-label">
                                                Choose a image…
                                            </span>
                                        </span>
                                        <span class="file-name"></span>
                                    </label>
                                    <p class="help is-danger"><?= $data['img_err']?></p>
                                <!-- <button class="button is-link is-outlined">submit</button> -->
                                </div>
                                <input class="button is-link" id="take" onclick="takephoto();" type="submit" value="Takephoto">
                                <!-- <input type="hidden" name="filter1" id="filter1"> -->
                            </form>
                        </div>
                        <div class="column is-10">
                            <div class="card events-card">
                                <header class="card-header">
                                    <p class="card-header-title">
                                        All image.
                                    </p>
                                </header>
                                <div class="card-table">
                                    <div class="content" id="c_scroll">
                                        <table class="table is-fullwidth is-striped">
                                            <tbody>
                                            <? foreach($data['img'] as $img) :?>
                                                <tr>
                                                    <form action="<?php echo URLROOT;?>cameras/delete_preview/<?php echo $img->id; ?>" method="post">
                                                        <img src="data:<?php echo $img->type?>;base64,<?php echo $img->img?>" style="filter : <?php echo $img->filter?> ;" alt="image"/>
                                                        <button class="button is-danger is-outlined">
                                                            <span class="icon is-small">
                                                                <i class="fas fa-times"></i>
                                                            </span>
                                                        </button>
                                                    </form>
                                                    <br>
                                                </tr>
                                            <? endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                //camera preview

                function ch_test(){
                    var c = document.getElementById("canvas");
                    var ctx = c.getContext("2d");
                    var img = document.getElementById("0");
                    ctx.drawImage(img, 10, 10, c.offsetWidth, c.offsetHeight);
                    console.log(ctx);
                }
            </script>
        <?php endif ;?>
<?php require APPROOT . "/views/inc/footer.php"; ?>