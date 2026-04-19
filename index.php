<?php
/**
 * VedaWays WordPress Theme — index.php (Homepage Template)
 * Handles the main front page.
 */
get_header(); ?>

<!-- ===== HERO ===== -->
<section class="hero" id="home">
  <div class="hero-video-bg">
    <div class="hero-overlay"></div>
    <div class="hero-particles" id="particles"></div>
  </div>
  <div class="hero-content">
    <p class="hero-eyebrow">Since 2024 · Private India Specialists</p>
    <h1 class="hero-title">
      Curated Journeys<br/>
      <em>Across India</em>
    </h1>
    <p class="hero-subtitle">Crafted for the Discerning Traveler</p>
    <div class="hero-divider"></div>
    <p class="hero-desc">From palace courts of Rajasthan to the backwaters of Kerala — we craft private journeys that reveal India's soul, not just its sights.</p>
    <div class="hero-ctas">
      <a href="#journeys" class="btn-primary">Explore Journeys</a>
      <a href="#plan" class="btn-secondary">Plan My Trip</a>
    </div>
    <div class="hero-stats">
      <div class="stat"><span class="stat-num">1,200+</span><span class="stat-label">Happy Travelers</span></div>
      <div class="stat-divider"></div>
      <div class="stat"><span class="stat-num">15+</span><span class="stat-label">Destinations</span></div>
      <div class="stat-divider"></div>
      <div class="stat"><span class="stat-num">4.9★</span><span class="stat-label">Avg Rating</span></div>
    </div>
  </div>
  <div class="hero-scroll-hint">
    <span>Scroll to Discover</span>
    <div class="scroll-line"></div>
  </div>
</section>

<!-- ===== MARQUEE ===== -->
<div class="marquee-strip">
  <div class="marquee-track">
    <?php $destinations = ['Rajasthan','Kerala Backwaters','Golden Triangle','Varanasi','Ranthambore Safari','Khajuraho','Odisha Tribal Tours','Udaipur Palaces'];
    for ($j = 0; $j < 2; $j++):
      foreach ($destinations as $d): ?>
        <span><?php echo esc_html($d); ?></span><span class="dot">◆</span>
    <?php endforeach; endfor; ?>
  </div>
</div>

<!-- ===== PLAN MY TRIP ===== -->
<section class="plan-section" id="plan">
  <div class="container">
    <div class="plan-inner">
      <div class="plan-text">
        <p class="section-eyebrow">Build Your Journey</p>
        <h2 class="section-title">Where Shall We<br/><em>Take You?</em></h2>
        <p class="section-desc">Answer four simple questions and we'll craft a personalized journey just for you.</p>
        <div class="plan-features">
          <div class="plan-feat"><span class="feat-icon">✦</span>Luxury 4★ & 5★ accommodations</div>
          <div class="plan-feat"><span class="feat-icon">✦</span>Spanish-speaking expert guides</div>
          <div class="plan-feat"><span class="feat-icon">✦</span>Private air-conditioned transport</div>
          <div class="plan-feat"><span class="feat-icon">✦</span>24/7 concierge on WhatsApp</div>
        </div>
      </div>
      <div class="plan-form-wrap">
        <form class="plan-form" id="planForm">
          <?php wp_nonce_field('vedaways_nonce', 'nonce'); ?>
          <div class="step active" data-step="1">
            <h3>What brings you to India?</h3>
            <div class="option-grid">
              <label class="option-card"><input type="radio" name="interest" value="luxury"/><span class="opt-icon">👑</span><span>Luxury Vacation</span></label>
              <label class="option-card"><input type="radio" name="interest" value="culture"/><span class="opt-icon">🏛️</span><span>Cultural Exploration</span></label>
              <label class="option-card"><input type="radio" name="interest" value="wildlife"/><span class="opt-icon">🐅</span><span>Wildlife Safari</span></label>
              <label class="option-card"><input type="radio" name="interest" value="spiritual"/><span class="opt-icon">🕉️</span><span>Spiritual Journey</span></label>
              <label class="option-card"><input type="radio" name="interest" value="honeymoon"/><span class="opt-icon">🌹</span><span>Honeymoon</span></label>
              <label class="option-card"><input type="radio" name="interest" value="offbeat"/><span class="opt-icon">🌿</span><span>Offbeat India</span></label>
            </div>
            <button type="button" class="btn-next" onclick="nextStep(1)">Continue →</button>
          </div>
          <div class="step" data-step="2">
            <h3>How long is your journey?</h3>
            <div class="option-grid">
              <label class="option-card"><input type="radio" name="duration" value="5-7"/><span class="opt-icon">🗓️</span><span>5–7 Days</span></label>
              <label class="option-card"><input type="radio" name="duration" value="8-12"/><span class="opt-icon">🗓️</span><span>8–12 Days</span></label>
              <label class="option-card"><input type="radio" name="duration" value="12+"/><span class="opt-icon">🗓️</span><span>12+ Days</span></label>
            </div>
            <button type="button" class="btn-next" onclick="nextStep(2)">Continue →</button>
            <button type="button" class="btn-back" onclick="prevStep(2)">← Back</button>
          </div>
          <div class="step" data-step="3">
            <h3>What's your travel budget?</h3>
            <div class="option-grid">
              <label class="option-card budget-opt"><input type="radio" name="budget_range" value="budget"/><span>₹30K–₹60K</span><small>Value</small></label>
              <label class="option-card budget-opt"><input type="radio" name="budget_range" value="premium"/><span>₹60K–₹1L</span><small>Premium</small></label>
              <label class="option-card budget-opt"><input type="radio" name="budget_range" value="luxury"/><span>₹1L+</span><small>Luxury</small></label>
            </div>
            <button type="button" class="btn-next" onclick="nextStep(3)">Continue →</button>
            <button type="button" class="btn-back" onclick="prevStep(3)">← Back</button>
          </div>
          <div class="step" data-step="4">
            <h3>Your details</h3>
            <div class="form-fields">
              <input type="text" name="name" placeholder="Your Name" class="field" required/>
              <input type="email" name="email" placeholder="Email Address" class="field" required/>
              <input type="tel" name="phone" placeholder="WhatsApp Number" class="field"/>
              <input type="hidden" name="action" value="vedaways_inquiry"/>
            </div>
            <button type="submit" class="btn-submit">Craft My Journey ✦</button>
            <button type="button" class="btn-back" onclick="prevStep(4)">← Back</button>
          </div>
          <div class="step success-step" data-step="5">
            <div class="success-anim">✦</div>
            <h3>Crafting Your Journey…</h3>
            <p>Our team will reach out within 24 hours on WhatsApp with your personalized itinerary.</p>
          </div>
          <div class="progress-bar"><div class="progress-fill" id="progressFill"></div></div>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- ===== FEATURED JOURNEYS ===== -->
