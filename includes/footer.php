<?php $cfg=site(); ?>
</main>
<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-brand">
      <img src="<?= e($cfg['logo_url']??'/assets/img/logo-full.png') ?>" alt="Cédrick Benittah">
      <p><?= e($cfg['footer_note'] ?? 'Adoption IA, transformation et gouvernance responsable.') ?></p>
      <div class="socials">
        <?php if(!empty($cfg['linkedin_url']) && $cfg['linkedin_url'] !== '#'): ?><a href="<?= e($cfg['linkedin_url']) ?>" target="_blank" rel="noopener" aria-label="LinkedIn">in</a><?php endif; ?>
        <?php if(!empty($cfg['malt_url'])): ?><a href="<?= e($cfg['malt_url']) ?>" target="_blank" rel="noopener" aria-label="Malt">M</a><?php endif; ?>
        <?php if(!empty($cfg['phone'])):?><a href="tel:<?= e(preg_replace('/[^\d+]/','',$cfg['phone'])) ?>" aria-label="Téléphone">☎</a><?php endif; ?>
        <a href="mailto:<?= e($cfg['email']??'cedrick@benittah.com') ?>" aria-label="Email">✉</a>
      </div>
    </div>
    <div><div class="footer-title">Navigation</div><div class="footer-links"><a href="/">Accueil</a><a href="/diagnostic-adoption-ia-transformation/">Diagnostic IA</a><a href="/cedrick-benittah/">Cédrick Benittah</a><a href="/accompagnements/">Offres</a><a href="/articles/">Articles</a><a href="/contact/">Contact</a></div></div>
    <div><div class="footer-title">Accompagnements</div><div class="footer-links"><a href="/diagnostic-adoption-ia-transformation/">Diagnostic IA</a><a href="/gouvernance-ia-ia-act/">Gouvernance IA & IA Act</a><a href="/accompagnements/acculturation-ia/">Adoption IA terrain</a><a href="/accompagnements/">Toutes les offres</a></div></div>
    <div><div class="footer-title">Ressources</div><div class="footer-links"><a href="/articles/">Tous les articles</a><a href="/articles/?pillar=IA%20%26%20Adoption">IA & Adoption</a><a href="/articles/ia-act-organisations-anticiper/">IA Act</a><a href="/articles/ia-generative-passer-experimentation-impact/">IA générative</a></div></div>
    <div><div class="footer-title">Légal</div><div class="footer-links"><a href="/mentions-legales/">Mentions légales</a><a href="/politique-confidentialite/">Confidentialité</a></div></div>
    <div><div class="footer-title">Contact</div><div class="footer-links contact-list"><a href="mailto:<?= e($cfg['email']??'cedrick@benittah.com') ?>">✉ <?= e($cfg['email']??'cedrick@benittah.com') ?></a><?php if(!empty($cfg['phone'])):?><a href="tel:<?= e(preg_replace('/[^\d+]/','',$cfg['phone'])) ?>">☎ <?= e($cfg['phone']) ?></a><?php endif; ?><span>⌖ <?= e($cfg['address']??'France') ?></span></div></div>
  </div>
  <div class="container copyright">© <?= date('Y') ?> Cédrick Benittah. Tous droits réservés. <?= e($cfg['company_credit']??'') ?></div>
</footer>
</body>
</html>
