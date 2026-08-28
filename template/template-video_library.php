<?php
/*
Template Name: Video Library Page
*/

get_header();
?>

<?php inner_hero(); ?>

<?php  if ( have_rows('video') ) :  ?>
    <section class="py-5 my-3">
        <div class="container">   
            <div class="video-grid" id="videoGrid">
                
               <?php 
                    while ( have_rows('video') ) : the_row(); 
                        $heading = get_sub_field('heading');
                        $tagline = get_sub_field('tagline');
                        $video_title = get_sub_field('video_title');
                        $video_poster = get_sub_field('video_poster');
                        $video_url = get_sub_field('video_url');

                        if($video_url) :

                            if($heading) {
                                $$heading = $heading;
                            } else {
                                $heading = 'EcoBloom';
                            }

                            if($video_title) {
                                $video_title = $video_title;
                            } else {
                                $video_title = 'EcoBloom'; 
                            }

                            if ($video_poster) {
                                $image_url = $video_poster['url'];
                                $image_alt = $video_poster['alt'] ? $video_poster['alt'] : '';
                            } else {
                                $image_url = get_template_directory_uri() . '/assets/images/placeholder.webp';
                                $image_alt = 'EcoBloom';
                            }
                            ?>
                            <div class="video-card" data-bs-toggle="modal" data-bs-target="#videoModal" 
                                data-video-title="<?php echo esc_attr($video_title); ?>"
                                data-video-url="<?php echo esc_url($video_url); ?>"
                                data-video-poster="<?php echo esc_url($image_url); ?>">
                                <div class="video-thumb-wrap">
                                    <img src="<?php echo esc_url($image_url); ?>" class="video-thumb" alt="<?php echo esc_attr($image_alt); ?>">
                                    <div class="video-play-btn"><i class="bi bi-play-fill text-white"></i></div>
                                </div>
                                <?php
                                    if($heading || $tagline) :
                                        echo '<div class="video-card-info">';
                                            if($heading) :
                                                echo '<h4>' . $heading . '</h4>';
                                            endif;
                                            if($tagline) :
                                                echo '<span>' . $tagline . '</span>';
                                            endif;
                                        echo '</div>';
                                    endif;
                                ?>
                            </div>
                            <?php
                        endif;
                    endwhile;    
                ?>
            </div>
        </div>
    </section>

    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark text-white border-0 rounded-4 overflow-hidden">
                <div class="modal-header border-0 pb-2">
                    <h5 class="modal-title fs-6 text-light" id="videoModalLabel">
                        <i class="bi bi-play-circle-fill text-magenta me-2"></i> EcoBloom Video Player
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 bg-black">
                    <div class="ratio ratio-16x9">
                        <video id="modalVideoPlayer" controls preload="metadata" poster="<?php echo get_template_directory_uri() . '/assets/images/placeholder.webp'; ?>"
                            style="width: 100%; height: 100%; background: #000;">
                            <source src="#" type="video/mp4">
                            Your browser does not support HTML5 video playback.
                        </video>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-between py-2 px-4">
                    <span class="small" id="videoTitleCaption">Video Title</span>
                    <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3"
                        data-bs-dismiss="modal">Close Player</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const videoModal = document.getElementById('videoModal');
        const modalVideoPlayer = document.getElementById('modalVideoPlayer');
        const videoTitleCaption = document.getElementById('videoTitleCaption');
        const videoSource = modalVideoPlayer.querySelector('source');

        // When modal opens, set title and play
        videoModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const title = button.getAttribute('data-video-title');
            const videoUrl = button.getAttribute('data-video-url');
            const videoPoster = button.getAttribute('data-video-poster');

            if (title && videoTitleCaption) {
                videoTitleCaption.innerText = title;
            }

            if (modalVideoPlayer && videoSource && videoUrl) {
                videoSource.src = videoUrl;
                modalVideoPlayer.load();
                modalVideoPlayer.play().catch(function () {});
            }

            if (modalVideoPlayer && videoPoster) {
                modalVideoPlayer.poster = videoPoster;
            }
        });

        // STRICT REQUIREMENT: When modal closes, STOP video playing
        videoModal.addEventListener('hidden.bs.modal', function () {
            if (modalVideoPlayer) {
                modalVideoPlayer.pause();
                modalVideoPlayer.currentTime = 0;
            }
        });
    });
</script>

<?php get_footer(); ?>