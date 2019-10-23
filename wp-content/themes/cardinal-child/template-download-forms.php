
<?php
/**
 * Template Name: Download Forms
 */
?>

<?php get_header(); ?>

<section class="container"><div class="row"><div class="blank_spacer col-sm-12  hidden-xs" style="height:100px;"></div></div></section>
<div class="container">
    <div class="row">
        <div class="spb_content_element col-sm-12 spb_text_column">
            <div class="spb-asset-content" style="text-align: center;padding-top:0%;padding-bottom:0%;padding-left:0%;padding-right:0%;">
                <h2><?php echo the_field('title'); ?></h2>
            </div>
        </div>
    </div>
</div>

<?php
    $fileGroups = get_field('file_group_repeater');
    if(!empty($fileGroups)):
        foreach ($fileGroups as $fileGroup): ?>

            <section class="container"><div class="row"><div class="blank_spacer col-sm-12  hidden-xs" style="height:80px;"></div></div></section>

            <div class="container">
                <div class="row">
                    <div class="spb_content_element col-sm-12 spb_text_column">
                        <div class="file-list-title spb-asset-content" style="text-align: center;padding-top:0%;padding-bottom:0%;padding-left:0%;padding-right:0%;">
                            <div>
                                <?php echo $fileGroup['file_group_title'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <?php
                $files = $fileGroup['file_repeater'];
                ?>
                <div class="download-forms-wrapper">
                    <?php if(!empty($files)): ?>
                        <?php foreach ($files as $file): ?>
                            <?php
                            $file_title = $file['file_title'];
                            $file_description = $file['file_description'];
                            $file_url = $file['file'];
                            ?>
                            <div>
                                <a class="download-form-link" href="<?php echo $file_url ?>" target="_blank">
                                    <div class="form-holder">
                                        <div class="form-item">
                                            <?php if(!empty($file_title)): ?>
                                                <span class="form-title"><?php echo $file_title ?></span>
                                                <span class="form-description"><?php echo $file_description ?></span>
                                                <?php if(!empty($file_url)): ?>
                                                    <i class="ss-download sf-icon sf-icon-medium"></i>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        <?php
        endforeach;
    endif;
?>

<section class="container"><div class="row"><div class="blank_spacer col-sm-12  hidden-xs" style="height:100px;"></div></div></section>

<?php get_footer(); ?>