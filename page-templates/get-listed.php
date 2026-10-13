<?php
/**
 * Template Name: Get Listed
 */
get_header();

$vs2_bc = [
    [ 'label' => 'Home', 'url' => home_url( '/' ) ],
    [ 'label' => 'Get Listed', 'url' => null ],
];
vs2_breadcrumb( $vs2_bc );
?>

<div style="background:var(--black);padding:60px 28px 48px;text-align:center;border-bottom:1px solid rgba(255,255,255,.06);">
  <div style="font-size:11px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:var(--red);margin-bottom:14px;">Join the Directory</div>
  <div style="font-size:clamp(28px,5vw,52px);font-weight:900;color:#fff;letter-spacing:-.04em;margin-bottom:14px;line-height:1.1;">Get your venture on<br>Venture<em style="color:var(--red);font-style:normal;">Stori</em>.</div>
  <div style="font-size:15px;color:#777;max-width:480px;margin:0 auto;font-weight:500;">Choose the listing type that fits your venture. Every profile starts as a listing and grows into a verified, searchable digital identity.</div>
</div>

<div class="dir-body">
  <div style="margin-bottom:28px;"><div class="sec-label">What would you like to list?</div></div>
  <div class="listing-type-grid">
    <button type="button" class="listing-type-btn lt-active" data-target="lt-biz"><div class="lt-icon">🏢</div><div class="lt-name">Business</div><div class="lt-desc">SMEs, startups, corporations</div></button>
    <button type="button" class="listing-type-btn" data-target="lt-pro"><div class="lt-icon">🧑</div><div class="lt-name">Professional</div><div class="lt-desc">Individuals with career identities</div></button>
    <button type="button" class="listing-type-btn" data-target="lt-org"><div class="lt-icon">🤝</div><div class="lt-name">Organization</div><div class="lt-desc">Associations, chambers, bodies</div></button>
    <button type="button" class="listing-type-btn" data-target="lt-ins"><div class="lt-icon">🏛️</div><div class="lt-name">Institution</div><div class="lt-desc">Universities, hospitals, schools</div></button>
  </div>

  <div id="lt-biz" class="lt-panel">
    <div style="margin-bottom:24px;"><div class="sec-label">Business Listing Tiers</div><div style="font-size:14px;color:var(--grey);font-weight:500;">For every type of operating business across Africa.</div></div>
    <div class="tiers-grid">
      <div class="tier-card"><div class="tier-name">Listed</div><div class="tier-price">Free <span>/ always</span></div><div class="tier-desc">Basic profile in the directory.</div><ul class="tier-features"><li>Profile in business directory</li><li>One primary category</li><li>Contact details displayed</li><li>Standard placement</li></ul><a href="<?php echo esc_url( home_url( '/wp-admin/post-new.php?post_type=profile' ) ); ?>" class="tier-cta-ghost">Submit listing</a></div>
      <div class="tier-card featured"><div class="tier-badge">Most Popular</div><div class="tier-name">Verified</div><div class="tier-price">₦15,000 <span>/ year</span></div><div class="tier-desc">Enhanced visibility, analytics and priority placement.</div><ul class="tier-features"><li>Everything in Listed</li><li>Verification badge</li><li>Three categories</li><li>Logo, gallery and products module</li><li>Priority in search results</li></ul><a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>?subject=Get%20Verified%20-%20Business" class="tier-cta-primary">Get Verified</a></div>
      <div class="tier-card"><div class="tier-name">Growth</div><div class="tier-price">₦35,000 <span>/ year</span></div><div class="tier-desc">Featured placement, lead capture and full analytics.</div><ul class="tier-features"><li>Everything in Verified</li><li>Featured collection placement</li><li>Enquiry forms with lead capture</li><li>Team member profiles</li><li>One editorial mention / year</li></ul><a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>?subject=Get%20Growth%20-%20Business" class="tier-cta-ghost">Get Growth</a></div>
      <div class="tier-card"><div class="tier-name">Certified</div><div class="tier-price">₦80,000 <span>/ year</span></div><div class="tier-desc">Documentary certification, gold badge, API access.</div><ul class="tier-features"><li>Everything in Growth</li><li>Certified badge</li><li>Documentary verification certificate</li><li>Multi-location support</li><li>Dedicated account contact</li></ul><a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>?subject=Get%20Certified%20-%20Business" class="tier-cta-ghost">Get Certified</a></div>
    </div>
  </div>

  <div id="lt-pro" class="lt-panel" style="display:none;">
    <div style="margin-bottom:24px;"><div class="sec-label">Professional Profile Tiers</div><div style="font-size:14px;color:var(--grey);font-weight:500;">For individuals building a career identity across Africa.</div></div>
    <div class="tiers-grid">
      <div class="tier-card"><div class="tier-name">Listed</div><div class="tier-price">Free</div><div class="tier-desc">Basic professional profile.</div><ul class="tier-features"><li>Profile in professional directory</li><li>Name, role and location</li><li>Skills listed</li></ul><a href="<?php echo esc_url( home_url( '/wp-admin/post-new.php?post_type=professional' ) ); ?>" class="tier-cta-ghost">Create profile</a></div>
      <div class="tier-card featured"><div class="tier-badge">Recommended</div><div class="tier-name">Verified</div><div class="tier-price">₦8,000 <span>/ year</span></div><div class="tier-desc">Verified identity, career timeline, publications and organization links.</div><ul class="tier-features"><li>Everything in Listed</li><li>Verification badge</li><li>Full career timeline</li><li>Publications and portfolio</li><li>Organization membership links</li></ul><a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>?subject=Get%20Verified%20-%20Professional" class="tier-cta-primary">Create Profile</a></div>
      <div class="tier-card"><div class="tier-name">Premium</div><div class="tier-price">₦20,000 <span>/ year</span></div><div class="tier-desc">Lead capture, editorial exposure and priority placement.</div><ul class="tier-features"><li>Everything in Verified</li><li>Enquiry forms</li><li>Featured in category pages</li><li>One editorial mention / year</li></ul><a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>?subject=Go%20Premium%20-%20Professional" class="tier-cta-ghost">Go Premium</a></div>
    </div>
  </div>

  <div id="lt-org" class="lt-panel" style="display:none;">
    <div style="margin-bottom:24px;"><div class="sec-label">Organization Listing Tiers</div><div style="font-size:14px;color:var(--grey);font-weight:500;">For professional bodies, trade associations, student groups and community networks.</div></div>
    <div class="tiers-grid">
      <div class="tier-card"><div class="tier-name">Basic</div><div class="tier-price">Free</div><div class="tier-desc">Organization profile with about, leadership and contact.</div><ul class="tier-features"><li>Organization profile page</li><li>About, history and leadership</li><li>Contact details</li><li>Up to 3 event listings</li></ul><a href="<?php echo esc_url( home_url( '/wp-admin/post-new.php?post_type=organization' ) ); ?>" class="tier-cta-ghost">List organization</a></div>
      <div class="tier-card featured"><div class="tier-badge">Most Popular</div><div class="tier-name">Partner</div><div class="tier-price">₦50,000 <span>/ year</span></div><div class="tier-desc">Full digital headquarters with member directory and verified badge.</div><ul class="tier-features"><li>Everything in Basic</li><li>Verified badge</li><li>Member directory (up to 500)</li><li>Unlimited event listings</li><li>Chapter management</li></ul><a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>?subject=Become%20a%20Partner%20-%20Organization" class="tier-cta-primary">Become a Partner</a></div>
      <div class="tier-card"><div class="tier-name">National</div><div class="tier-price">₦120,000 <span>/ year</span></div><div class="tier-desc">For national bodies — unlimited members, bulk onboarding.</div><ul class="tier-features"><li>Everything in Partner</li><li>Gold certified badge</li><li>Unlimited member directory</li><li>Bulk import and onboarding</li><li>Dedicated account contact</li></ul><a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>?subject=National%20Tier%20-%20Organization" class="tier-cta-ghost">Contact us</a></div>
    </div>
  </div>

  <div id="lt-ins" class="lt-panel" style="display:none;">
    <div style="margin-bottom:24px;"><div class="sec-label">Institution Listing Tiers</div><div style="font-size:14px;color:var(--grey);font-weight:500;">For universities, hospitals, schools, government ministries and research institutes.</div></div>
    <div class="tiers-grid">
      <div class="tier-card"><div class="tier-name">Listed</div><div class="tier-price">Free</div><div class="tier-desc">Add your institution's official information and listings.</div><ul class="tier-features"><li>Institution profile page</li><li>Overview and departments</li><li>Location and contact</li><li>Alumni auto-population</li></ul><a href="<?php echo esc_url( home_url( '/wp-admin/post-new.php?post_type=institution' ) ); ?>" class="tier-cta-ghost">List institution</a></div>
      <div class="tier-card featured"><div class="tier-badge">Recommended</div><div class="tier-name">Verified</div><div class="tier-price">₦80,000 <span>/ year</span></div><div class="tier-desc">Full departmental profile, alumni directory and event listings.</div><ul class="tier-features"><li>Everything in Listed</li><li>Verification badge</li><li>Full departmental structure</li><li>Event listings and news</li><li>Searchable alumni directory</li></ul><a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>?subject=Get%20Verified%20-%20Institution" class="tier-cta-primary">Get Verified</a></div>
      <div class="tier-card"><div class="tier-name">Certified</div><div class="tier-price">₦200,000 <span>/ year</span></div><div class="tier-desc">Gold badge, research showcase and API access.</div><ul class="tier-features"><li>Everything in Verified</li><li>Certified badge</li><li>Documentary verification</li><li>Research and publications module</li><li>Annual editorial feature</li></ul><a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>?subject=Get%20Certified%20-%20Institution" class="tier-cta-ghost">Contact us</a></div>
    </div>
  </div>

  <div style="margin-top:48px;padding-top:40px;border-top:1px solid var(--border);">
    <div class="sec-label" style="margin-bottom:20px;">Common Questions</div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;">
      <div><div style="font-size:14px;font-weight:800;color:#fff;margin-bottom:7px;">How does verification work?</div><div style="font-size:13px;color:var(--grey);line-height:1.65;font-weight:500;">We confirm your business is reachable by phone and email, and confirm your location. Certified listings also require documentary evidence of registration.</div></div>
      <div><div style="font-size:14px;font-weight:800;color:#fff;margin-bottom:7px;">Can I upgrade later?</div><div style="font-size:13px;color:var(--grey);line-height:1.65;font-weight:500;">Yes. Start free and upgrade when ready. Profile data carries over.</div></div>
      <div><div style="font-size:14px;font-weight:800;color:#fff;margin-bottom:7px;">Payment methods?</div><div style="font-size:13px;color:var(--grey);line-height:1.65;font-weight:500;">Card, bank transfer and mobile money. Annual billing preferred.</div></div>
    </div>
  </div>
</div>

<script>
document.querySelectorAll('.listing-type-btn').forEach(function(btn){
  btn.addEventListener('click', function(){
    document.querySelectorAll('.listing-type-btn').forEach(function(b){ b.classList.remove('lt-active'); });
    btn.classList.add('lt-active');
    document.querySelectorAll('.lt-panel').forEach(function(p){ p.style.display = 'none'; });
    var target = document.getElementById(btn.dataset.target);
    if (target) target.style.display = 'block';
  });
});
</script>

<?php get_footer(); ?>
