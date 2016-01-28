<!-- Carousel started  -->
<div id="forside" class="carousel slide row">

    <ol class="carousel-indicators" style="bottom:10px;">
        <?php foreach ($frontpage_items as $key => $frontpage_item) : ?>
            <li data-target="#forside" data-slide-to="<?php echo $key; ?>" 
            <?php if($rnd_active == $key) : ?>
            class="active"
            <?php endif; ?>
            >
            </li>
        <?php endforeach; ?>
        </ol>
    
    <!-- Wrapper for slides -->
    <div class="carousel-inner">

        <?php foreach ($frontpage_items as $key => $frontpage_item) : ?>
            <div class="item <?php if($rnd_active == $key) {echo 'active';} ?>">
                <img>
                    <div style="background:url('/v2/img/frontpage_items/<?php echo $frontpage_item['FrontpageItem']['id']; ?>.png') center no-repeat;background-size: cover; width:100%; height:500px;">
                    </div>
                </img>
                <div class="carousel-caption">
                    <h3 style="text-shadow: 0px 0px 3px #000;"><?php echo $frontpage_item['FrontpageItem']['description']; ?></h3>
                    <a href="<?php echo $frontpage_item['FrontpageItem']['url']; ?>" class="btn btn-danger"><?php echo $frontpage_item['FrontpageItem']['button_text']; ?></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#forside" data-slide="prev">
        <span class="icon-prev"></span>
    </a>
    <a class="right carousel-control" href="#forside" data-slide="next">
        <span class="icon-next"></span>
    </a>
</div>

<!-- Slide every 5 seconds -->
<script>
    $('.carousel').carousel({
        interval: 5000
    })
</script>