<section class="journeys-section" id="journeys">
  <div class="container">
    <div class="section-header">
      <p class="section-eyebrow">Our Curated Collection</p>
      <h2 class="section-title">Signature <em>Journeys</em></h2>
      <p class="section-desc">Every itinerary is built around authentic experiences, not tourist checkboxes.</p>
    </div>
    <div class="filter-bar">
      <button class="filter-btn active" data-filter="all">All Journeys</button>
      <?php
      $terms = get_terms(['taxonomy' => 'journey_category', 'hide_empty' => false]);
      foreach ($terms as $term):
      ?>
      <button class="filter-btn" data-filter="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></button>
      <?php endforeach; ?>
    </div>
    <div class="journeys-grid" id="journeysGrid">
      <?php
      $itineraries = new WP_Query([
        'post_type'      => 'itinerary',
        'posts_per_page' => 6,
        'post_status'    => 'publish',
        'meta_query'     => [],
      ]);
      if ($itineraries->have_posts()):
        while ($itineraries->have_posts()): $itineraries->the_post();
          $post_id   = get_the_ID();
          $route     = get_post_meta($post_id, '_route', true);
          $duration  = get_post_meta($post_id, '_duration', true);
          $hotel     = get_post_meta($post_id, '_hotel_type', true);
          $includes  = get_post_meta($post_id, '_includes', true);
          $badge     = get_post_meta($post_id, '_badge_text', true);
          $price     = vedaways_get_price_range($post_id);
          $img       = get_the_post_thumbnail_url($post_id, 'large');
          $cats      = wp_get_post_terms($post_id, 'journey_category', ['fields' => 'slugs']);
          $cat_str   = implode(' ', $cats);
      ?>
      <article class="journey-card" data-category="<?php echo esc_attr($cat_str); ?>">
        <div class="card-image" style="background-image: url('<?php echo esc_url($img ?: 'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=600'); ?>')">
          <?php if ($badge): ?><span class="card-badge"><?php echo esc_html($badge); ?></span><?php endif; ?>
          <div class="card-overlay"></div>
        </div>
        <div class="card-body">
          <div class="card-tags">
            <?php foreach ($cats as $cat): ?>
            <span class="tag"><?php echo esc_html(ucfirst($cat)); ?></span>
            <?php endforeach; ?>
          </div>
          <h3 class="card-title"><?php the_title(); ?></h3>
          <p class="card-route"><?php echo esc_html($route); ?></p>
          <div class="card-meta">
            <span>⏱ <?php echo esc_html($duration); ?></span>
            <span>🏨 <?php echo esc_html($hotel); ?></span>
          </div>
          <div class="card-includes"><?php echo esc_html($includes); ?></div>
          <div class="card-footer">
            <div class="card-price"><?php echo $price; ?><small>/person</small></div>
            <div class="card-actions">
              <a href="<?php the_permalink(); ?>" class="btn-view">View</a>
              <a href="#plan" class="btn-customize">Customize</a>
            </div>
          </div>
        </div>
      </article>
      <?php endwhile; wp_reset_postdata();
      else: ?>
      <p style="text-align:center;color:#7A6859;grid-column:1/-1">No journeys found. Add itineraries from the WordPress admin.</p>
      <?php endif; ?>
    </div>
    <div class="journeys-footer">
      <a href="<?php echo get_post_type_archive_link('itinerary'); ?>" class="btn-primary">View All Journeys</a>
    </div>
  </div>
