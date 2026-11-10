<?php
/******************************************
Page Fabrication : façade vidéo (éco-conception)
AVANT : iframe YouTube chargée dès l'ouverture de la page
        (>= 21 requêtes, 1,2 Mo transférés, traceurs tiers).
APRÈS : une image locale + un vrai bouton. Le lecteur YouTube
        n'est créé qu'au clic (youtube-nocookie).
Pour changer de vidéo : modifier les 3 variables ci-dessous.
******************************************/
$videoId    = "lOwPOGmNg_w";
$videoTitre = "Apiculture : la récolte du miel - Truffaut";
$vignette   = "./PhotosSlider/img04.webp";

$iframe = '<iframe width="480" height="270"'
        . ' src="https://www.youtube-nocookie.com/embed/' . rawurlencode($videoId) . '?autoplay=1"'
        . ' title="' . htmlspecialchars($videoTitre, ENT_QUOTES) . '"'
        . ' allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>';
?>
<style>
    .video-facade { position: relative; display: block; padding: 0; border: 0; background: none; cursor: pointer; }
    .video-facade img { display: block; }
    .video-play { position: absolute; inset: 0; margin: auto; width: 4rem; height: 4rem;
                  display: grid; place-items: center; border-radius: 50%;
                  background: rgba(0, 0, 0, 0.6); color: #fff; font-size: 2rem; }
    .video-facade:focus-visible { outline: 3px solid #54b454; outline-offset: 3px; }
</style>

<figure class="video">
    <button type="button" class="video-facade"
            aria-label="Lire la vidéo « <?= htmlspecialchars($videoTitre, ENT_QUOTES) ?> » (YouTube)"
            data-iframe="<?= htmlspecialchars($iframe, ENT_QUOTES) ?>"
            onclick="this.outerHTML = this.dataset.iframe;">
        <img src="<?= htmlspecialchars($vignette, ENT_QUOTES) ?>" width="480" height="240" alt="" loading="lazy">
        <span class="video-play" aria-hidden="true">▶</span>
    </button>
    <figcaption>
        <?= htmlspecialchars($videoTitre, ENT_QUOTES) ?>.
        <a href="https://www.youtube.com/watch?v=<?= rawurlencode($videoId) ?>">Voir sur YouTube</a>
    </figcaption>
</figure>