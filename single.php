<?php get_header(); ?>

		 <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
              <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="entry clear noheader">
                    <?php the_content(); ?>
                </div><!--end entry-->
              </div>
            <?php endwhile; /* rewind or continue if all posts have been fetched */ ?>
              <div class="navigation index">
                <div class="alignleft"><?php next_posts_link( 'Older Entries' ); ?></div>
                <div class="alignright"><?php previous_posts_link( 'Newer Entries' ); ?></div>
              </div><!--end navigation-->
            <?php else : ?>
       <?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>