</section>

<!-- ===== DESTINATIONS ===== -->
<section class="destinations-section" id="destinations">
  <div class="container">
    <div class="section-header">
      <p class="section-eyebrow">Explore India</p>
      <h2 class="section-title">Choose Your <em>Destination</em></h2>
    </div>
    <div class="dest-grid">
      <?php
      $destinations = new WP_Query(['post_type' => 'destination', 'posts_per_page' => 5]);
      $count = 0;
      if ($destinations->have_posts()):
        while ($destinations->have_posts()): $destinations->the_post();
          $img = get_the_post_thumbnail_url(get_the_ID(), 'large');
          $extra_class = ($count === 0) ? 'dest-large' : '';
          $count++;
      ?>
      <a href="<?php the_permalink(); ?>" class="dest-card <?php echo $extra_class; ?>" style="background-image:url('<?php echo esc_url($img); ?>')">
        <div class="dest-overlay"></div>
        <div class="dest-info">
          <h3><?php the_title(); ?></h3>
          <p><?php echo get_the_excerpt(); ?></p>
        </div>
      </a>
      <?php endwhile; wp_reset_postdata();
      endif; ?>
    </div>
  </div>
</section>

<!-- ===== TESTIMONIALS ===== -->
<section class="testimonials-section">
  <div class="container">
    <div class="section-header">
      <p class="section-eyebrow">Traveler Stories</p>
      <h2 class="section-title">Voices of Our <em>Guests</em></h2>
    </div>
    <div class="testimonials-grid">
      <?php
      $testimonials = new WP_Query(['post_type' => 'testimonial', 'posts_per_page' => 3]);
      $t_count = 0;
      while ($testimonials->have_posts()): $testimonials->the_post();
        $rating   = get_post_meta(get_the_ID(), '_rating', true);
        $name     = get_post_meta(get_the_ID(), '_traveler_name', true);
        $country  = get_post_meta(get_the_ID(), '_traveler_country', true);
        $initials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', $name), 0, 2)));
        $featured_class = ($t_count === 1) ? 'testi-featured' : '';
        $t_count++;
      ?>
      <div class="testi-card <?php echo $featured_class; ?>">
        <div class="testi-stars"><?php echo vedaways_star_rating((int)$rating); ?></div>
        <p class="testi-text">"<?php the_content(); ?>"</p>
        <div class="testi-author">
          <div class="testi-avatar"><?php echo esc_html($initials); ?></div>
          <div><strong><?php echo esc_html($name); ?></strong><small><?php echo esc_html($country); ?></small></div>
        </div>
      </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>

<!-- ===== CONTACT SECTION ===== -->
<section class="contact-section" id="contact">
  <div class="container">
    <div class="contact-grid">
      <div class="contact-text">
        <p class="section-eyebrow">Get In Touch</p>
        <h2 class="section-title">Let's Plan Your<br/><em>India Journey</em></h2>
        <p>Whether you have a trip in mind or just want to explore possibilities, reach out — we'd love to hear from you.</p>
        <div class="contact-methods">
          <a href="https://wa.me/<?php echo get_option('vedaways_whatsapp', '91XXXXXXXXXX'); ?>" class="contact-method whatsapp">
            <span class="cm-icon">💬</span>
            <div><strong>WhatsApp</strong><small>Chat with us now</small></div>
          </a>
          <a href="mailto:<?php echo antispambot(get_option('admin_email')); ?>" class="contact-method email">
            <span class="cm-icon">✉️</span>
            <div><strong>Email</strong><small><?php echo antispambot(get_option('admin_email')); ?></small></div>
          </a>
        </div>
      </div>
      <form class="contact-form" id="contactForm">
        <?php wp_nonce_field('vedaways_nonce', 'nonce'); ?>
        <input type="hidden" name="action" value="vedaways_contact"/>
        <input type="text" name="name" placeholder="Your Name" class="field" required/>
        <input type="email" name="email" placeholder="Email Address" class="field" required/>
        <input type="tel" name="phone" placeholder="WhatsApp Number" class="field"/>
        <textarea name="message" placeholder="Tell us about your dream trip…" class="field textarea" rows="4"></textarea>
        <button type="submit" class="btn-submit">Send Enquiry ✦</button>
      </form>
    </div>
  </div>
</section>

<?php get_footer(); ?>
