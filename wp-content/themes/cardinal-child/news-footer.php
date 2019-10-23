<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 01/09/2017
 * Time: 09:27
 */
?>
<style>
    .inner-page-wrap{
        margin-bottom: 0;
    }

    .wpcf7 input.wpcf7-submit[type="submit"]:hover,
    {
        background-color: inherit;
        border-color: #000000!important;
        color: #000000!important;
    }


    .wpcf7 input.wpcf7-submit[type="submit"]{
        background-color: #ffffff!important;
        color: #000000;
    }
    .title-wrap .spb-heading{
        width: 100%;
        text-align: center;
    }
</style>

<section data-header-style="" class="row fw-row  dynamic-header-change fw-row-adjusted" >
    <div class="spb-row  " data-row-type="color" data-wrap="full-width-contained" data-image-movement="fixed"
         data-content-stretch="false" data-row-height="content-height" data-col-spacing="0" data-col-v-pos="default"
         style="background-color: rgb(33, 142, 106); margin-top: 0px; margin-bottom: 0px; padding-top: 100px; padding-bottom: 100px; opacity: 1; visibility: visible;"
         data-sb-init="true">


        <div class="spb_content_element clearfix">
            <section class="row ">
                <div class="spb-column-container col-sm-12   col-maxwidth-copy " style="z-index: 0; margin-left: 20px; margin-right: 20px ">
                    <div class="spb-column-inner row clearfix" style="margin-top: 0px;margin-bottom: 0px;">
                        <section class="row ">
                            <div class="spb_content_element col-sm-12 white-font spb_text_column">
                                <div class="spb-asset-content" style="margin-top: 0px;margin-bottom: 0px;">

                                    <div id="u540" class="text">

										<?php dynamic_sidebar( 'news-bottom-widget' ); ?>

                                    </div>
                                </div>
                        </section>


                    </div>
                </div>
            </section>
        </div>

    </div><!-- .sb-row -->

</section>