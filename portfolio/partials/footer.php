  </main>
  <footer class="site-footer">
    <div class="site-footer-inner container">
      <div>
        <div class="kicker">LionDevs</div>
        <div class="small">© <?php echo date('Y'); ?> LionDevs. Всички права запазени.</div>
      </div>
      <div class="nav">
        <?php if (!empty($config['company']['github'])): ?>
          <a href="<?php echo htmlspecialchars($config['company']['github'], ENT_QUOTES); ?>" target="_blank" rel="noreferrer">GitHub ↗</a>
        <?php endif; ?>
        <?php if (!empty($config['company']['discord'])): ?>
          <a href="<?php echo htmlspecialchars($config['company']['discord'], ENT_QUOTES); ?>" target="_blank" rel="noreferrer">Discord ↗</a>
        <?php endif; ?>
        <a href="mailto:<?php echo htmlspecialchars($config['company']['email'] ?? 'contact@liondevs.dev', ENT_QUOTES); ?>">Имейл</a>
      </div>
    </div>
  </footer>
</body>
</html>