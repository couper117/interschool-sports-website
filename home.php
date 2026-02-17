<?php
// rssf-updates.php snippet
include 'config.php';

// Example: Embed Facebook page (official: https://www.facebook.com/100071943012452 or search "Rwanda School Sport Federation")
?>
<?php include 'includes/header.php'; ?>
<li>
    <form action="search.php" method="get" style="display:inline;">
        <input type="text" name="q" placeholder="Search..." style="padding:6px; width:180px;">
        <button type="submit" style="padding:6px 12px;">Go</button>
    </form>
</li>
<h2>Latest from RSSF (Rwanda School Sports Federation)</h2>

<!-- Facebook Page Plugin (official embed code) -->
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v20.0"></script>
<div class="fb-page" 
     data-href="https://www.facebook.com/100071943012452" 
     data-tabs="timeline" 
     data-width="500" 
     data-height="600" 
     data-small-header="false" 
     data-adapt-container-width="true" 
     data-hide-cover="false" 
     data-show-facepile="true">
    <blockquote cite="https://www.facebook.com/100071943012452" class="fb-xfbml-parse-ignore">
        <a href="https://www.facebook.com/100071943012452">Rwanda School Sport Federation</a>
    </blockquote>
</div>

<!-- X (Twitter) Timeline Embed for @FRSS2025 -->
<a class="twitter-timeline" href="https://twitter.com/FRSS2025" data-width="500" data-height="600">
    Tweets by FRSS2025
</a> 
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

<p class="note">These are official RSSF channels. For full results & fixtures, visit RSSF social pages or contact infofrss8@gmail.com / 0788792574 (from official profile).</p>
<?php include 'includes/footer.php'; ?> 