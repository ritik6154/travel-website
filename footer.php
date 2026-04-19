<?php /* footer.php */ ?>

<!-- ===== FOOTER ===== -->
<footer class="footer">
  <div class="container">
    <div class="footer-top">
      <div class="footer-brand">
        <div class="footer-logo"><span class="logo-veda">Veda</span><span class="logo-ways">Ways</span></div>
        <p>Curated Journeys Across India — Crafted for the Discerning Traveler.</p>
        <div class="social-links">
          <a href="<?php echo get_option('vedaways_instagram', '#'); ?>" class="social-link">IG</a>
          <a href="<?php echo get_option('vedaways_facebook', '#'); ?>" class="social-link">FB</a>
          <a href="<?php echo get_option('vedaways_youtube', '#'); ?>" class="social-link">YT</a>
          <a href="https://wa.me/<?php echo get_option('vedaways_whatsapp', '91XXXXXXXXXX'); ?>" class="social-link">WA</a>
        </div>
      </div>
      <div class="footer-col">
        <h4>Journeys</h4>
        <?php
        $itineraries = new WP_Query(['post_type'=>'itinerary','posts_per_page'=>5]);
        while($itineraries->have_posts()): $itineraries->the_post();
          echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
        endwhile; wp_reset_postdata();
        ?>
      </div>
      <div class="footer-col">
        <h4>Destinations</h4>
        <?php
        $dests = new WP_Query(['post_type'=>'destination','posts_per_page'=>5]);
        while($dests->have_posts()): $dests->the_post();
          echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
        endwhile; wp_reset_postdata();
        ?>
      </div>
      <div class="footer-col">
        <h4>Company</h4>
        <a href="<?php echo get_permalink(get_page_by_path('about')); ?>">About Us</a>
        <a href="<?php echo get_permalink(get_page_by_path('blog')); ?>">Blog</a>
        <a href="<?php echo get_permalink(get_page_by_path('contact')); ?>">Contact</a>
        <a href="<?php echo get_permalink(get_page_by_path('privacy-policy')); ?>">Privacy Policy</a>
      </div>
      <div class="footer-col newsletter-col">
        <h4>Stay Inspired</h4>
        <p>Travel stories &amp; India insights in your inbox.</p>
        <form class="newsletter-form" id="newsletterForm">
          <input type="email" placeholder="Your email" class="nl-field" required/>
          <?php wp_nonce_field('vedaways_nonce', 'nonce'); ?>
          <button type="submit" class="nl-btn">→</button>
        </form>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© <?php echo date('Y'); ?> VedaWays by Vishnu Verma. All rights reserved.</p>
      <p>Designed with ♥ for India lovers worldwide.</p>
    </div>
  </div>
</footer>

<!-- WhatsApp Sticky Button -->
<a href="https://wa.me/<?php echo get_option('vedaways_whatsapp', '91XXXXXXXXXX'); ?>" class="whatsapp-float" target="_blank">
  <svg viewBox="0 0 24 24" fill="currentColor" width="28" height="28"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
</a>

<?php wp_footer(); ?>
</body>
</html>
