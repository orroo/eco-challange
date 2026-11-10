UPDATE `miel`
SET `photo` = REPLACE(`photo`, '.png', '.webp')
WHERE `photo` IN (
  './img/miels/Acacia.png',
  './img/miels/Chataignier.png',
  './img/miels/Colza.png',
  './img/miels/Lavande.png',
  './img/miels/Romarin.png',
  './img/miels/TouteFleurs.png',
  './img/miels/Tilleul.png'
);

UPDATE `produitderive`
SET `photo` = './img/produitsDerives/propolis.webp'
WHERE `photo` = './img/produitsDerives/propolis.jpg';

UPDATE `produitderive`
SET `photo` = './img/produitsDerives/gelee.webp'
WHERE `photo` = './img/produitsDerives/gelee.jpg';

UPDATE `photoaccueil`
SET `photo` = './PhotosSlider/img04.webp'
WHERE `photo` = './img/imgAccueil.jpg';
