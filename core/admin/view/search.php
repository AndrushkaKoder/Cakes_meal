<div class="vg-wrap vg-element vg-ninteen-of-twenty">
        <?php if($data):?>
            <?php foreach($data as $item):?>
                <div class="vg-element vg-fourth">
                    <a href="<?=$item['alias']?>" class="vg-wrap vg-element vg-full vg-firm-background-color4 vg-box-shadow">
                        <div class="vg-element vg-half vg-center">
                            <?php if($item['img']):?>
                                <?php $image = explode("|", $item['img'])[0]?>
                                <img src="<?=PATH.UPLOAD_DIR.$image?>" alt="service">
                            <?php endif;?>
                        </div>
                        <div class="vg-element vg-half vg-center">
                            <span class="vg-text vg-firm-color1"><?=$item['name']?></span>
                        </div>
                    </a>
                </div>
            <?php endforeach;?>
        <?php endif;?>
    </div>